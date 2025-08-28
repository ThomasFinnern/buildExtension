<?php
namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

/*================================================================================
Class fileUseData_RSG2
================================================================================*/

use Finnern\BuildExtension\src\codeByCaller\fileManifestLib\copyrightTextFactory;

/**
 * Keeps all lines of a PHP files as preLines,useLines and postLines
 * The “Use” lines are as in the following line
 *     use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
 *
 *  Does remove backslash from "use \Joomla\CMS\Language\Text;"
 *
 *  !!! comments above use line will be ignored and deleted
 */
class fileUseData_L4D extends fileUseDataBase
{
    public function __construct()
    {
        parent::__construct();

        print ("->fileUseData__L4D: " . PHP_EOL);

        // rsg2 sorting
        $this->isSortByLength = false;

        // Remove backslash from "use \Joomla\CMS\Language\Text;"
        $this->isPrependBackSlash = false;
        $this->isRemoveBackSlash = true;
    }

} // fileHeader
