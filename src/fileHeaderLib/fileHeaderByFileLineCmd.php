<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;

$HELP_MSG = <<<EOT
    >>>
    fileHeaderByFile class

    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "f:t:h12345";
$isPrintArguments = false;

[$inArgs, $options] = commandLineLib::argsAndOptions($argv, $optDefinition, $isPrintArguments);

$LeaveOut_01 = true;
$LeaveOut_02 = true;
$LeaveOut_03 = true;
$LeaveOut_04 = true;
$LeaveOut_05 = true;

/*--------------------------------------------
variables
--------------------------------------------*/
$srcfile = '';
// $srcfile = "./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php";

$taskLine = '';
$tasksLine = ' task:exchangeLicense'
    . ' /fileName="./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php"';
//$tasksLine = ' task:exchangeActCopyrightYear'
//    . ' /fileName="./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php"'
//    . ' /copyrightDate=1999'
//;

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 'f':
            $srcfile = $option;
            break;

        case 't':
            $taskLine = $option;
            break;

        case "h":
            exit($HELP_MSG);

        case "1":
            $LeaveOut_01 = true;
            print("LeaveOut_01");
            break;
        case "2":
            $LeaveOut_02 = true;
            print("LeaveOut__02");
            break;
        case "3":
            $LeaveOut_03 = true;
            print("LeaveOut__03");
            break;
        case "4":
            $LeaveOut_04 = true;
            print("LeaveOut__04");
            break;
        case "5":
            $LeaveOut_05 = true;
            print("LeaveOut__05");
            break;

        default:
            print("Option not supported '" . $option . "'");
            break;
    }
}

/*--------------------------------------------------
   call function
--------------------------------------------------*/

// for start / end diff
$start = commandLineLib::print_header($options, $inArgs);

//--- create class object ---------------------------------

$task = new task();

//--- extract tasks from string or file ---------------------------------

if ( ! empty ($taskFile)) {
    $testTask = $task->extractTaskFromFile($taskFile);
    //if (empty ($task->name)) {
    //    print ("Error on function extractTaskFromFile:" // . $hasError
    //        . ' Task file: ' . $taskFile);
    //    $hasError = -301;
    //}
} else {
    $testTask = $task->extractTaskFromString($tasksLine);
    //if (empty ($task->name)) {
    //    print ("Error on function extractTaskFromString:" . $hasError
    //        . ' tasksLine: ' . $tasksLine);
    //    $hasError = -302;
    //}
}

print ($task->text());

if (empty ($hasError)) {

	$oFileHeader = new fileHeaderByFileLine();

	//--- assign tasks ---------------------------------

    $hasError = $oFileHeader->assignTask($task);
    if ($hasError) {
        print ("Error on function assignTask:" . $hasError);
	}
 	
	//--- execute tasks ---------------------------------

    if (!$hasError) {
        $hasError = $oFileHeader->execute();
        if ($hasError) {
            print ("Error on function execute:" . $hasError);
        }
    }
}



commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

