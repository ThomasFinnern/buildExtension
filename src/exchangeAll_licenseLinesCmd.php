<?php

namespace exchangeAll_licenseLines;

require_once "./commandLine.php";
require_once "./exchangeAll_licenseLines.php";

// use \DateTime;

use task\task;
use function commandLine\argsAndOptions;
use function commandLine\print_end;
use function commandLine\print_header;


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

$optDefinition = "s:l:h12345";
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

$task = new task();
$task->extractTaskFromString($tasksLine);

$oExchangeAllLicenses = new exchangeAll_licenseLines($srcRoot, $licenseText);

$hasError = $oExchangeAllLicenses->assignTask($task);
if ($hasError) {
    print ("Error on function assignTask:" . $hasError);
}
if (!$hasError) {
    $hasError = $oExchangeAllLicenses->execute();
    if ($hasError) {
        print ("Error on function execute:" . $hasError);
    }
}

print ($oExchangeAllLicenses->text() . "\r\n");

print_end($start);

print ("--- end  ---" . "\n");

