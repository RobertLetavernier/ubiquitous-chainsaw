@echo off
call %~dp0..\..\..\_conf.bat

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM TODOs : 
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set mytitle=RB - MYProject - livraison preprod
TITLE %mytitle%
set currentproject=rb_3d
set currentbranch=rb_3d_1.0

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM pendant les tests uniquement
REM set scriptid=2017-07-10_134210
set scriptpath=%batch_root_path%rb\my_projects\livraison_preprod\
set svndevfolder=https://lisbonne/svn/%currentproject%/branches/%currentbranch%/dev

REM gets revision number
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
REM remplacement du numéro de version
cscript %batch_root_path%replace.vbs %temppath%%scriptid%\conf\_version.inc.php ".0000" ".%revnumber%"
REM création du zip
%zipcmd% %temppath%%scriptid%\dev.zip %temppath%%scriptid%\*
	

REM Copie du zip dans le répertoire distant  /opt/nfs/preprod/_delivery
call %scriptpath%winscp_sendfiles.bat
%winscpcmd%  /script=%scriptpath%winscp_sendfiles.txt
del winscp_sendfiles.txt


MSG * "%mytitle% : Verifier que le backup est bien present avant de continuer"
echo "Verifier que le backup est bien present avant de continuer"
echo dans %backuppath%rb_myprojects_preprod_%scriptid%\ 
pause
MSG * "%mytitle% : deziper le zip en executant le batch distant /opt/nfs/preprod/_delivery/unzip_file.bat"
pause
MSG * "%mytitle% : modifier le numero de version et le reporter dans installation overview"
pause
MSG * "%mytitle% : verifier s il y a des patchs à jouer"
pause
MSG * "%mytitle% has come to an end"
