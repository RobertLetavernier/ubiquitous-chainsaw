> winscp_sendfiles.txt echo @echo off
>> winscp_sendfiles.txt echo option batch on
>> winscp_sendfiles.txt echo option confirm off
>> winscp_sendfiles.txt echo open RB
>> winscp_sendfiles.txt echo option transfer binary
>> winscp_sendfiles.txt echo put %temppath%%scriptid%\* /opt/nfs/PROD/mymedia/
>> winscp_sendfiles.txt echo close
>> winscp_sendfiles.txt echo exit
