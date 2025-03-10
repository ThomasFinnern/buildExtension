<?php

namespace Finnern\BuildExtension\src\tasksLib;

require_once '../autoload/autoload.php';
// use DateTime;

use Finnern\BuildExtension\src\tasksLib\commandLineLib ;
use Finnern\BuildExtension\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    task class

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:h12345";
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

$taskLine = 'Task::task1';
$taskLine = 'Task::task1 /option1 ';
//$taskLine = 'Task::task1 /option2=Option';
//$taskLine = 'Task::task1 /option3="01_Xteststring"';
$taskLine = 'Task::task1 /option1 /option2=Option /option3="01_Xteststring"';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
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

//--- call function ---------------------------------

// for start / end diff
$start = commandLineLib::print_header($options, $inArgs);

$oTask = new task();

$oTaskResult = $oTask->extractTaskFromString($taskLine);

print ($oTask->text() . "\r\n");
print ("Line: '" . $oTaskResult->text4Line() . "'" . "\r\n");

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

