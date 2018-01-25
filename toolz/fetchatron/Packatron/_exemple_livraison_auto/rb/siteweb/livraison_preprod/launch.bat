@echo off
call %~dp0..\..\..\_conf.bat

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM TODO : 
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set mytitle=RB - siteweb - livraison preprod
set currentproject=rb_siteweb
set currentbranch=rb_siteweb_1.4
TITLE %mytitle%

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set scriptpath=%batch_root_path%rb\siteweb\livraison_preprod\
set svndevfolder=https://lisbonne/svn/%currentproject%/branches/%currentbranch%/dev

REM gets revision number
for /f %%i in ('svn info --show-item revision %svndevfolder%') do set revnumber=%%i 
set revnumber=%revnumber:~-5,4%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM BACKUP DES SOURCES - https://winscp.net/eng/docs/file_mask
call %scriptpath%winscp_getfiles.bat
%winscpcmd%  /script=%scriptpath%winscp_getfiles.txt
del winscp_getfiles.txt

REM Export des sources depuis svn
svn export %svndevfolder% %temppath%%scriptid%
rd /s /q %temppath%%scriptid%\.settings
del %temppath%%scriptid%\.project
cscript %batch_root_path%replace.vbs %temppath%%scriptid%\conf\_version.inc.php ".0000" ".%revnumber%"


MSG * "%mytitle% : Attendre la fin du backup avant de continuer"
echo "Attendre la fin du backup avant de continuer"
pause
Copie des sources dans le répertoire distant  /myrb_siteweb/
call %scriptpath%winscp_sendfiles.bat
%winscpcmd%  /script=%scriptpath%winscp_sendfiles.txt
del winscp_sendfiles.txt


MSG * "%mytitle% : verifier s il y a des patchs a jouer"
pause
MSG * "%mytitle% : modifier le numero de version et le reporter dans installation overview"
pause
MSG * "%mytitle% has come to an end"
