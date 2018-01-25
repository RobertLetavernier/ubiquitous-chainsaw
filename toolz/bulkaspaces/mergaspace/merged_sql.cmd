@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set cmdname=%~n0
:: Split cmdname on "_"
set "name2=%cmdname:*_=%"
for /F "tokens=1 delims=_" %%F IN ("%cmdname%") do set "name1=%%F"
set mytitle=Convertron - %name1% to %name2%
TITLE %mytitle%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:: Append any SQL to tmpMerge with the appender
forfiles /m *.%name1% /C "cmd /c REN @file @fname.%name2%"
