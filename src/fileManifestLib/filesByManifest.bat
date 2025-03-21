@ECHO OFF
REM build_release.bat
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
REM ?????
echo --- "%ExePath%php.exe" ./filesByManifest.php -f d:\Entwickl\2025\_gitHub\LangMan4Dev\lang4dev.xml %1
"%ExePath%php.exe" ./filesByManifest.php -f d:\Entwickl\2025\_gitHub\LangMan4Dev\lang4dev.xml %1

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
