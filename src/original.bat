@ECHO OFF
REM original
REM <What it does>
CLS

Set CmdArgs=
ECHO PHP original.php

REM source file
Call :AddNextArg -s "./original.php"

REM destination file
Call :AddNextArg -d "./original.php"


REM Source path
REM Call :AddNextArg -p "..\..\RSGallery2_J4"

REM add command line
REM Call :AddNextArg %*

ECHO.
ECHO ------------------------------------------------------------------------------
ECHO Start cmd:
ECHO.
ECHO php.exe -f "./original.php" --  %CmdArgs% %*
php.exe -f "./original.php" --  %CmdArgs% %*

GOTO :EOF

REM ------------------------------------------
REM Adds given argument to the already known command arguments
:AddNextArg
	Set NextArg=%*
	Set CmdArgs=%CmdArgs% %NextArg%
	ECHO  '%NextArg%'
GOTO :EOF

