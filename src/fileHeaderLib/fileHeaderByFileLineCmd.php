<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;

$HELP_MSG = <<<EOT
    >>>
    fileHeaderByFileLineCmd class

    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "f:t:o:h12345";
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
$srcfile = '';
// $srcfile = "./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php";

$taskLine = '';
$tasksLine = ' task:exchangeLicense'
    . ' /fileName="./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php"';
//$tasksLine = ' task:exchangeActCopyrightYear'
//    . ' /fileName="./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php"'
//    . ' /copyrightDate=1999'
//;

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
        case 'f':
            $srcfile = $option;
            break;

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

//--- create class object ---------------------------------

$task = new task();

//--- extract tasks from string or file ---------------------------------

if ( ! empty ($taskFile)) {
    $task = $task->extractTaskFromFile($taskFile);
} else {
    $task = $task->extractTaskFromString($tasksLine);
}

//--- extract options from file(s) ------------------

if ( ! empty($optionFiles) ) {
    foreach ($optionFiles as $optionFile) {
        $task->extractOptionsFromFile($optionFile);
    }
}

print ($task->text());

/*--------------------------------------------------
   execute task
--------------------------------------------------*/

if (empty ($hasError)) {

	$oFileHeader = new fileHeaderByFileLine();

	//--- assign tasks ---------------------------------

    $hasError = $oFileHeader->assignTask($task);
    if ($hasError) {
        print ("!!! Error on function assignTask:" . $hasError . PHP_EOL);
	}
 	
	//--- execute tasks ---------------------------------

    if (!$hasError) {
        $hasError = $oFileHeader->execute();
        if ($hasError) {
            print ("!!! Error on function execute:" . $hasError . PHP_EOL);
        }
    }
	
	// print ($oFileHeader->text() . PHP_EOL);
}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);

