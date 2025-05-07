@ECHO OFF
REM increaseVersionId.bat
REM options file can be selected 
REM d:develop, s:step, f:fix, r:release, m:major
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

set OptionFile=

if %1A==-dA (
	set OptionFile=-o options_version_tsk\build_develop.opt
)

if %1A==-sA (
	set OptionFile=-o options_version_tsk\build_step.opt
)

if %1A==-fA (
	set OptionFile=-o options_version_tsk\build_fix.opt
)

if %1A==-rA (
	set OptionFile=-o options_version_tsk\build_release.opt
)

if %1A==-mA (
	set OptionFile=-o options_version_tsk\build_major.opt
)


ECHO ----------------------------------------------
ECHO.

ECHO Path: %cd% 

echo --- "%ExePath%php.exe" ./increaseVersionIdCmd.php -f increaseVersionId.tsk %OptionFile%
"%ExePath%php.exe" increaseVersionIdCmd.php -f increaseVersionId.tsk %OptionFile%

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
