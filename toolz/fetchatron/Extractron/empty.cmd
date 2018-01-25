@echo off
title Empty
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set folder=_out
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
echo Empty folder "%folder%" ?
pause

del _deployReport.txt
RD /S /Q %folder%
MD %folder%
