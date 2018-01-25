@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set argn0=%~n0
set argn1=%~n1
set argn2=%~1
TITLE Kaleidobulkatron
for %%F IN (..\_config_*.cmd) do CALL "%%F"
for %%F IN (_config_*.cmd) do CALL "%%F"
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set    report=_bulkReport.txt
set    getDatePath=..\_config_getDate.cmd
set    file=tmpMerge.txt
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

echo Kaleido-Bulking

CALL %getDatePath% > %report%

:: Build appender
:: %% escapes %
:: ^> escapes >
:: %~1 Removes quotes from the first command line argument
:: %~n1 extends %1 in filename only
>  ap.cmd echo @echo off
>> ap.cmd echo echo Bulking %%~1
>> ap.cmd echo ^>^> %report% echo Bulking %%~1
>> ap.cmd echo ^>^> %file% echo -- ------------------------
>> ap.cmd echo ^>^> %file% echo -- copy of %%~1
>> ap.cmd echo ^>^> %file% echo -- ------------------------
>> ap.cmd echo ^>^> %file% echo drop database if exists %%~n1;
>> ap.cmd echo ^>^> %file% echo create database %%~n1;
>> ap.cmd echo ^>^> %file% echo use %%~n1;
>> ap.cmd echo ^>^> %file% type %%~1

:: Init %file%
>  %file% echo -- ------------------------
>> %file% echo -- Bulking before all
>> %file% echo -- ------------------------ 
>> %file% echo SET autocommit=0;
>> %file% echo SET unique_checks=0;
>> %file% echo SET foreign_key_checks=0;

:: Append any SQL to %file% with the appender
forfiles /m *.sql /C "cmd /c ap.cmd @file"

:: Finish %file%
>> %file% echo -- ------------------------
>> %file% echo -- Bulking after all
>> %file% echo -- ------------------------
>> %file% echo SET foreign_key_checks=1;
>> %file% echo SET unique_checks=1;
>> %file% echo COMMIT;

:: Delete appender
del ap.cmd

:: play bulked sql
echo Bulk done, playing bulk
>> %report% echo Bulk done, playing bulk
CALL %getDatePath% >> %report%


set _options=-h %host% -u %user% -P %port% -p%pass% --debug-info
mysql %_options% < %file% >> %report%  2>&1

:: finish and report finish stamp
echo Bulk played
>> %report% echo Bulk played
CALL %getDatePath% >> %report%

REM del %file%

MSG * "Kaleidobulkatron has come to an end"
