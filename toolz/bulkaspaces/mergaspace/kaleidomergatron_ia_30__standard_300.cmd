@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set cmdname=%~n0
:: Split cmdname on "_"
set "name2=%cmdname:*_=%"
for /F "tokens=1 delims=_" %%F IN ("%cmdname%") do set "name1=%%F"
set mytitle=Kaleidomergatron - %name2%
TITLE %mytitle%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set    user=root
REM set /P pass=Enter SQL password for user "%user%": 
set    pass=root 

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:: Build appender
:: %% escapes %
:: ^> escapes >
:: %~1 Removes quotes from the first command line argument
:: %~n1 extends %1 in filename only
>  ap.cmd echo @echo off
>> ap.cmd echo echo Bulking %%~1
>> ap.cmd echo ^>^> tmpMerge echo -- ------------------------
>> ap.cmd echo ^>^> tmpMerge echo -- copy of %%~1
>> ap.cmd echo ^>^> tmpMerge echo -- ------------------------
>> ap.cmd echo ^>^> tmpMerge echo drop database if exists %%~n1;
>> ap.cmd echo ^>^> tmpMerge echo create database %%~n1;
>> ap.cmd echo ^>^> tmpMerge echo use %%~n1;
>> ap.cmd echo ^>^> tmpMerge type %%~1
>> ap.cmd echo del %%~1

:: Init tmpMerge
>  tmpMerge echo -- ------------------------
>> tmpMerge echo -- Bulking before all
>> tmpMerge echo -- ------------------------ 
>> tmpMerge echo SET autocommit=0;
>> tmpMerge echo SET unique_checks=0;
>> tmpMerge echo SET foreign_key_checks=0;

:: Append any SQL to tmpMerge with the appender
forfiles /m *.sql /C "cmd /c ap.cmd @file"

:: Finish tmpMerge
>> tmpMerge echo -- ------------------------
>> tmpMerge echo -- Bulking after all
>> tmpMerge echo -- ------------------------
>> tmpMerge echo SET foreign_key_checks=1;
>> tmpMerge echo SET unique_checks=1;
>> tmpMerge echo COMMIT;

:: Delete appender
del ap.cmd

REN tmpMerge %name2%.merged

