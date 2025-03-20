<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;


$HELP_MSG = <<<EOT
    >>>
    class exchangeAll_licenses

    Reads file, exchanges one 'license' line
    Standard replace text is defined in class fileHeaderData
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:l:o:h12345";
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

$tasksLine = ' task:exchangeAllLicenses'
//    . ' /srcRoot="./../../RSGallery2_J4"'
    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
//    . ' /isNoRecursion=true'
    . ' /licenseText = "GNU General Public License version 2 or later"'//    . ' /s='
;

//$srcRoot = './../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop';
//$srcRoot = './../../RSGallery2_J4';
$srcRoot = '';

//$licenseText = "GNU General Public License version 2 or later;";
//$this->license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
$licenseText = '';

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

        case 'l':
            $licenseText = $option;
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

	$oExchangeAllLicenses = new exchangeAll_licenseLines($srcRoot, $licenseText);

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

	print ($oExchangeAllLicenses->text() . "\r\n");
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

