<?php

namespace option;

require_once "./commandLine.php";
require_once "./option.php";

// use DateTime;

use function commandLine\argsAndOptions;
use function commandLine\print_end;
use function commandLine\print_header;

$HELP_MSG = <<<EOT
    >>>
    option class

    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "o:h12345";
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

//$optionLine = '/option1';
$optionLine = '/option2=02_Option';
//$optionLine = '/option3="01_Xteststring"';


foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 'o':
            $optionLine = $option;
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

$oOption = new option();

$oOptionResult = $oOption->extractOptionFromString($optionLine);

print ($oOption->text() . "\r\n");
print ("Line: '" . $oOptionResult->text4Line() . "'" . "\r\n");

print_end($start);

print ("--- end  ---" . "\n");

