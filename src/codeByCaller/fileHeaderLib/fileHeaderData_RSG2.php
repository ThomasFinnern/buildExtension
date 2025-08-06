<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;


// ToDo: make copyright local

/*================================================================================
Class fileHeader data
================================================================================*/

use Finnern\BuildExtension\src\codeByCaller\fileManifestLib\copyrightTextFactory;

/**
 * keeps all variables of a PHP package description header
 * function headerText: Expected result. can be inserted/replace into php code file
 *    returns a set of header lines.
 * function extractHeaderValuesFromLines:
 *    To exchange parts of the header lines they may be extracted here.
 * The variables are global so you may read a file header, change data like actual year,
 * create the lines again here and replace the original file part
 */
class fileHeaderData_RSG2 extends fileHeaderDataBase
    implements fileHeaderDataInterface
{
    public function __construct()
    {
        parent::__construct();

        // rsg2 copyright handling
        $this->oCopyright = copyrightTextFactory::oCopyrightText('RSG2');
    }


} // fileHeader
