@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set argn0=%~n0
set arg_1=%~1
set argn1=%~n1
set mytitle=Bulkatron
TITLE %mytitle%
for %%F IN (..\_config_*.cmd) do CALL "%%F"
for %%F IN (_config_*.cmd) do CALL "%%F"
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set report=%argn1%_bulkReport_%_filedate%.txt
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

echo Bulking for "%argn1%"

CALL ..\..\_getDate.cmd > %report%

>  temp1 echo -- ------------------------
>> temp1 echo -- Bulking for "%argn1%"
>> temp1 echo -- ------------------------
>> temp1 echo drop database if exists %argn1%;
>> temp1 echo create database %argn1%;
>> temp1 echo use %argn1%;
>> temp1 echo SET autocommit=0;
>> temp1 echo SET unique_checks=0;
>> temp1 echo SET foreign_key_checks=0;
>> temp1 echo -- ------------------------
>> temp1 echo -- Copying sqls
>> temp1 echo -- ------------------------

>> %report% copy /b *.sql temp2 

>> %report% copy /b temp1+temp2 temp3 
del temp1
del temp2

>> temp3 echo -- ------------------------
>> temp3 echo -- End of Bulking
>> temp3 echo -- ------------------------
>> temp3 echo SET foreign_key_checks=1;
>> temp3 echo SET unique_checks=1;
>> temp3 echo COMMIT;

echo Bulk done, playing bulk

set _options=-h %host% -P %port% -u %user% -p%pass% --debug-info
mysql %_options% < temp3 >> %report%  2>&1

del temp3

MSG * "%mytitle% has come to an end"

>> %report% echo Bulk finished
CALL getDate.cmd >> %report% 
