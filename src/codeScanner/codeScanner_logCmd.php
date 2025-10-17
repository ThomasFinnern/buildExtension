<?php

namespace Finnern\BuildExtension\src\codeScanner;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\doFileTasks;
use Finnern\BuildExtension\src\fileSinceLib\exchangeAll_sinceInFiles;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\tasksLib\tasks;


$HELP_MSG = <<<EOT
    >>>
    class 

    Reads file, exchanges all @since lines to expected format " * @since v.mm 
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:t:c:f:o:h12345";
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
$collectedTasks = new tasks;

$basePath = ".\\";

$tasksLine = ' task:codeScanner_logDepth'
//    . ' /srcRoot="."'
    . ' /srcRoot="../../testDataRegression/scanCode"'
    . ' /fileName="HtmlView.php"'
;

$tasksLine = ' task:codeScanner_logIdent'
//    . ' /srcRoot="."'
    . ' /srcRoot="../../testDataRegression/scanCode"'
///    . ' /srcRoot="D:/Entwickl/2025/_gitHub/RSGallery2_J4"'
//    . ' /fileName="HtmlView.php"'
//    . ' /fileName="install_rsg2.php"'
//    . ' /fileName=".removeCommentPHP.php"'
    . ' /fileName=".indent.php"'
;

//$tasksLine = "";

//$taskFile="./build_Develop.tsk";
//$taskFile="./build_release.tsk";
// $taskFile = "";
// $taskFile = '../../J_LangMan4ExtDevProject/.buildPHP/exchangeAll_sinceInFiles.tsk';
//**$taskFile = '../tsk_file_examples/exchangeAll_sinceInFiles.tsk';
//$taskFile = '../tsk_file_examples/alignAll_use_Lines_JG.tsk';

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
//$optionFiles [] = '..\options_version_tsk\build_release.opt';
//$optionFiles [] = '..\options_version_tsk\build_major.opt

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
        case 's':
            $tasks = $option;
            break;

        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            $taskFile = $option;
            break;

        // separate list of task files
        case 'c':
            $collectedTasks->extractTasksFromFile($option);
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

$start = commandLineLib::print_header($options, $inArgs);

/*--------------------------------------------------
   collect task
--------------------------------------------------*/

//--- create class object ---------------------------------

$oDoFileTasks = new codeScanner_log();
$tasks = new tasks();

//--- extract tasks from string or file ---------------------------------

if ( ! empty ($tasksFile)) {
    print ("taskFile found: " . $tasksFile . PHP_EOL);
    $tasks = $tasks->extractTasksFromFile($tasksFile);
} else {
    if ($collectedTasks->count() > 0) {
        $tasks->assignTasks($collectedTasks);
    } else {
        print ("taskFile empty, TaskLine: " . $tasksLine . PHP_EOL);
        $testTasks = $tasks->extractTasksFromString($tasksLine);
        if (!empty ($hasError)) {
            print ("!!! Error on function extractTasksFromString:" . $hasError
                . ' path: ' . $basePath . PHP_EOL);
        }
    }
}

print ($tasks->text());

/*----------------------------------------------------------
   assign tasks to DoFileTasks class
----------------------------------------------------------*/

// //--- extract tasks from string or file ---------------------------------

// if ($tasksFile != "") {
// $hasError = $oDoFileTasks->extractTasksFromFile($tasksFile);
// if (!empty ($hasError)) {
// print ("!!! Error on function extractTasksFromFile:" . $hasError
// . ' path: ' . $basePath . PHP_EOL);
// }

// } else {
// if ($collectedTasks->count() > 0) {
// $testTasks = $oDoFileTasks->assignTasks($collectedTasks);
// } else {
// $testTasks = $oDoFileTasks->extractTasksFromString($tasksLine);
// //if (!empty ($hasError)) {
// //    print ("!!! Error on function extractTasksFromString:" . $hasError
// //        . ' path: ' . $basePath . PHP_EOL);
// //}
// }
// }

// print ($oDoFileTasks->tasksText());

/*--------------------------------------------------
   execute task
--------------------------------------------------*/

if (empty ($hasError)) {

    //--- assign tasks ---------------------------------

    $oDoFileTasks->assignTasks($tasks);

    //--- execute tasks ---------------------------------

    // create task classes, when task execute is issued the task does execute
    $hasError = $oDoFileTasks->execute();

    if ($hasError) {
        print ("%%% doFileTaskCmd Error: " . $hasError . " on execute task: " . $oDoFileTasks->actTaskName . PHP_EOL);
    }

    if (! $hasError) {
        print ($oDoFileTasks->text() . PHP_EOL);
    }

}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);

