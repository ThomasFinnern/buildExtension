<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_sinceCopyrightYearLines;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;


$HELP_MSG = <<<EOT
    >>>
    class exchangeAll_sinceCopyrightYear

    Reads file, exchanges one 'copyright' line for 'since'' year part (first year in line)
    Standard replace text is ??? year

    ToDo: extract year from git log each ..
    ToDo: create twin file with new name appended so it is easy to decide to use it .. ...
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:y:h12345";
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

$tasksLine = ' task:exchangeAll_sinceCopyrightYear'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
    . ' /isNoRecursion=true'
    . ' /yearText="1960"'//
;

//$srcRoot = './../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop';
//$srcRoot = './../../RSGallery2_J4';
$srcRoot = '';

//$licenseText = "GNU General Public License version 2 or later;";
//$this->license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
$yearText = '';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 's':
            $srcRoot = $option;
            break;

        case 'y':
            $yearText = $option;
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

	$oExchangeAllLicenses = new exchangeAll_sinceCopyrightYearLines($srcRoot, $yearText);

	//--- assign tasks ---------------------------------

	$hasError = $oExchangeAllLicenses->assignTask($task);
	if ($hasError) {
		print ("Error on function assignTask:" . $hasError);
	}
	
	//--- execute tasks ---------------------------------

	if (!$hasError) {
		$hasError = $oExchangeAllLicenses->execute();
		if ($hasError) {
			print ("Error on function execute:" . $hasError);
		}
	}
}


print ($oExchangeAllLicenses->text() . "\r\n");

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

