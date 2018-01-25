@echo off
title Fetchatron Carreour Prod
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set     host=easytoretail-carrefour.gutenberg-networks.com
set     lgin=media
set     pass=yNOF2s
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