<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;


$HELP_MSG = <<<EOT
    >>>
    class forceVersionId

    ToDo: option commands , example

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:d:o:h12345";
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

//$tasksLine = ' task:forceVersionId'
//    . ' /srcRoot="../../../RSGallery2_J4"'
////    . ' /isNoRecursion=true'
//    . ' /name=rsgallery2'
////    . ' /extension=RSGallery2'
//    . ' /version="5.0.12.4"';

$tasksLine = ' task:forceVersionId'
    . ' /srcRoot="../../../LangMan4Dev"'
//    . ' /isNoRecursion=true'
//    . ' /name=rsgallery2'
    . ' /name=lang4dev'
//    . ' /extension=RSGallery2'
    . ' /version="0.29.0.0"';

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
        case 's':
            $srcFile = $option;
            break;

        case 'd':
            $dstFile = $option;
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

	$oForceVersionId = new forceVersionId();

	//--- assign tasks ---------------------------------

	$hasError = $oForceVersionId->assignTask($task);
	if ($hasError) {
		print ("!!! Error on function assignTask:" . $hasError . PHP_EOL);
	}
	
	//--- execute tasks ---------------------------------

	if (!$hasError) {
		$hasError = $oForceVersionId->execute();
		if ($hasError) {
			print ("!!! Error on function execute:" . $hasError . PHP_EOL);
		}
	}
	
	print ($oForceVersionId->text() . PHP_EOL);
}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);

