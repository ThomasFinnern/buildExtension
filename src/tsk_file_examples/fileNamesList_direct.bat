@ECHO OFF
REM fileNameList.bat
REM
CLS

ECHO PHP fileNamesListCmd.php
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

REM more otions 

set OptionFile=

ECHO ----------------------------------------------
ECHO.

pushd  ..\fileNamesLib
REM pushd  ..\
ECHO Path: %cd% 


REM dir *Cmd.php
type 

echo --- "%ExePath%php.exe" fileNamesListCmd.php -f ..\tsk_file_examples/fileNamesList_direct.tsk %1
"%ExePath%php.exe" fileNamesListCmd.php -f ..\tsk_file_examples/fileNamesList_direct.tsk %1

popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
