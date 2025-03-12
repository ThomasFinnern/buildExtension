<?php

namespace Finnern\BuildExtension\cleanUpLib;

require_once 'autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;


$HELP_MSG = <<<EOT
    >>>
    class clean4GitCheckin

    Reads file, trims each line and writes result back on change
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

$tasksLine = ' task:clean4GitCheckin'
//    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
//    . ' /isNoRecursion=true'
//    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
// -> self
    . ' /srcRoot="./"'
    . ' /isNoRecursion=true'
;
//======================================================================
//
// ToDo: file list restriction: no BMP ...
//
//======================================================================

//$srcRoot = './../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop';
//$srcRoot = './../../RSGallery2_J4';
$srcRoot = '';

//$linkText = "GNU General Public link version 2 or later;";
//$this->link = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
$linkText = '';

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
$optionFiles [] = 'xTestOptionFile.opt';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            $taskFile = $option;
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
   collect task
--------------------------------------------------*/

// for start / end diff
$start = commandLineLib::print_header($options, $inArgs);

$task = new task();

if ($taskFile != "") {
    $testTask = $task->extractTaskFromFile($taskFile);
//    if (!empty ($hasError)) {
//        print ("Error on function extractTasksFromFile:" . $hasError
//            . ' path: ' . $taskFile);
//    }
} else {
    $testTask = $task->extractTaskFromString($tasksLine);
    //if (empty ($task->name)) {
    //    print ("Error on function extractTaskFromString:" . $hasError
    //        . ' tasksLine: ' . $tasksLine);
    //    $hasError = -302;
    //}
}

$oClean4GitCheckin = new clean4GitCheckin();

$hasError = $oClean4GitCheckin->assignTask($task);
if ($hasError) {
    print ("Error on function assignTask:" . $hasError);
}
if (!$hasError) {
    $hasError = $oClean4GitCheckin->execute();
    if ($hasError) {
        print ("Error on function execute:" . $hasError);
    }
}

print ($oClean4GitCheckin->text() . "\r\n");

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

