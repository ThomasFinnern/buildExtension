<?php

namespace DoBuildTasks;

require_once "./commandLine.php";
require_once "./doBuildTasks.php";

use tasks\tasks;
use function commandLine\argsAndOptions;
use function commandLine\print_end;
use function commandLine\print_header;

$HELP_MSG = <<<EOT
    >>>
    doBuildTasks class

    ToDo: option commands , example

    <<<
    EOT;

// ToDo: make Task:execute implizit $collectedTasks->addTasksFromString('task:execute'); Where should task auto executet ?
/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:p:h12345";
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

$collectedTasks = new tasks;

//$tasksLine = "task:task00" . "\r\n"
//    . 'task:task01 /option1 /option2=xxx /option3="01teststring"' . "\r\n"
//    . 'task:task02 /optionX /option2=Y /optionZ="Zteststring"' . "\r\n"
//;
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');

//$tasksLine = "task:task00"
//    . 'task:task01 /option1 /option2=xxx /option3="01_Xteststring"'
//    . 'task:task02 /optionX /option2=Y /optionZ="02_Zteststring"'
//;
//$tasksLine = "task:clean4git";
//$tasksLine = "task:clean4release";

// build without properties: component path to rsgallery2_j4
// build without changes, increase id, prepare for release
// build type: component module plugin package
// build folder:
// build dev update version:
// Version ID  /increaseDevelop: x.x.x.n, release x.n.00, versionByConfig
//
//$tasksLine = "task:build /type=component";
//$tasksLine = "task:build /increaseId";
//$tasksLine = "task:build /increaseId /clean4release";

$tasksLine = '';

//$tasksLine .= ' task:buildExtension'
//    . ' /type=component'
//    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
//    . ' /buildDir="./../.packages"'
////    . ' /adminPath='
////    . ' /sitePath='
////    . ' /mediaPath='
//    . ' /name=rsgallery2'
//    . ' /extension=RSGallery2'
//// name.xml ?    . '/manifestFile='
////    . '/s='
////    . '/s='
////    . '/s='
//    . ' ';
//
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');

//$tasksLine .= ' task:forceVersionId'
//    . ' /id="9.9.9"'
//    . ' /srcRoot="./../../RSGallery2_J4/'
//    . ' /isNoRecursion=true'
//    . ' /type=component'
//    . ' /name=rsgallery2'
//;
//
//$tasksLine .= ' task:forceVersionId'
//    . ' /idFile="./VersionId.txt"'
//    . ' /srcRoot="./../../RSGallery2_J4/'
//    . ' /isNoRecursion=true'
//    . ' /type=component'
//    . ' /name=rsgallery2'
//;
//
//$tasksLine .= ' task:clean4release'
//    . ' /type=component'
//    . ' /name=rsgallery2'
//;
//
//$tasksLine .= ' task:clean4git'
//    . ' /type=component'
//    . ' /name=rsgallery2'
//    . ' /srcRoot="./../../RSGallery2_J4"'
//;
//
//
//--- RSG2 standard files ---------------------------------

$tasksLine .= "task:createFilenamesList"
    . ' /srcRoot="./../../RSGallery2_J4"'
    . ' /isNoRecursion=true'
//    . ' /isCrawlSilent=false' default true ToDo:
    . ' /includeExt="php"'
//    . ' /includeExt="xmp"'
//    . ' /includeExt="xmp"'
//    . ' /includeExt="ini"'
//    . ' /includeFiles="???"'
//    . ' /excludeFiles="./../../RSGallery2_J4/.gitignore ./../../RSGallery2_J4/LICENSE.txt /../../RSGallery2_J4/README.md ./../../RSGallery2_J4/index.html "'
//   . ' /includeFolder="./Administrator'
//   . ' /includeFolder="./Administrator'
    . ' ';
$tasksLine .= "task:add2FilenamesList"
    . ' /srcRoot="./../../RSGallery2_J4/administrator"'
//    . ' /isNoRecursion=true'
    . ' /includeExt="php"'
//    . ' /includeExt="xmp"'
//    . ' /includeExt="ini"'
//    . ' /includeExt="ts"'
//   . ' /includeFolder="./Administrator'
//   . ' /includeFolder="./Administrator'
    . ' ';
$tasksLine .= "task:add2FilenamesList"
    . ' /srcRoot="./../../RSGallery2_J4/components"'
    //    . ' /isNoRecursion=true'
    . ' /includeExt="php"'
//    . ' /includeExt="xmp"'
//    . ' /includeExt="ini"'
//    . ' /includeExt="ts"'
//   . ' /includeFolder="./Administrator'
//   . ' /includeFolder="./Administrator'
    . ' ';
$tasksLine .= "task:add2FilenamesList"
    . ' /srcRoot="./../../RSGallery2_J4/media"'
//    . ' /isNoRecursion=true'
//    . ' /includeExt="php"'
//    . ' /includeExt="xmp"'
//    . ' /includeExt="ini"'
    . ' /includeExt="ts"'
//   . ' /includeFolder="./Administrator'
//   . ' /includeFolder="./Administrator'
    . ' ';

//$tasksLine .= "task:printFilenamesList"
//    . ' ';

