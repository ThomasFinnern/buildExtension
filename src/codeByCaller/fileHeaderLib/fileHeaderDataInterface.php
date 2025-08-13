<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

/*================================================================================
Class fileHeaderDataInterface 
================================================================================*/

/**
 * 
 */
interface fileHeaderDataInterface
{
    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    function __construct();

    function init() : void;

    function extractHeaderValuesFromLines(array $headerLines = []);

    function extractNameFromHeaderLine(string $line) : array;

    function scan4HeaderValueInLine(string $name, string $line): string;

    function text(): string;

    function headerLines(): array;

    function headerFormat($name, $value): string;

    function headerFormatCopyright(): string;

    function headerText() : string;

    function isDifferent(fileHeaderDataBase $fileHeaderExtern): bool;

    function isDifferentByString(string $externHeaderAsString): bool;

    function check4ValidHeaderLines(array|string $headerLines): bool;


} // fileHeader
