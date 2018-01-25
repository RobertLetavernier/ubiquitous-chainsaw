@echo off
call %~dp0..\..\..\_conf.bat

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM TODO : lancer le backup en parallele de l export svn
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set mytitle=RB - siteweb - livraison PROD
set currentproject=rb_siteweb
set currentbranch=rb_siteweb_1.2
TITLE %mytitle%

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set scriptpath=%batch_root_path%rb\siteweb\livraison_PROD\
set svndevfolder=https://lisbonne/svn/%currentproject%/branches/%currentbranch%/dev

REM gets revision number
for /f %%i in ('svn info --show-item revision %svndevfolder%') do set revnumber=%%i 
set revnumber=%revnumber:~-5,4%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM BACKUP DES SOURCES - https://winscp.net/eng/docs/file_mask
call %scriptpath%winscp_getfiles.bat
%winscpcmd%  /script=%scriptpath%winscp_getfiles.txt
REM del winscp_getfiles.txt

REM Export des sources depuis svn
svn export %svndevfolder% %temppath%%scriptid%
rd /s /q %temppath%%scriptid%\.settings
del %temppath%%scriptid%\.project
cscript %batch_root_path%replace.vbs %temppath%%scriptid%\conf\_version.inc.php ".0000" ".%revnumber%"


MSG * "%mytitle% : Verifier le backup avant de copier les sources"
echo %backuppath%rb_siteweb_PROD_%scriptid%\
echo les fichiers sont dans : %temppath%%scriptid%
start "" %winscppath%winscp.exe RB
pause

MSG * "%mytitle% : verifier s il y a des patchs a jouer"
pause
MSG * "%mytitle% : completer installation overview installation overview rev : %revnumber%"
pause
MSG * "%mytitle% has come to an end"
