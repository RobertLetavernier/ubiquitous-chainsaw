@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set cmdname=%~n0
:: Split cmdname on "_"
set "name2=%cmdname:*_=%"
for /F "tokens=1 delims=_" %%F IN ("%cmdname%") do set "name1=%%F"
set mytitle=Dumpatron - %name2%
TITLE %mytitle%
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
CALL _run_bulkatron.cmd %name2%








> winscp_sendfiles.txt echo @echo off
>> winscp_sendfiles.txt echo option batch on
>> winscp_sendfiles.txt echo option confirm off
>> winscp_sendfiles.txt echo open RB
>> winscp_sendfiles.txt echo option transfer binary
>> winscp_sendfiles.txt echo put %temppath%%scriptid%\* /opt/nfs_www/PREPROD/roche-bobois.com/
>> winscp_sendfiles.txt echo close
>> winscp_sendfiles.txt echo exit
