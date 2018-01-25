> winscp_getfiles.txt echo:@echo off
>> winscp_getfiles.txt echo:option batch on
>> winscp_getfiles.txt echo:option confirm off
>> winscp_getfiles.txt echo:open RB
>> winscp_getfiles.txt echo:option transfer binary
rem use ^ to escape caracters https://ss64.com/nt/echo.html
>> winscp_getfiles.txt echo:get -filemask=^|/opt/nfs/PROD/mymedia/etiquette/logs/;/opt/nfs/PROD/mymedia/etiquette/media/;/opt/nfs/PROD/mymedia/etiquette/admin/module/csv/downloaded_csv/;/opt/nfs/PROD/mymedia/charte*/;/opt/nfs/PROD/mymedia/files/;/opt/nfs/PROD/mymedia/etiquette/logs/;/opt/nfs/PROD/mymedia/etiquette/media; /opt/nfs/PROD/mymedia/ %backuppath%rb_mymedia_PROD_%scriptid%\
>> winscp_getfiles.txt echo:close
>> winscp_getfiles.txt echo:exit
