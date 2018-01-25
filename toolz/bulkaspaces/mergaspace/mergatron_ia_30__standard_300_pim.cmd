@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set cmdname=%~n0
:: Split cmdname on "_"
set "name2=%cmdname:*_=%"
for /F "tokens=1 delims=_" %%F IN ("%cmdname%") do set "name1=%%F"
set mytitle=Mergatron - %name2%
TITLE %mytitle%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set    user=root
REM set /P pass=Enter SQL password for user "%user%": 
set    pass=root 
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

copy /b *.sql %name2%.merged

del *.sql
