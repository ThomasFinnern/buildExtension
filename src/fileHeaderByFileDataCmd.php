<?php

namespace FileHeader;

require_once "./commandLine.php";
require_once "./task.php";
require_once "./fileHeaderByFileData.php";

// use DateTime;

use task\task;
use function commandLine\argsAndOptions;
use function commandLine\print_end;
use function commandLine\print_header;

$HELP_MSG = <<<EOT
    >>>
    fileHeaderByFile class

    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "f:t:h12345";
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
$srcfile = '';
// $srcfile = "./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php";

$taskLine = '';
// $tasksLine = ' task:replaceHeader'
$tasksLine = ' task:upgradeHeader'
    . ' /fileName="./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php"';
//$tasksLine = ' task:exchangeActCopyrightYear'
//    . ' /fileName="./../../RSGallery2_J4/administrator/components/com_rsgallery2/src/Model/GalleryTreeModel.php"'
//    . ' /copyrightDate=1999'
//
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
//;

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 'f':
            $srcfile = $option;
            break;

        case 't':
            $taskLine = $option;
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

//--- call function ---------------------------------

// for start / end diff
$start = print_header($options, $inArgs);


if (!empty ($tasksLine)) {
    $task = new task();
    $task->extractTaskFromString($tasksLine);

    $oFileHeader = new fileHeaderByFileData();

    $hasError = $oFileHeader->assignTask($task);
    if ($hasError) {
        print ("Error on function assignTask:" . $hasError);
    }
    if (!$hasError) {
        $hasError = $oFileHeader->execute();
        if ($hasError) {
            print ("Error on function execute:" . $hasError);
        }
    }
} else {
    if (!empty ($srcfile)) {
        $oFileHeader = new fileHeaderByFileData($srcfile);

        $oFileHeader->importFileData();

        print ($oFileHeader->text() . "\r\n");
        print ("header Lines: '" . $oFileHeader->headerText() . "'" . "\r\n");
    }
}


print_end($start);

print ("--- end  ---" . "\n");

