@ECHO OFF
REM cleanUp4Checkin.bat
REM Task to do before check in like remove space at the end of lines
CLS
ECHO PHP cleanUp4CheckinCmd.php
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
REM "%ExePath%php.exe" --version

ECHO ----------------------------------------------
ECHO.

echo --- "%ExePath%php.exe" clean4GitCheckinCmd.php -f ../../LangMan4DevProject/.buildPHP/clean4Checkin.tsk %1
"%ExePath%php.exe" clean4GitCheckinCmd.php -f ../../LangMan4DevProject/.buildPHP/clean4Checkin.tsk %1

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
	Set NextArg=%*
	Set CmdArgs=%CmdArgs% %NextArg%
	ECHO  '%NextArg%'
GOTO :EOF

