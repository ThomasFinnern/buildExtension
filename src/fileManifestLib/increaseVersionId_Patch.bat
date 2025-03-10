@ECHO OFF
REM increaseVersionId_Patch.bat
REM
CLS
ECHO PHP increaseVersionId_Patch.php
ECHO.

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
REM "%ExePath%php.exe" --version
REM ECHO.

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
echo --- "%ExePath%php.exe" ./increaseVersionIdCmd.php -f increaseVersionId_Patch.tsk %1
"%ExePath%php.exe" increaseVersionIdCmd.php -f increaseVersionId_Patch.tsk %1

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF

