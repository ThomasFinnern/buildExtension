<?php

namespace Finnern\BuildExtension\src\fileNamesLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;

/**
 * ToDo:
 * folder name regex
 * filename regex
 * /**/

$HELP_MSG = <<<EOT
    >>>
    Call FileNameList class ...
    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "oe:i:p:w:nh12345";
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

// ToDo: use tasklines as command

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
$srcRoot = "./../../../LangMan4Dev";
//$srcRoot = "..\\..\\..\\LangMan4Dev";

$includeExt = "";
//$includeExt = "php xmp ini";
//$includeExt = "php";
//$includeExt = "xmp";
//$includeExt = "ini";
$includeExt = "ts";

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

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 'p':
            $srcRoot = $option;
            break;

        case 'i':
            $includeExt = $option;
            break;

        case 'e':
            $excludeExt = $option;
            break;

        case 'n':
            $isNoRecursion = true;
            break;

        case 'w':
            $writeListToFile = $option;
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
$start = commandLineLib::print_header($options, $inArgs);

// ToDo: assign task instead if exist

$oFileNamesList = new fileNamesList($srcRoot, $includeExt, $excludeExt, $isNoRecursion, $writeListToFile);

$hasError = $oFileNamesList->scan4Filenames();

if ($hasError) {
    print ("Error on function scan4Filenames:" . $hasError);
} else {
    print ("--- result -------------------" . "\r\n");
    print ($oFileNamesList->text() . "\r\n");
}


commandLineLib::commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

