@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set cmdname=%~n0
:: Split cmdname on "_"
set "name2=%cmdname:*_=%"
for /F "tokens=1 delims=_" %%F IN ("%cmdname%") do set "name1=%%F"
set mytitle=Clean - %name2%
TITLE %mytitle%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

del *.sql
del *.txt
