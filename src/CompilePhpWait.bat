@ECHO OFF
REM Compiles given *.php file

php --version

REM echo.
echo --- %1
echo --- php -l compileall %1
php -l  %1
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
    php.exe -l  %1

    if errorlevel 1 goto :ErrAtRegSvr

REM @ECHO ON
goto :EOF

RESTART:

