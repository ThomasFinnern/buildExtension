<?php

namespace forceCreationDate;

require_once "./commandLine.php";
require_once "./forceCreationDate.php";

// use \DateTime;

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib ;


$HELP_MSG = <<<EOT
    >>>
    class forceCreationDate

    ToDo: option commands , example

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:f:h12345";
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

$tasksLine = ' task:forceCreationDate'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
    . ' /name=rsgallery2'
//    . ' /extension=RSGallery2'
//    . ' /date="22. Feb. 2022"'
;

// $taskFile = "";
$taskFile="./forceCreationDate.tsk";
$taskLine = "";


foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 't':
            $taskLine = $option;
            break;

        case 'f':
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

//--- call function ---------------------------------

yyy $taskFile ....;

// for start / end diff
$start = commandLineLib::print_header($options, $inArgs);

$task = new task();
$task->extractTaskFromString($tasksLine);

$oforceCreationDate = new forceCreationDate();

$hasError = $oforceCreationDate->assignTask($task);
if ($hasError) {
    print ("Error on function assignTask:" . $hasError);
}
if (!$hasError) {
    $hasError = $oforceCreationDate->execute();
    if ($hasError) {
        print ("Error on function execute:" . $hasError);
    }
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

