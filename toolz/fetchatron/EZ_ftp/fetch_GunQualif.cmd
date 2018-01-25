@echo off
title Fetchatron Gun Qualif
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

:: Mousquetaire Prod
set     host=easy3p2.ntic.fr
set     lgin=easy3p
set     pass=VM2mv55e
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
:: echo open> tmp.txt
:: echo %host%
echo open >  tmp.ftp
echo %host%>>  tmp.ftp
echo %lgin%>> tmp.ftp
echo %pass%>> tmp.ftp
echo binary>> tmp.ftp
type ftp.txt >> tmp.ftp
echo quit    >> tmp.ftp

ftp < tmp.ftp

pause