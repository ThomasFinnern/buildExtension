@ECHO OFF
REM updateAll_fileHeaders.bat
REM
CLS

REM Path for calling
set ExePath=C:\Program Files\php82\
REM ECHO ExePath: "%ExePath%"

if exist "%ExePath%php.exe" (
    REM path known (WT)
    ECHO ExePath: "%ExePath%"
) else (
    REM Direct call
    ECHO PHP in path variable
    set ExePath=
)

REM "C:\Program Files\php82\php.exe" --version
"%ExePath%php.exe" --version
ECHO.

REM echo.
echo --- "%ExePath%php.exe" ./updateAll_fileHeadersCmd.php -f updateAll_fileHeaders.tsk %1
"%ExePath%php.exe" updateAll_fileHeadersCmd.php -f updateAll_fileHeaders.tsk %1

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
