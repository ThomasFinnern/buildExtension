<?php

namespace Finnern\BuildExtension\src\tasksLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    task class

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:o:h12345";
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

// $taskLine = 'Task::task1';
// $taskLine = 'Task::task1 /option1 ';
//$taskLine = 'Task::task1 /option2=Option';
//$taskLine = 'Task::task1 /option3="01 test space string"';
$taskLine = 'Task::task1 /option1 /option2=Option /option3="01 test space string"';
//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
$optionFiles [] = 'xTestOptionFile.opt';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 't':
            $taskLine = $option;
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

// for start / end diff
$start = commandLineLib::print_header($options, $inArgs);

/*--------------------------------------------------
   collect task
--------------------------------------------------*/

$oTask = new task();

$oTaskResult = $oTask->extractTaskFromString($taskLine);

/*--------------------------------------------------
   tell task definition
--------------------------------------------------*/

print (">>>result 01" . "\r\n");
print ($oTask->text() . "\r\n");
print ("Line: '" . $oTaskResult . "'" . "\r\n");

/*--------------------------------------------------
   extract options from file(s)
--------------------------------------------------*/

if ( ! empty($optionFiles) ) {
    foreach ($optionFiles as $optionFile) {
        // print ("Option file: '" . $optionFile . "'" . "\r\n");
        $oTaskResult->extractOptionsFromFile($optionFile);
    }

    print (">>>result 02" . "\r\n");
    print ($oTask->text() . "\r\n");
    print ("Line: '" . $oTaskResult . "'" . "\r\n");
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

