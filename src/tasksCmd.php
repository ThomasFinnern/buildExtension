<?php

namespace tasks;

require_once "./commandLine.php";
require_once "./task.php";
require_once "./tasks.php";

use function commandLine\argsAndOptions;
use function commandLine\print_end;
use function commandLine\print_header;


$HELP_MSG = <<<EOT
    >>>
    class tasks

    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:h12345";
$isPrintArguments = false;

[$inArgs, $options] = argsAndOptions($argv, $optDefinition, $isPrintArguments);

$LeaveOut_01 = true;
$LeaveOut_02 = true;
$LeaveOut_03 = true;
$LeaveOut_04 = true;
$LeaveOut_05 = true;

/*--------------------------------------------
variables
--------------------------------------------*/

//$tasksLine = '/option1 $optionLine = /option2=Option /option3="01_Xteststring"';
$tasksLine = "task:task00 "
    . 'task:task01 /option1 /option2=xxx /option3="01_Xteststring" '
    . 'task:task02 /optionX /option2=Y /optionZ="02_Zteststring" ';
//$tasksLine = "task:clean4git";
//$tasksLine = "task:clean4release";

// build without properties: component path to rsgallery2_j4
// build without changes, increase id, prepare for release
// build type: component module plugin package
// build folder:
// build dev update version
// Version ID  /increaseDevelop: x.x.x.n, release x.n.00, versionByConfig
//
//$tasksLine = "task:build /type=component";
//$tasksLine = "task:build /increaseId";
//$tasksLine = "task:build /increaseId /clean4release";
//$tasksLine = "task: ";
//$tasksLine = "task: ";
//$tasksLine = "task: ";
//$tasksLine = "task: ";
//$tasksLine = "task: ";
//$tasksLine = "task: ";
//$tasksLine = "task: ";


$taskFile = "";
// $taskFile="./taskFile.cmd";

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 't':
            $srcFile = $option;
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

// for start / end diff
$start = print_header($options, $inArgs);

$oTasks = new tasks();

if ($tasksLine != '') {
    $oTasksResult = $oTasks->extractTasksFromString($tasksLine);

    print ($oTasks->text() . "\r\n");
    print ("Line: '" . $oTasksResult->text4Line() . "'" . "\r\n");
}

if ($taskFile != '') {
    $oTasksResult = $oTasks->extractTasksFromFile($taskFile);

    print ($oTasks->text() . "\r\n");
    print ("Line: '" . $oTasksResult->text4Line() . "'" . "\r\n");
}

print_end($start);

print ("--- end  ---" . "\n");