// $collectedTasks->addTasksFromString($tasksLine);
// $collectedTasks->addTasksFromString('task:execute');

////--- RSG2 module files ---------------------------------
//
//$tasksLine .= "task:createFilenamesList"
//    . ' /srcRoot="./../../RSGallery2_J4/module"'
//    . ' /isNoRecursion=true'
//;
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//
////--- RSG2 plugin files ---------------------------------
//
//$tasksLine .= "task:createFilenamesList"
//    . ' /srcRoot="./../../RSGallery2_J4/plugins"'
//    . ' /isNoRecursion=true'
//;
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//

//$tasksLine .= ' task:exchangeall_licenselines'
//    . ' /licenseText = "GNU General Public License version 2 or later"'
////    . ' /s='
//    . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//

$tasksLine = ' task:clean4GitCheckin'
//    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
//    . ' /s='
    . ' ';
// $collectedTasks->addTasksFromString($tasksLine);
// $collectedTasks->addTasksFromString('task:execute');


//$tasksLine .= ' task:exchangeAll_licenseLines'
//    . ' /licenseText = "GNU General Public License version 2 or later"'
////    . ' /s='
//    . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_actCopyrightYearLines'
////    . ' /s='
//    . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_linkLines'
////    . ' /s='
//    . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_packages'
////    . ' /s='
//    . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_subPackageLines'
////    . ' /s='
//    . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');

$tasksLine = ' task:updateAll_fileHeaders'
    . ' ';
$collectedTasks->addTasksFromString($tasksLine);
$collectedTasks->addTasksFromString('task:execute');

//$tasksLine .= "task: "
////    . ' /s='
//   . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->addTasksFromString($tasksLine);
//$collectedTasks->addTasksFromString('task:execute');

$tasksLine = ' task:increaseVersionId'
//    . ' /type=component'
    . ' /srcRoot="./../../RSGallery2_J4/"'
    //    . ' /isNoRecursion=true'
    . ' /name=rsgallery2'
    . ' /version=major|minor|patch|dev'
    . ' /s='
    . ' ';
$collectedTasks->addTasksFromString($tasksLine);
$collectedTasks->addTasksFromString('task:execute');


$tasksLine = 'task:clearFilenamesList' . ' ';

$tasksLine .= ' task:buildExtension'
    . ' /type=component'
    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
    . ' /buildDir="./../.packages"'
//    . ' /adminPath='
//    . ' /sitePath='
//    . ' /mediaPath='
    . ' /name=rsgallery2'
    . ' /extension=RSGallery2'
// name.xml ?    . '/manifestFile='
//    . '/s='
//    . '/s='
//    . '/s='
    . ' ';
$collectedTasks->addTasksFromString($tasksLine);
$collectedTasks->addTasksFromString('task:execute');


//========================================================
// test external directories tasks

// clean $collectedTasks
$collectedTasks = new tasks;


$basePath = "..\\..\\LangMan4Dev";

//--- build_develop.tsk --------------------------------
// $taskFile = "";
// $taskFile="./taskFile.cmd";
// $taskFile="../../LangMan4DevProject/.buildPHP/build_develop.tsk";

//--- build_develop.tsk --------------------------------
// $taskFile = "";
// $taskFile="./taskFile.cmd";
// $taskFile="../../LangMan4DevProject/.buildPHP/updateAll_fileHeaders.tsk";
//$taskFile="../../LangMan4DevProject/.buildPHP/build_develop.tsk";
$taskFile="../../LangMan4DevProject/.buildPHP/build_develop_plg_webservices.tsk";

foreach ($options as $idx => $option) {
    print ("idx: " . $idx . "\r\n");
    print ("option: " . $option . "\r\n");

    switch ($idx) {
        case 's':
            $tasks = $option;
            break;

        case 't':
            $tasksLine = $option;
            break;

        case 'f':
            $taskFile = $option;
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

//--- create class object ---------------------------------

// for start / end diff
$start = print_header($options, $inArgs);

$oDoBuildTasks = new doBuildTasks(); // $basePath, $tasksLine

//--- extract tasks from string or file ---------------------------------

if ($taskFile != "") {
    $hasError = $oDoBuildTasks->extractTasksFromFile($taskFile);
    if (!empty ($hasError)) {
        print ("Error on function extractTasksFromFile:" . $hasError
            . ' path: ' . $basePath);
    }

} else {
    if ($collectedTasks->count() > 0) {
        $oDoBuildTasks->assignTasks($collectedTasks);
    } else {
        $hasError = $oDoBuildTasks->extractTasksFromString($tasksLine);
        if (!empty ($hasError)) {
            print ("Error on function extractTasksFromString:" . $hasError
                . ' path: ' . $basePath);
        }
    }
}

print ($oDoBuildTasks->tasksText());

//--- execute tasks ---------------------------------

if (empty ($hasError)) {


    // create task classes, when task execute is issued the task does execute
    $hasError = $oDoBuildTasks->applyTasks();

    if ($hasError) {
        print ("Error on function collectFiles:" . $hasError
            . ' path: ' . $basePath);
    }
}

if (empty ($hasError)) {
    print ($oDoBuildTasks->text() . "\r\n");
}


print_end($start);

print ("--- end  ---" . "\n");

