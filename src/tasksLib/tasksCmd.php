<?php

namespace Finnern\BuildExtension\src\tasksLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    class tasks

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:f:o:h12345";
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

//$tasksLine = '/option1 $optionLine = /option2=Option /option3="01 test space string"';
$tasksLine = "task:task00 "
    . 'task:task01 /option1 /option2=xxx /option3="01 test space string" '
    . 'task:task02 /optionX /option2=Y /optionZ="02_Ztest string" ';
//$tasksLine = "task:clean4git";
//$tasksLine = "task:clean4release";

// build without properties: component path to rsgallery2_j4
// build without changes, increase id, prepare for release
// build type: component module plugin package
// build folder:
// build dev update version
// use Version ID  /increaseDevelop: x.x.x.n, release x.n.00, versionByConfig
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

$tasksFile = "";
// $taskFile="./taskFile.cmd";

$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
//$optionFiles [] = 'xTestOptionFile.opt';

//--- regression tasks --------------------------------------------

$tasksFile = "..\\tasksLib_regressionTests\\tasksMultiple\\tasks_optionPerLine.tsk";

//echo "getcwd(): " . getcwd() . PHP_EOL;
//echo "getcwd(): " . getcwd() . PHP_EOL;
//exit (-01);

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
        case 't':
            $tasksLine = $option;
            break;
        case 'f':
            $tasksFile = $option;
            break;

        case 'o':
            $optionFiles[] = $option;
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

$oTasks = new tasks();

//--- extract tasks from string or file ---------------------------------

if ( ! empty ($tasksFile)) {
    $oTasksResult = $oTasks->extractTasksFromFile($tasksFile);
} else {

    if ( ! empty ($tasksLine)) {
        $oTasksResult = $oTasks->extractTasksFromString($tasksLine);
    }
}

print ($oTasks->text() . PHP_EOL);
print ("Resulting line: '" . $oTasksResult . "'" . PHP_EOL);

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);
