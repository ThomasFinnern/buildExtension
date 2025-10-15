<?php

namespace Finnern\BuildExtension\src\fileSinceLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\fileSinceLib\exchangeAll_sinceInFiles;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;


$HELP_MSG = <<<EOT
    >>>
    class exchangeAll_sinceInFiles

    Reads file, exchanges all @since lines to expected format " * @since v.mm 
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "f:t:s:o:h12345";
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

$tasksLine = ' task:exchangeAll_sinceInFiles'
    . ' /callerProjectId=RSG2'
    . ' /srcRoot="../testData"'
    . ' /includeExt = "php"'
    . ' /fileName = "../../testData/sinceTestFile.php"'
//    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
    . ' /isNoRecursion=true'//    . ' /s='
;

//$tasksLine = "";

//$srcRoot = './../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop';
//$srcRoot = './../../RSGallery2_J4';
$srcRoot = '';
$isNoRecursion = true;

//$licenseText = "GNU General Public License version 2 or later;";
//$this->license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
$yearText = '';

//$taskFile="./exchangeAll_sinceInFiles.tsk";
//$taskFile="./build_Develop.tsk";
//$taskFile="./build_release.tsk";
// $taskFile = "";
// $taskFile = '../../J_LangMan4ExtDevProject/.buildPHP/exchangeAll_sinceInFiles.tsk';
//**$taskFile = '../tsk_file_examples/exchangeAll_sinceInFiles.tsk';
//$taskFile = '../tsk_file_examples/alignAll_use_Lines_JG.tsk';

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
//$optionFiles [] = '..\options_version_tsk\build_develop.opt';
//$optionFiles [] = '..\options_version_tsk\build_step.opt';
//$optionFiles [] = '..\options_version_tsk\build_fix.opt';
//$optionFiles [] = '..\options_version_tsk\build_release.opt';
//$optionFiles [] = '..\options_version_tsk\build_major.opt

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            $taskFile = $option;
            break;

        case 's':
            $srcRoot = $option;
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

//--- create class object ---------------------------------

$task = new task();

//--- extract tasks from string or file ---------------------------------

if (!empty ($taskFile)) {
    $testTask = $task->extractTaskFromFile($taskFile);
    //if (empty ($task->name)) {
    //    print ("!!! Error on function extractTaskFromFile:" // . $hasError
    //        . ' Task file: ' . $taskFile);
    //    $hasError = -301;
    //}
} else {
    $testTask = $task->extractTaskFromString($tasksLine);
    //if (empty ($task->name)) {
    //    print ("!!! Error on function extractTaskFromString:" . $hasError
    //        . ' tasksLine: ' . $tasksLine . PHP_EOL);
    //    $hasError = -302;
    //}
}

print ($task->text());

/*--------------------------------------------------
   execute task
--------------------------------------------------*/

if (empty ($hasError)) {

    $oAlignAll_use_LinesLines = new exchangeAll_sinceInFiles();

    //--- assign tasks ---------------------------------

    $hasError = $oAlignAll_use_LinesLines->assignTask($task);
    if ($hasError) {
        print ("!!! Error on function assignTask:" . $hasError . PHP_EOL);
    }

    //--- execute tasks ---------------------------------

    if (!$hasError) {
        $hasError = $oAlignAll_use_LinesLines->execute();
        if ($hasError) {
            print ("!!! Error on function execute:" . $hasError . PHP_EOL);
        }
    }

    print ($oAlignAll_use_LinesLines->text() . PHP_EOL);
}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);

