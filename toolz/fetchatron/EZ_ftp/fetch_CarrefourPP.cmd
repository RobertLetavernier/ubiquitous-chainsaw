@echo off
title Fetchatron Carreour PP
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set     host=easytoretail-preprod1.gutenberg-networks.com
set     lgin=media
set     pass=kozQkB
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