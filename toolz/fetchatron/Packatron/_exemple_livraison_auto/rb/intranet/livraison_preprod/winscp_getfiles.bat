> winscp_getfiles.txt echo @echo off
>> winscp_getfiles.txt echo option batch on
>> winscp_getfiles.txt echo option confirm off
>> winscp_getfiles.txt echo open ftp://myrochebobois:D2PQdhhS@rochebobois20.ecritel.net
>> winscp_getfiles.txt echo option transfer binary
rem use ^ to escape caracters https://ss64.com/nt/echo.html
>> winscp_getfiles.txt echo get -filemask=^|/myintranet/logs/;/myintranet/media/;/myintranet/TeamViewerQS.exe;/myintranet/wkhtmltopdf-i386 /myintranet/* %backuppath%rb_intranet_preprod_%scriptid%\ 
>> winscp_getfiles.txt echo close
>> winscp_getfiles.txt echo exit
