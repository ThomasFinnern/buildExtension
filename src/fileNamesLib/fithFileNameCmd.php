<?php

namespace Finnern\BuildExtension\src\fileNamesLib;

require_once '../autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\commandLineLib;

$HELP_MSG = <<<EOT
    >>>
    fithFileName class ...
    <<<
    EOT;


/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "s:d:o:h12345";
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

$srcFile = "../original.php";

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
$optionFiles [] = 'xTestOptionFile.opt';

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . PHP_EOL);
    print ("option: " . $option . PHP_EOL);

    switch ($idx) {
        case 's':
            $srcFile = $option;
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

$oFileName = new fithFileName($srcFile);
// $hasError = $oFileName->extractNameParts();
print ($oFileName->text() . PHP_EOL);

if ($oFileName->hasExtension('php')) {
    print("yes hasExtension('php')" . PHP_EOL);
}

if ($oFileName->nameMatchesRegEx("/i.*i/")) {
    print("yes nameMatchesRegEx('/i.*i/')" . PHP_EOL);
}

commandLineLib::print_end($start);

print ("--- end  ---" . PHP_EOL);

