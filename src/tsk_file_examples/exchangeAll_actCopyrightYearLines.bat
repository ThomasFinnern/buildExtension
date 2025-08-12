@ECHO OFF
REM exchangeAll_actCopyrightYearLines.bat
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

REM more otions 

set OptionFile=

ECHO ----------------------------------------------
ECHO.

pushd  ..\fileHeaderLib
REM pushd  ..\
ECHO Path: %cd% 

REM echo --- "%ExePath%php.exe" exchangeAll_actCopyrightYearLinesCmd.php -f ../../../JoomGallery_fith_dev/.buildPHP/exchangeAll_actCopyrightYearLines.tsk %1
REM "%ExePath%php.exe" exchangeAll_actCopyrightYearLinesCmd.php -f ../../../JoomGallery_fith_dev/.buildPHP/exchangeAll_actCopyrightYearLines.tsk %1
REM echo --- "%ExePath%php.exe" fileHeaderLib/exchangeAll_actCopyrightYearLinesCmd.php -f tsk_file_examples/exchangeAll_actCopyrightYearLines.tsk %1
REM "%ExePath%php.exe" fileHeaderLib/exchangeAll_actCopyrightYearLinesCmd.php -f tsk_file_examples/exchangeAll_actCopyrightYearLines.tsk %1
echo --- "%ExePath%php.exe" exchangeAll_actCopyrightYearLinesCmd.php -f ..\tsk_file_examples/exchangeAll_actCopyrightYearLines.tsk %1
"%ExePath%php.exe" exchangeAll_actCopyrightYearLinesCmd.php -f ..\tsk_file_examples/exchangeAll_actCopyrightYearLines.tsk %1

popd

goto :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF
