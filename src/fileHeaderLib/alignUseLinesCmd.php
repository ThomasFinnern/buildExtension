<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;

/*================================================================================
Class exchangeAll_actCopyrightYear
================================================================================*/

class exchangeAll_actCopyrightYearLines extends baseExecuteTasks
    implements executeTasksInterface
{
    //--- use file lines for task ----------------------

    public fileHeaderByFileLine $fileHeaderByFileLine;

    public string $yearText = "";
    /**
     * @var fileNamesList
     */

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion = false, $yearText = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . "\r\n");
//            print ("srcRoot: " . $srcRoot . "\r\n");
//            print ("yearText: " . $yearText . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct($srcRoot, $isNoRecursion);

            $this->yearText = $yearText;

            //--- use file lines for task ----------------------

            $this->fileHeaderByFileLine = new fileHeaderByFileLine();

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    public function execute(): int
    {
        // TODO: Implement execute() method.
    }
}