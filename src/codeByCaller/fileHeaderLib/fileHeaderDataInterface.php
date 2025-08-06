<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

/*================================================================================
Class fileHeader data
================================================================================*/

/**
 * keeps all variables of a PHP package description header
 * function headerText: Expected result. can be inserted/replace into php code file
 *    returns a set of header lines.
 * function extractHeaderValuesFromLines:
 *    To exchange parts of the header lines they may be extracted here.
 * The variables are global so you may read a file header, change data like actual year,
 * create the lines again here and replace the original file part
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
