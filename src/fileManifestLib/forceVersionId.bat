@ECHO OFF
REM forceVersionId.bat
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

ECHO ----------------------------------------------
ECHO.



ECHO ----------------------------------------------
ECHO.

ECHO Path: %cd% 

echo --- "%ExePath%php.exe" ./forceVersionIdCmd.php -f forceVersion.tsk %OptionFile%
"%ExePath%php.exe" forceVersionIdCmdCmd.php -f forceVersion.tsk %OptionFile%

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
