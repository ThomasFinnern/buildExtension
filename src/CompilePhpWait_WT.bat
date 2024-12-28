@ECHO OFF
REM Compiles given *.php file

"C:\Program Files\php82\php.exe" --version

REM echo.
echo --- %1
echo --- "C:\Program Files\php82\php.exe" -l compileall %1
"C:\Program Files\php82\php.exe" -l  %1
if errorlevel 1 Call :ErrAtRegSvr %1
echo done

REM --- exit ----------------------
goto :EOF

REM ------------------------------------------
REM Print an error message
:ErrAtRegSvr
REM    @ECHO OFF
    Echo.
    ECHO !!! Please fix error at %1" !!!
    ECHO %time%
    Echo.
    PAUSE

    echo    * %1
    "C:\Program Files\php82\php.exe".exe -l  %1

    if errorlevel 1 goto :ErrAtRegSvr

REM @ECHO ON
goto :EOF

RESTART:

