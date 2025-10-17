@ECHO OFF
REM exchangeAll_sinceInFiles_log.bat
REM
CLS

ECHO PHP exchangeAll_sinceInFilesCmd.php.php exchangeAll_sinceInFiles_log.tsk
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

pushd  ..\..\src
REM pushd  ..\
ECHO Path: %cd% 

echo --- "%ExePath%php.exe" doFileTasksCmd.php -f ..\testDataRegression/scanCode/exchangeAll_sinceInFiles_log.tsk %1
"%ExePath%php.exe" doFileTasksCmd.php -f ..\testDataRegression/scanCode/exchangeAll_sinceInFiles_log.tsk %1

popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
