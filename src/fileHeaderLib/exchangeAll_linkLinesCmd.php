<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;


$HELP_MSG = <<<EOT
    >>>
    class exchangeAllLinkLines

    Reads file, exchanges one 'links' line
    Standard replace text is defined in class fileHeaderData
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:d:h12345";
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

$tasksLine = ' task:exchangeAllLinks'
//    . ' /srcRoot="./../../RSGallery2_J4"'
    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
    //    . ' /isNoRecursion=true'
    . ' /linkText = "GNU General Public link version 2 or later"'//    . ' /s='
;

//$srcRoot = './../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop';
//$srcRoot = './../../RSGallery2_J4';
$srcRoot = '';
$isNoRecursion = false;

//$linkText = "GNU General Public link version 2 or later;";
//$this->link = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
$linkText = '';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
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
$start = commandLineLib::print_header($options, $inArgs);

//--- create class object ---------------------------------

$task = new task();

//--- extract tasks from string or file ---------------------------------

if ( ! empty ($taskFile)) {
    $testTask = $task->extractTaskFromFile($taskFile);
    //if (empty ($task->name)) {
    //    print ("Error on function extractTaskFromFile:" // . $hasError
    //        . ' Task file: ' . $taskFile);
    //    $hasError = -301;
    //}
} else {
    $testTask = $task->extractTaskFromString($tasksLine);
    //if (empty ($task->name)) {
    //    print ("Error on function extractTaskFromString:" . $hasError
    //        . ' tasksLine: ' . $tasksLine);
    //    $hasError = -302;
    //}
}

print ($task->text());

if (empty ($hasError)) {

	$oExchangeAlllinks = new exchangeAll_linkLines($srcRoot, $isNoRecursion, $linkText);

	//--- assign tasks ---------------------------------

	$hasError = $oExchangeAlllinks->assignTask($task);
	if ($hasError) {
		print ("Error on function assignTask:" . $hasError);
	}
	
	//--- execute tasks ---------------------------------

	if (!$hasError) {
		$hasError = $oExchangeAlllinks->execute();
		if ($hasError) {
			print ("Error on function execute:" . $hasError);
		}
	}
}

print ($oExchangeAlllinks->text() . "\r\n");

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

