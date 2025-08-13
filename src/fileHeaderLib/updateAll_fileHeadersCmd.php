<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    class updateAll_fileHeaders

    Reads file, exchanges one 'author' line
    Standard replace text is defined in class fileHeaderData
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:f:o:h12345";
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

$tasksLine = ' task:updateAll_fileHeaders'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /srcRoot="./../../RSGallery2_J4/administrator/components/com_rsgallery2/tmpl/develop"'
//    . ' /isNoRecursion=true'
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
//    . ' /fileName="./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php"'
//    . ' /copyrightDate=1999'
//    . ' /package        = "RSGallery2";
//    . ' /subpackage     = "com_rsgallery2";
//    . ' /actCopyright      = "2024";
//    . ' /sinceCopyright      = "2016";
//    . ' /copyrightToday = $copyrightDate . "-" . $copyrightDate . " RSGallery2 Team";
//    . ' /license        = "GNU General Public License version 2 or later";
//        //$this->license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
//    . ' /author = "RSGallery2 Team <team2@rsgallery2.org>";
//    . ' /link   = "https://www.rsgallery2.org";
//
//    . ' /isForceStdPackage        = "RSGallery2";
//    . ' /isForceStdSubpackage     = "com_rsgallery2";
//    . ' /isForceStdActCopyright      = "2024";
//    . ' /isForceStdSinceCopyright      = "2016";
//    . ' /isForceSinceCopyrightToToday = $copyrightDate . "-" . $copyrightDate . " RSGallery2 Team";
//    . ' /isForceStdLicense        = "GNU General Public License version 2 or later";
//    . ' /isForceStdAuthor = "RSGallery2 Team <team2@rsgallery2.org>";
//    . ' /isForceStdLink   = "https://www.rsgallery2.org";
//
//    . ' /isForcePackage        = "RSGallery2";
//    . ' /isForceSubpackage     = "com_rsgallery2";
//    . ' /isForceActCopyright      = "2024";
//    . ' /isForceSinceCopyright      = "2016";
//    . ' /isForceActCopyrightToToday = $copyrightDate . "-" . $copyrightDate . " RSGallery2 Team";
//    . ' /isForcelicense        = "GNU General Public License version 2 or later";
//    . ' /isForceAuthor = "RSGallery2 Team <team2@rsgallery2.org>";
//    . ' /isForcelink   = "https://www.rsgallery2.org";
//
//    . ' /isKeepStdPackage        = "RSGallery2";
//    . ' /isKeepStdSubpackage     = "com_rsgallery2";
//    . ' /isKeepStdActCopyright      = "2024";
//    . ' /isKeepStdSinceCopyright      = "2016";
//    . ' /isKeepStdcopyrightToday = $copyrightDate . "-" . $copyrightDate . " RSGallery2 Team";
//    . ' /isKeepStdlicense        = "GNU General Public License version 2 or later";
//    . ' /isKeepStdAuthor = "RSGallery2 Team <team2@rsgallery2.org>";
//    . ' /isKeepStdlink   = "https://www.rsgallery2.org";
//
//    . ' ';

;

// $taskFile = "";
$taskFile="./updateAll_fileHeaders.tsk";
$tasksLine = "";

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

	$oUpdateAll_fileHeaders = new updateAll_fileHeaders();

	//--- assign tasks ---------------------------------

	$hasError = $oUpdateAll_fileHeaders->assignTask($task);
	if ($hasError) {
		print ("!!! Error on function assignTask:" . $hasError . PHP_EOL);
	}
	
	//--- execute tasks ---------------------------------

	if (!$hasError) {
		$hasError = $oUpdateAll_fileHeaders->execute();
		if ($hasError) {
			print ("!!! Error on function execute:" . $hasError . PHP_EOL);
		}
	}

	print ($oUpdateAll_fileHeaders->text() . PHP_EOL);
}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);

