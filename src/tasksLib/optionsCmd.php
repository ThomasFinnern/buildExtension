<?php

namespace Finnern\BuildExtension\src\tasksLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;
use Finnern\BuildExtension\src\tasksLib\option;

$HELP_MSG = <<<EOT
    >>>
    options class

    <<<
    EOT;

/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "o:h12345";
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

$optionsLine = '/option1 /option2=01_Option /option3="02 test space string"';
//$optionsLine = '/option3B="02 test space string"';
//$optionsLine = ' /option3="02 test space string" /option4="" /option5="05 OP " /option6="06_Xteststring"';
//$optionsLine = '/option4="" /option5="05 OP " /option6="06_Xteststring" ';
//$optionsLine = '/option1 ';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
        case 'o':
            $optionsLine = $option;
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

$oOptions = new options();

$oOptionsResult = $oOptions->extractOptionsFromString($optionsLine);

print ($oOptions->text() . PHP_EOL);
print ("Resulting line: '" . $oOptionsResult . "'" . PHP_EOL);

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);

