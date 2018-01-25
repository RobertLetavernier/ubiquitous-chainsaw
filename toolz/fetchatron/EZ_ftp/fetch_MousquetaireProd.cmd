@echo off
title Fetchatron Carreour Prod
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
:: CARREFOUR Prod
REM set     host=easytoretail-carrefour.gutenberg-networks.com
REM set     lgin=media
REM set     pass=yNOF2s

:: Mousquetaire Prod
set     host=easytoretail-mousquetaires.gutenberg-networks.com
set     lgin=media
set     pass=AFaES9
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