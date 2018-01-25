@echo off
TITLE Extractron
:: ****************************************************************************
:: ****                           CONFIG                                   ****
:: ****************************************************************************
set    report=_deployReport.txt
set    source=_sources.txt
set    outdir=_out
set    appender=tmpApp.cmd

:: ****************************************************************************
:: ****                              RUN                                   ****
:: ****************************************************************************

:: Build appender
:: %% escapes %
:: ^> escapes >
:: %~1 Removes quotes from the first command line argument
:: %~n1 extends %1 in filename only
>  %appender% echo @echo off
>> %appender% echo set fullPart=%%~1
>> %appender% echo set dirPart=%%~dp1
>> %appender% echo set filePart=%%~nx1
>> %appender% echo echo Extracting %%fullPart%% = [%%dirPart%%] / [%%filePart%%]
>> %appender% echo set "tgtPath=%outdir%\%%dirPart%%"
>> %appender% echo set "tgtPath=%%tgtPath::=_%%"
>> %appender% echo echo tgtPath=%%tgtPath%%
>> %appender% echo IF EXIST "%%fullPart%%" (
>> %appender% echo echo full part exists
>> %appender% echo     IF NOT DEFINED filePart (
>> %appender% echo         echo copy folder %%dirPart%%
>> %appender% echo         robocopy %%dirPart%% %%tgtPath%% /E /LOG+:%report% /NP
>> %appender% echo     ) ELSE (
>> %appender% echo         echo copy file %%filePart%%
>> %appender% echo         MD "%%tgtPath%%"
>> %appender% echo         copy "%%dirPart%%\%%filePart%%" "%%tgtPath%%\%%filePart%%"
>> %appender% echo     )
>> %appender% echo ) ELSE (
>> %appender% echo echo full part doesnt exists
>> %appender% echo     IF NOT DEFINED filePart (
>> %appender% echo         echo create folder %%dirPart%%
>> %appender% echo         MD "%%tgtPath%%"
>> %appender% echo     ) ELSE (
>> %appender% echo         echo wrong data, nothing to do
>> %appender% echo     )
>> %appender% echo ) 

FOR /F "delims=" %%G IN (%source%) DO  CALL %appender% %%G 

del %appender%
pause