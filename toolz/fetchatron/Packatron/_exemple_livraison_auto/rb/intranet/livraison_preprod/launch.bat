@echo off
call %~dp0..\..\..\_conf.bat

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM TODO : 
REM mettre en pause le script tant que filezilla est ouvert
	REM TASKLIST /NH /FI "IMAGENAME eq filezilla*"
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set mytitle=RB - intranet - livraison preprod
set currentproject=rb_intranet
set currentbranch=rb_intranet_1.4
TITLE %mytitle%

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set scriptpath=%batch_root_path%rb\intranet\livraison_preprod\
set svndevfolder=https://lisbonne/svn/%currentproject%/branches/%currentbranch%/dev

for /f %%i in ('svn info --show-item revision %svndevfolder%') do set revnumber=0000%%i 
set revnumber=%revnumber:~-5,4%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM BACKUP DES SOURCES - https://winscp.net/eng/docs/file_mask
call %scriptpath%winscp_getfiles.bat
%winscpcmd%  /script=%scriptpath%winscp_getfiles.txt
del winscp_getfiles.txt

REM Export des sources depuis svn
svn export %svndevfolder% %temppath%%scriptid%
rd /s /q %temppath%%scriptid%\.settings
del %temppath%%scriptid%\.buildpath
del %temppath%%scriptid%\.project
cscript %batch_root_path%replace.vbs %temppath%%scriptid%\conf\_version.inc.php ".000" ".%revnumber%"

REM upload des sources
MSG * "%mytitle% : Verifier que le backup est bien present avant de continuer"
echo "Verifier que le backup est bien present avant de continuer"
echo dans %backuppath%rb_intranet_preprod_%scriptid%\ 
pause
REM Copie des sources dans le répertoire distant  /myintranet/
call %scriptpath%winscp_sendfiles.bat
%winscpcmd%  /script=%scriptpath%winscp_sendfiles.txt
del winscp_sendfiles.txt


MSG * "%mytitle% : verifier s il y a des patchs a jouer"
pause
MSG * "%mytitle% : modifier le numero de version et le reporter dans installation overview"
pause
MSG * "%mytitle% has come to an end"
