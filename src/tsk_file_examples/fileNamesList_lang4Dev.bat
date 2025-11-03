@ECHO OFF
REM FileNamesList.bat
REM
CLS

Set CmdArgs=
ECHO PHP fileNamesList.bat
ECHO.

REM REM Source path
REM REM Call :AddNextArg -p "..\..\RSGallery2_J4"
REM Call :AddNextArg -p "..\..\..\LangMan4Dev"

REM REM include file types
REM Call :AddNextArg -i "*.php *.xmp *.ini"

REM REM exclude file types
REM REM Call :AddNextArg -e "*.php *.xmp *.ini"

REM REM actual folder only, not recursively
REM Call :AddNextArg -a

REM REM write resulting list to file
REM Call :AddNextArg -w FileNamesList.txt

REM Source path
Call :AddNextArg -t "task:createFileNamesList /callerProjectId=RSG2 /srcRoot='../../LangMan4Dev' /includeExt='php' /isWriteListToFile=true /listFileName='../testData/FoundFileNamesList.txt'"

ECHO --- PHP -------------------------------------------
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

pushd  ..
REM pushd  ..\
ECHO Path: %cd% 

REM echo --- "%ExePath%php.exe" fileNamesListCmd.php %CmdArgs% %*
REM "%ExePath%php.exe" fileNamesListCmd.php %CmdArgs% %*
REM echo --- "%ExePath%php.exe" fileNamesListCmd.php -t '/callerProjectId=RSG2 /srcRoot="../../../LangMan4Dev" /includeExt = "php" /isWriteListToFile /listFileName="../../testData/FoundFileNamesList.txt"'
REM "%ExePath%php.exe" fileNamesListCmd.php -t '/callerProjectId=RSG2 /srcRoot="../../../LangMan4Dev" /includeExt = "php" /isWriteListToFile /listFileName="../../testData/FoundFileNamesList.txt" '
echo --- "%ExePath%php.exe" doFileTasksCmd.php -t "task:createFileNamesList /callerProjectId=RSG2 /srcRoot='../../LangMan4Dev' /includeExt='php' /isWriteListToFile=true /listFileName='../testData/FoundFileNamesList.txt'"
"%ExePath%php.exe" doFileTasksCmd.php -t "task:createFileNamesList /callerProjectId=RSG2 /srcRoot='../../LangMan4Dev' /includeExt='php' /isWriteListToFile=true /listFileName='../testData/FoundFileNamesList.txt'"

popd

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF

