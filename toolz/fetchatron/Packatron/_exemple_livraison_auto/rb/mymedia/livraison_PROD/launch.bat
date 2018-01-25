@echo off
call %~dp0..\..\..\_conf.bat

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
REM TODO : 
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set mytitle=RB - mediatheque - livraison PROD
set currentproject=rb_mediatheque
TITLE %mytitle%

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set scriptpath=%batch_root_path%rb\mymedia\livraison_PROD\
set svndevfolder=https://lisbonne/svn/%currentproject%/dev/trunk

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
rd /s /q %temppath%%scriptid%\.cache
del %temppath%%scriptid%\.project
del %temppath%%scriptid%\.buildpath


MSG * "%mytitle% : Verifier le backup avant de copier les sources"
echo "Verifier le backup avant de continuer"
echo %backuppath%rb_mymedia_PROD_%scriptid%\
echo les fichiers sont dans : %temppath%%scriptid%
start "" %winscppath%winscp.exe RB
pause
MSG * "%mytitle% : verifier s il y a des patchs a jouer"
pause
MSG * "%mytitle% : completer installation overview installation overview rev : %revnumber%"
pause
MSG * "%mytitle% has come to an end"
