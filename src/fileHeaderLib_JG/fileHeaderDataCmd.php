<?php

namespace Finnern\BuildExtension\src\fileHeaderLib_JG;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\fileHeaderLib_JG\fileHeaderData;

$HELP_MSG = <<<EOT
    >>>
    fileHeader class

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "f:o:h12345";

/*--------------------------------------------------
   call task
--------------------------------------------------*/
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

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
//$optionFiles [] = '..\options_version_tsk\build_develop.opt';
//$optionFiles [] = '..\options_version_tsk\build_step.opt';
//$optionFiles [] = '..\options_version_tsk\build_fix.opt';
//$optionFiles [] = '..\options_version_tsk\build_release.opt';
//$optionFiles [] = '..\options_version_tsk\build_major.opt

// $fileName="";
$fileName="d:/Entwickl/2025/_gitHub/JoomGallery_fith_feature_cli-start/administrator/com_joomgallery/src/CliCommand/ConfigList.php";




foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {

        case 'f':
            $fileName = $option;
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

$oFileHeader = new fileHeaderData($fileName);

print ($oFileHeader->text() . "\r\n");
print ("Line: '" . $oFileHeader->headerText() . "'" . "\r\n");

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

