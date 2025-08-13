<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

/*================================================================================
Class fileHeader data
================================================================================*/

/**
 * 
 */
interface fileUseDataInterface
{
    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    function __construct();

    function init() : void;

    function extractUseLines(array $lines = []) : void;

    static function sortUseLines(array $lines = [], bool $isSortByLength = false);

    // function extractNameFromHeaderLine(string $line) : array;

    // function scan4HeaderValueInLine(string $name, string $line): string;

    function fileLines(): array;

    function useLinesSorted(): array;

    function isChanged() : bool;

} // fileHeader
