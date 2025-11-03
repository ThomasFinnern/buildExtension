@ECHO OFF
REM alignAll_use_Lines.bat
REM
CLS

ECHO PHP alignAll_use_LinesCmd.php
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

REM more options 

set OptionFile=

ECHO ----------------------------------------------
ECHO.

pushd  ..\fileHeaderLib
REM pushd  ..\
ECHO Path: %cd% 

echo --- "%ExePath%php.exe" alignAll_use_LinesCmd.php -f ..\tsk_file_examples/alignAll_use_Lines_direct.tsk %1
"%ExePath%php.exe" alignAll_use_LinesCmd.php -f ..\tsk_file_examples/alignAll_use_Lines_direct.tsk %1

popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
