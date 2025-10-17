<?php
namespace Finnern\BuildExtension\src\codeByCaller\fileSinceLib;

/*================================================================================
Class fileUseData_JG
================================================================================*/

/**
 * Keeps all lines of a PHP files as preLines,useLines and postLines
 * The “Use” lines are as in the following line
 *    use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
 *
 * Does prepend backslash like "use \Joomla\CMS\Language\Text;"
 *
 *  !!! comments above use line will be ignored and deleted
 */
class fileSinceData_JG extends fileSinceDataBase
{
    public function __construct()
    {
        parent::__construct();

        print ("->fileSinceData_JG: " . PHP_EOL);

    }

    public function init(): void
    {
        parent::init();

        $this->identSize = 2;
    }


} // fileHeader
