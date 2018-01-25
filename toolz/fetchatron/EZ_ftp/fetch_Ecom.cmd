@echo off
title Fetchatron Ecom Prod
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set     host=91.103.233.66
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
:: copy tmp.ftp + ftp.txt tmp.ftp
echo quit    >> tmp.ftp

ftp < tmp.ftp
:: FTP -d -s:tmp.ftp %host%

pause