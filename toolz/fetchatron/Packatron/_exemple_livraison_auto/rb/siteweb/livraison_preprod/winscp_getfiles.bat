> winscp_getfiles.txt echo:@echo off
>> winscp_getfiles.txt echo:option batch on
>> winscp_getfiles.txt echo:option confirm off
>> winscp_getfiles.txt echo:open RB
>> winscp_getfiles.txt echo:option transfer binary
rem use ^ to escape caracters https://ss64.com/nt/echo.html
>> winscp_getfiles.txt echo:get -filemask=^|/opt/nfs_www/PREPROD/roche-bobois.com/brochures/;/opt/nfs_www/PREPROD/roche-bobois.com/debug/;/opt/nfs_www/PREPROD/roche-bobois.com/files/;/opt/nfs_www/PREPROD/roche-bobois.com/logs/;/opt/nfs_www/PREPROD/roche-bobois.com/media/;/opt/nfs_www/PREPROD/roche-bobois.com/*.xml.gz /opt/nfs_www/PREPROD/roche-bobois.com/ %backuppath%rb_siteweb_preprod_%scriptid%\
>> winscp_getfiles.txt echo:close
>> winscp_getfiles.txt echo:exit
