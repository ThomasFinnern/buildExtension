@ECHO OFF
REM Compiles all *.php file in directory
CLS

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

for /f %%f in ('dir /b *.php') do (
    REM echo.
    echo --- "%%f"
    "%ExePath%php.exe -l  %%f >nul
    if errorlevel 1 Call :ErrAtRegSvr %%f

)
REM --- exit ----------------------
goto :EOF

REM ------------------------------------------
REM Print an error message
:ErrAtRegSvr
    @ECHO OFF
    Echo.
    ECHO !!! Please fix error at %1" !!!
    ECHO %time%
    Echo.
    PAUSE

    echo    * %1
    "%ExePath%php.exe -l  %1

    if errorlevel 1 goto :ErrAtRegSvr

REM @ECHO ON
goto :EOF

RESTART:

