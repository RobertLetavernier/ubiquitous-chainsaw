@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set cmdname=%~n0
set mytitle=Dumpatron - %cmdname%
TITLE %cmdname%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
CALL _run_dump.cmd %cmdname%
