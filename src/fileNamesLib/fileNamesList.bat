@ECHO OFF
REM FileNamesList.bat
REM
CLS

Set CmdArgs=
ECHO PHP fileNamesList.bat

REM Source path
REM Call :AddNextArg -p "..\..\RSGallery2_J4"
Call :AddNextArg -p "..\..\..\LangMan4Dev"

REM include file types
Call :AddNextArg -i "*.php *.xmp *.ini"

REM exclude file types
REM Call :AddNextArg -e "*.php *.xmp *.ini"

REM actual folder only, not recursively
Call :AddNextArg -a

REM write resulting list to file
Call :AddNextArg -w FileNamesList.txt

REM return sub object with exluded/included files

REM add command line
REM Call :AddNextArg %*

REM files with extension

REM files matching regex





ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO php.exe -f "./FileNamesListCmd.php" --  %CmdArgs% %*
php.exe -f "./FileNamesListCmd.php" --  %CmdArgs% %*

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
    Set NextArg=%*
    Set CmdArgs=%CmdArgs% %NextArg%
    ECHO  '%NextArg%'
GOTO :EOF

