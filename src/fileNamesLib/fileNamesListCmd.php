<?php

namespace Finnern\BuildExtension\src\fileNamesLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\tasksLib\task;

/**
 * ToDo:
 * folder name regex
 * filename regex
 * /**/

$HELP_MSG = <<<EOT
    >>>
    calls FileNameList class ...
    
    Collects all files in srcRott folder and below
    Extensions can be included or excluded
    Folders can be excluded 
    
    A file list can be generated to check on files found 
    Recursion can be prevented
    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

//$optDefinition = "e:i:p:w:no:h12345";
$optDefinition = "f:t:s:y:o:h12345";
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

//$tasksLine = 'not defined ...';
//$tasksLine = "task:fileNamesList /callerProjectId=RSG2 /srcRoot='../../../LangMan4Dev' /includeExt='php' /isWriteListToFile=true /listFileName='../../testData/FoundFileNamesList.txt'";
//$tasksLine .= "task:createFilenamesList"
//    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
//    . ' /includeExt = "php"'
////    . ' /includeExt="xmp"'
////    . ' /includeExt="ini"'
////    . ' /includeExt="ts"'
////   . ' /includeFolder="./Administrator'
////   . ' /includeFolder="./Administrator'
//    . ' ';

//$srcRoot = "..\\..\\RSGallery2_J4";
//$srcRoot = "./../../RSGallery2_J4";
//$srcRoot = "./../../RSGallery2_J4/administrator";
//$srcRoot = "./../../RSGallery2_J4/component";
//$srcRoot = "./../../RSGallery2_J4/media";
//$srcRoot = "./../../../LangMan4Dev";
$srcRoot = "../../testData";

$includeExt = "";
//$includeExt = "php xmp ini";
//$includeExt = "php";
//$includeExt = "xmp";
//$includeExt = "ini";
$includeExt = "php";

//$excludeExt = "php xmp ini";
//$excludeExt = "php";
//$excludeExt = "xmp";
//$excludeExt = "ini";
$excludeExt = "";

// no recursion, actual folder only
$isNoRecursion = false;
//$isNoRecursion = True;

//$writeListToFile = "";
$writeListToFile = "./FileNamesList.txt";

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
//$optionFiles [] = '..\options_version_tsk\build_develop.opt';
//$optionFiles [] = '..\options_version_tsk\build_step.opt';
//$optionFiles [] = '..\options_version_tsk\build_fix.opt';
//$optionFiles [] = '..\options_version_tsk\build_release.opt';
//$optionFiles [] = '..\options_version_tsk\build_major.opt


print ('..: ' . realpath('../') . PHP_EOL);
print ('../..: ' . realpath('../..') . PHP_EOL);
print ('../tsk_file_examples: ' . realpath('../tsk_file_examples') . PHP_EOL);
print ('../../..: ' . realpath('../../..') . PHP_EOL);


//$taskFile = '../tsk_file_examples/fileNamesList.tsk';
$taskFile = '../tsk_file_examples/fileNamesList_direct.tsk';


foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
//        case 'p':
//            $srcRoot = $option;
//            break;
//
//        case 'i':
//            $includeExt = $option;
//            break;
//
//        case 'e':
//            $excludeExt = $option;
//            break;
//
//        case 'n':
//            $isNoRecursion = true;
//            break;
//
//        case 'w':
//            $writeListToFile = $option;
//            break;
//
//
//        case 'o':
//            $optionFiles[] = $option;
//            break;
//

        case 't':
            $tasksLine = $option;
            print('In taskLine: "' . $tasksLine . '"');
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

    $oFileNamesList = new fileNamesList();

    //--- assign tasks ---------------------------------

    $hasError = $oFileNamesList->assignTask($task);
    if ($hasError) {
        print ("!!! Error on function assignTask:" . $hasError . PHP_EOL);
    }

    //--- execute tasks ---------------------------------

    if (!$hasError) {
        $hasError = $oFileNamesList->execute(); // scan4Filenames();
        if ($hasError) {
            print ("!!! Error on function execute:" . $hasError . PHP_EOL);
        }

        print ($oFileNamesList->text() . PHP_EOL);
    }
}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);
