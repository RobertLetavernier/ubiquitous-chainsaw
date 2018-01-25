@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set argn0=%~n0
set argn1=%~n1
set argn2=%~1
TITLE Dumpatron
for %%F IN (..\_config_*.cmd) do CALL "%%F"
for %%F IN (_config_*.cmd) do CALL "%%F"
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

set _options=--opt -h %host% -P %port% -u %user% -p%pass%
echo dumping %~n1
mysqldump %_options% %~n1 > %~n1.sql
    
MSG * "%mytitle% has come to an end"
