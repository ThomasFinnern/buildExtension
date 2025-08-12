@ECHO OFF
REM alignAll_use_Lines_JG.bat
REM
CLS

ECHO PHP alignAll_use_LinesCmd.php alignAll_use_Lines_JG.tsk
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

pushd  ..\fileHeaderLib
REM pushd  ..\
ECHO Path: %cd% 

REM echo --- "%ExePath%php.exe" alignAll_use_Lines_JGCmd.php -f ../../../JoomGallery_fith_dev/.buildPHP/alignAll_use_Lines_JG.tsk %1
REM "%ExePath%php.exe" alignAll_use_Lines_JGCmd.php -f ../../../JoomGallery_fith_dev/.buildPHP/alignAll_use_Lines_JG.tsk %1
REM echo --- "%ExePath%php.exe" fileHeaderLib/alignAll_use_Lines_JGCmd.php -f tsk_file_examples/alignAll_use_Lines_JG.tsk %1
REM "%ExePath%php.exe" fileHeaderLib/alignAll_use_Lines_JGCmd.php -f tsk_file_examples/alignAll_use_Lines_JG.tsk %1
echo --- "%ExePath%php.exe" alignAll_use_Lines_JGCmd.php -f ..\tsk_file_examples/alignAll_use_Lines_JG.tsk %1
"%ExePath%php.exe" alignAll_use_Lines_JGCmd.php -f ..\tsk_file_examples/alignAll_use_Lines_JG.tsk %1

popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
