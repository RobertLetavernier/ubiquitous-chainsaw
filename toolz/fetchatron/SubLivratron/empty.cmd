@echo off
title Empty
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set folder=_out
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
echo Empty folder "%folder%" ?
pause

RD /S /Q %folder%
MD %folder%