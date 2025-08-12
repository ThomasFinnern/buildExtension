@ECHO OFF
REM build_step.bat
REM like build_develop but does increase minor 'build' number 
CLS

ECHO PHP buildExtensionCmd build_step.tsk
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

echo --- "%ExePath%php.exe" ..\buildExtensionCmd.php -f ../../../LangMan4DevProject/.buildPHP/build_step.tsk %1
"%ExePath%php.exe" ..\buildExtensionCmd.php -f ../../../LangMan4DevProject/.buildPHP/build_step.tsk %1

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF

