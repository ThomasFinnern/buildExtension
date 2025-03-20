<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;


$HELP_MSG = <<<EOT
    >>>
    class exchangeAll_authorLines

    Reads file, exchanges one 'author' line
    Standard replace text is defined in class fileHeaderData
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:a:o:h12345";
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

//// idea: own task for filenames
//$filesTaskLine = "task:createFilenamesList"
//    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
////    . ' /isCrawlSilent=false' default true ToDo:
//    . ' /includeExt="php"'
////    . ' /includeExt="xmp"'
////    . ' /includeExt="xmp"'
////    . ' /includeExt="ini"'
////    . ' /includeFiles="???"'
////    . ' /excludeFiles="./../../RSGallery2_J4/.gitignore ./../../RSGallery2_J4/LICENSE.txt /../../RSGallery2_J4/README.md ./../../RSGallery2_J4/index.html "'
////   . ' /includeFolder="./Administrator'
////   . ' /includeFolder="./Administrator'
//    . ' ';


$tasksLine = ' task:exchangeAll_authorLines'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
    . ' /isNoRecursion=true'
//    . ' /srcRoot="./../../RSGallery2_J4"'
    . ' '
    . ' /authorText = ""'//    . ' /s='
;

//$srcRoot = './../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop';
//$srcRoot = './../../RSGallery2_J4';
$srcRoot = '';

//$authorText = "GNU General Public author version 2 or later;";
//$this->author = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
$authorText = '';
$isNoRecursion = false;

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
//$optionFiles [] = '..\options_version_tsk\build_develop.opt';
//$optionFiles [] = '..\options_version_tsk\build_step.opt';
//$optionFiles [] = '..\options_version_tsk\build_fix.opt';
//$optionFiles [] = '..\options_version_tsk\build_release.opt';
//$optionFiles [] = '..\options_version_tsk\build_major.opt

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 's':
            $srcRoot = $option;
            break;

        case 'a':
            $authorText = $option;
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

//--- extract tasks from string or file ------------------

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

	$oExchangeAll_authorLines = new exchangeAll_authorLines($srcRoot,
		$isNoRecursion, $authorText);

	//--- assign tasks ---------------------------------

	$hasError = $oExchangeAll_authorLines->assignTask($task);
	if ($hasError) {
		print ("Error on function assignTask:" . $hasError);
	}
	
	//--- execute tasks ---------------------------------

	if (!$hasError) {
		$hasError = $oExchangeAll_authorLines->execute();
		if ($hasError) {
			print ("Error on function execute:" . $hasError);
		}
	}
	
	print ($oExchangeAll_authorLines->text() . "\r\n");
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

