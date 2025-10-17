<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileSinceLib;

/*================================================================================
Class fileHeader data
================================================================================*/

/**
 *
 */
interface fileSinceDataInterface
{
    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    function __construct();

    function init(): void;

    function checkLine(string $line): bool;

    function exchangeLine(string $line = '', string $versionId = 'xx.xx',
                          int    $alignIdx = 0,
                          bool   $isForceVersion = false, bool $isLogOnly = false,
                          int    $lineNbr = 1,
                          string $prevAtLine = "",
                          bool   $isTabFound = true): string;


    function isChanged(): bool;

} // fileHeader
