@ECHO OFF
REM build_fix.bat
REM
CLS

ECHO PHP exchangeAll_actCopyrightYearLinesCmd.php
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

ECHO ----------------------------------------------
ECHO.

echo --- "%ExePath%php.exe" exchangeAll_actCopyrightYearLinesCmd.php -f ../../LangMan4DevProject/.buildPHP/exchangeAll_actCopyrightYearLines.tsk %1
"%ExePath%php.exe" exchangeAll_actCopyrightYearLinesCmd.php -f ../../LangMan4DevProject/.buildPHP/exchangeAll_actCopyrightYearLines.tsk %1

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
