@echo off
title GoaBackuper
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
CALL _getDate.cmd

set isoToday=%_isodate:~0,10%

:: ****************************************************************************
:: ****                           CONFIG                                   ****
:: ****************************************************************************

:: -------------------------------------------------- PATHS ON THE MACHINE ----

:: Path to web root of [instance]
set    sources=@@configMe:instanceSrcPoint@@

:: Path to the storage folder
set    storage=@@configMe:instanceDataPoint@@\_backup
:: ------------------------------------------------------- SQL BACKUP INFO ----

:: Name of the table to dump separated by ,
set    schemas=@@configMe:instanceName@@_core, @@configMe:instanceName@@_mediadata, @@configMe:instanceName@@_pim

:: sql user and password to use. /P is the "prompt" switch
set    sqlhost=127.0.0.1
set    sqlport=3306
set    sqluser=@@configMe:bddUser@@
set    sqlpass=@@configMe:bddPass@@


:: ****************************************************************************
:: ****                         PRE CONFIG                                 ****
:: ****************************************************************************

:: ----------------------------------------------------- PRECONFIG SUBPATH ----

:: today's root for other backups
set    dayBkup=%storage%\%isoToday%

:: Sql's backup folder
set    sqlBkup=%dayBkup%\sql

:: Sources backup folder
set    webBkup=%dayBkup%\htdocs

:: Path to the robocopy logs
set    copylog=%dayBkup%\Robocopy_%isoToday%.log

:: Name for the cmd that redeploy the backup online.
set    rollbak=%dayBkup%\rollback_to_%isoToday%.cmd



:: ****************************************************************************
:: ****                              RUN                                   ****
:: ****************************************************************************

:: ------------------------------------------------------ Display & timout ----
echo ================================================
echo GoaBackuper
echo Backuping
echo  from  %sources%
echo    to  %webBkup%
echo ================================================
TIMEOUT 10

:: -------------------------------------------------------------------- MD ----
md %dayBkup%

:: ------------------------------------------------------------------- Web ----

:: Backuping sources to backup dir
robocopy %sources% /E %webBkup% /LOG+:%copylog% /NP

::  Bulding the rollback cmd
>  %rollbak% echo @echo off
>> %rollbak% echo title ReGoaBackuper
>> %rollbak% echo choice /C Yn /M "Roll back to %isoToday% ?"
>> %rollbak% echo IF errorlevel 2 Exit /B          
>> %rollbak% echo robocopy %webBkup% %sources% /NFL /NDL /MIR
>> %rollbak% echo pause 

:: ------------------------------------------------------------------- Sql ----

:: Backuping each SQL schema
:: note : --compatible=no_table_options
md %sqlBkup%
set _options=--opt -h %sqlhost% -P %sqlport% -u %sqluser% -p%sqlpass%
set _dumppath=%sqlBkup%\
FOR %%G IN (%schemas%) DO (
    echo dumping %%G
    mysqldump %_options% %%G > %_dumppath%\%%G.sql)

:: ------------------------------------------------------------------- END ----
echo End. Press any key to quit.
pause>nul