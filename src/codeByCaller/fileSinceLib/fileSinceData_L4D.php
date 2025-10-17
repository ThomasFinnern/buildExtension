<?php
namespace Finnern\BuildExtension\src\codeByCaller\fileSinceLib;

/*================================================================================
Class fileUseData_RSG2
================================================================================*/

/**
 * Keeps all lines of a PHP files as preLines,useLines and postLines
 * The “Use” lines are as in the following line
 *     use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
 *
 *  Does remove backslash from "use \Joomla\CMS\Language\Text;"
 *
 *  !!! comments above use line will be ignored and deleted
 */
class fileSinceData_L4D extends fileSinceDataBase
{
    public function __construct()
    {
        parent::__construct();

        print ("->fileSinceData__L4D: " . PHP_EOL);

    }

//    public function init(): void
//    {
//        parent::init();
//
//        $this->identSize = 4;
//    }

} // fileHeader
