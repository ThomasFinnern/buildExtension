<?php
namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

/*================================================================================
Class fileUseData_JG
================================================================================*/

use Finnern\BuildExtension\src\codeByCaller\fileManifestLib\copyrightTextFactory;

/**
 * Keeps all lines of a PHP files as preLines,useLines and postLines
 * The “Use” lines are as in the following line
 *    use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
 *
 * Does prepend backslash like "use \Joomla\CMS\Language\Text;"
 *
 *  !!! comments above use line will be ignored and deleted
 */
class fileUseData_JG extends fileUseDataBase
{
    public function __construct()
    {
        parent::__construct();

        // JG sorting
        $this->isSortByLength = true;

        // Add backslash like "use \Joomla\CMS\Language\Text;"
        $this->isPrependBackSlash = true;
        $this->isRemoveBackSlash = false;
    }


} // fileHeader
