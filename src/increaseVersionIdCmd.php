<?php

namespace increaseVersionId;

require_once "./commandLine.php";
require_once "./increaseVersionId.php";

// use \DateTime;

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib ;


$HELP_MSG = <<<EOT
    >>>
    class increaseVersionId

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:f:h12345";
$isPrintArguments = false;
//$isPrintArguments = true;

[$inArgs, $options] = commandLineLib::argsAndOptions($argv, $optDefinition, $isPrintArguments);

$LeaveOut_01 = true;
$LeaveOut_02 = true;
$LeaveOut_03 = true;
$LeaveOut_04 = true;
$LeaveOut_05 = true;

/*--------------------------------------------
variables
--------------------------------------------*/

$tasksLine = ' task:increaseVersionId'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
    . ' /name=rsgallery2'
//    . ' /extension=RSGallery2'
//    . ' /isIncreaseMajor'
//    . ' /isIncreaseMinor'
//    . ' /isIncreasePatch'
    . ' /isIncreaseBuild';

// $taskFile = "";
$taskFile="./increaseVersionId.tsk";
$tasksLine = "";

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            print ('->/f option: "' . $option . '"');
            $taskFile = $option;
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

//--- assign task line ------------------------------

$task = new task();
if ($taskFile != "") {
    $task->extractTaskFromFile($taskFile);
//    if (!empty ($hasError)) {
//        print ("Error on function extractTasksFromFile:" . $hasError
//            . ' path: ' . $taskFile);
//    }
} else {
    // Single task
    $task->extractTaskFromString($tasksLine);
}

//--- execute class tasks ------------------------------

$oIncreaseVersionId = new increaseVersionId();

$hasError = $oIncreaseVersionId->assignTask($task);
if ($hasError) {
    print ("Error on function assignTask:" . $hasError);
}
if (!$hasError) {
    $hasError = $oIncreaseVersionId->execute();
    if ($hasError) {
        print ("Error on function execute:" . $hasError);
    }
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

