@ECHO OFF
REM Compiles all *.php file in directory

"C:\Program Files\php82\php.exe" --version

for /f %%f in ('dir /b *.php') do (
    REM echo.
    echo --- "%%f"
    "C:\Program Files\php82\php.exe" -l  %%f >nul
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
    "C:\Program Files\php82\php.exe" -l  %1

    if errorlevel 1 goto :ErrAtRegSvr

REM @ECHO ON
goto :EOF

RESTART:

