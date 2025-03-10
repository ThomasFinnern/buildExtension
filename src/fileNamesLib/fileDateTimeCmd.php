<?php

namespace Finnern\BuildExtension\src\fileNamesLib;

require_once '../autoload/autoload.php';

use DateTime;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\fileNamesLib\fileDateTime;

$HELP_MSG = <<<EOT
    >>>
    class fileDateTime

    ToDo: option commands , example

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:d:h12345";
$isPrintArguments = false;

[$inArgs, $options] = commandLineLib::argsAndOptions($argv, $optDefinition, $isPrintArguments);

//$LeaveOut_01 = true;
//$LeaveOut_02 = true;
//$LeaveOut_03 = true;
//$LeaveOut_04 = true;
//$LeaveOut_05 = true;
//
///*--------------------------------------------
//variables
//--------------------------------------------*/
//
//$srcFile = "";
//$dstFile = "";
//
//foreach ($options as $idx => $option)
//{
//    print ("idx: " . $idx . "\r\n");
//    print ("option: " . $option . "\r\n");
//
//    switch ($idx)
//    {
//        case 's':
//            $srcFile = $option;
//            break;
//
//        case 'd':
//            $dstFile = $option;
//            break;
//
//        case "h":
//            exit($HELP_MSG);
//
//        case "1":
//            $LeaveOut_01 = true;
//            print("LeaveOut_01");
//            break;
//        case "2":
//            $LeaveOut_02 = true;
//            print("LeaveOut__02");
//            break;
//        case "3":
//            $LeaveOut_03 = true;
//            print("LeaveOut__03");
//            break;
//        case "4":
//            $LeaveOut_04 = true;
//            print("LeaveOut__04");
//            break;
//        case "5":
//            $LeaveOut_05 = true;
//            print("LeaveOut__05");
//            break;
//
//        default:
//            print("Option not supported '" . $option . "'");
//            break;
//    }
//
//}

/*--------------------------------------------------
   call function
--------------------------------------------------*/

// for start / end diff
$start = commandLineLib::print_header($options, $inArgs);

print ("Date file format: " . fileDateTime::stdFileDateTimeFormatString()) . "\n";
print ("Date expizit" . fileDateTime::DateTimeFormatString()) . "\n";
print ("Date file format (msec): " . fileDateTime::StdFileDateTimeFormatStringMsec()) . "\n";

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");
