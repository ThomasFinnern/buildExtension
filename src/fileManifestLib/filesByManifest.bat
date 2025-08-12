@ECHO OFF
REM build_release.bat
REM
CLS

ECHO PHP filesByManifestCmd.php
ECHO.

REM Path for calling
set ExePath=e:\wamp64\bin\php\php8.4.5\
REM ECHO ExePath: "%ExePath%"

if exist "%ExePath%php.exe" (
    REM path known (WT)
    ECHO ExePath: "%ExePath%"
) else (
    REM Direct call
    ECHO PHP in path variable
    set ExePath=
)

REM "%ExePath% --version
"%ExePath%php.exe" --version
ECHO.

REM echo.
echo --- "%ExePath%php.exe" ./filesByManifestCmd.php -f filesByManifest.tsk %1
"%ExePath%php.exe" ./filesByManifestCmd.php -f filesByManifest.tsk %1

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
