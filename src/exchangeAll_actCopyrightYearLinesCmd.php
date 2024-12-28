<?php

namespace exchangeAll_actCopyrightYear;

require_once "./commandLine.php";
require_once "./exchangeAll_actCopyrightYearLines.php";

// use \DateTime;

use task\task;
use function commandLine\argsAndOptions;
use function commandLine\print_end;
use function commandLine\print_header;


$HELP_MSG = <<<EOT
    >>>
    class exchangeAll_actCopyrightYear

    Reads file, exchanges one 'copyright' line for actual year part (second year in line)
    Standard replace text is actual year
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "f:t:s:y:h12345";
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

$tasksLine = ' task:exchangeAll_actCopyrightYear'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
    . ' /isNoRecursion=true'
    . ' /yearText="1984"'//    . ' /s='
;
$tasksLine="";

//$srcRoot = './../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop';
//$srcRoot = './../../RSGallery2_J4';
$srcRoot = '';
$isNoRecursion = true;

//$licenseText = "GNU General Public License version 2 or later;";
//$this->license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
$yearText = '';

//$taskFile="./exchangeAll_actCopyrightYearLines.tsk";
//$taskFile="./build_Develop.tsk";
//$taskFile="./build_release.tsk";
$taskFile = "";
$taskFile = '../../J_LangMan4ExtDevProject/.buildPHP/exchangeAll_actCopyrightYearLines.tsk';

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

        case 's':
            $srcRoot = $option;
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
$start = print_header($options, $inArgs);

//--- assign task line ------------------------------

$task = new task();
if ($taskFile != "") {
    $task->extractTaskFromFile($taskFile);
//    if (!empty ($hasError)) {
//        print ("Error on function extractTasksFromFile:" . $hasError
//            . ' path: ' . $taskFile);
//    }
} else {
    $task->extractTaskFromString($tasksLine);
}

//--- execute class tasks ------------------------------

// $oExchangeAllActCopyright = new exchangeAll_actCopyrightYearLines($srcRoot, $isNoRecursion, $yearText);
$oExchangeAllActCopyright = new exchangeAll_actCopyrightYearLines();

$hasError = $oExchangeAllActCopyright->assignTask($task);
if ($hasError) {
    print ("Error on function assignTask:" . $hasError);
}
if (!$hasError) {
    $hasError = $oExchangeAllActCopyright->execute();
    if ($hasError) {
        print ("Error on function execute:" . $hasError);
    }
}

print ($oExchangeAllActCopyright->text() . "\r\n");

print_end($start);

print ("--- end  ---" . "\n");

