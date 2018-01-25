set user=root
set pass=root 
set phppath=C:\bin\apache\apache24_php7_x86\php7\
set winscppath="C:\Program Files (x86)\WinSCP\"
set backuppath=E:\working_dir\divers\backups\
set temppath=E:\program_data\temp\
set zippath="C:\Program Files\7-Zip\"
set filezillapath="C:\Program Files (x86)\FileZilla FTP Client\"
set batch_root_path=%~dp0

::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
set phpcmd=%phppath%php.exe -i %phppath%php.ini 
set winscpcmd=%winscppath%WinSCP.com
set zipcmd=%zippath%7z.exe a -y
set svngetrevcmd="svn info --show-item revision"

call %~dp0\_getDate.cmd
REM replaces " " by "_" and removes ":" https://ss64.com/nt/syntax-replace.html
set scriptid=%_isodate: =_%
set scriptid=%scriptid::=%
