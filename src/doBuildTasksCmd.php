<?php

namespace Finnern\BuildExtension\src;

require_once 'autoload/autoload.php';

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\tasks;
use Finnern\BuildExtension\src\tasksLib\commandLineLib;

$HELP_MSG = <<<EOT
    >>>
    doBuildTasks class

    ToDo: option commands , example

    <<<
    EOT;

// ToDo: make Task:execute implizit $collectedTasks->extractTasksFromString('task:execute'); Where should task auto executet ?
/*================================================================================
main (used from command line)
================================================================================*/

$optDefinition = "t:p:h12345";
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

$collectedTasks = new tasks;

//$tasksLine = "task:task00" . "\r\n"
//    . 'task:task01 /option1 /option2=xxx /option3="01teststring"' . "\r\n"
//    . 'task:task02 /optionX /option2=Y /optionZ="Zteststring"' . "\r\n"
//;
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');

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
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');

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

// $collectedTasks->extractTasksFromString($tasksLine);
// $collectedTasks->extractTasksFromString('task:execute');

////--- RSG2 module files ---------------------------------
//
//$tasksLine .= "task:createFilenamesList"
//    . ' /srcRoot="./../../RSGallery2_J4/module"'
//    . ' /isNoRecursion=true'
//;
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//
////--- RSG2 plugin files ---------------------------------
//
//$tasksLine .= "task:createFilenamesList"
//    . ' /srcRoot="./../../RSGallery2_J4/plugins"'
//    . ' /isNoRecursion=true'
//;
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//

//$tasksLine .= ' task:exchangeall_licenselines'
//    . ' /licenseText = "GNU General Public License version 2 or later"'
////    . ' /s='
//    . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//

$tasksLine = ' task:clean4GitCheckin'
//    . ' /srcRoot="./../../RSGallery2_J4"'
//    . ' /isNoRecursion=true'
//    . ' /s='
    . ' ';
// $collectedTasks->extractTasksFromString($tasksLine);
// $collectedTasks->extractTasksFromString('task:execute');


//$tasksLine .= ' task:exchangeAll_licenseLines'
//    . ' /licenseText = "GNU General Public License version 2 or later"'
////    . ' /s='
//    . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_actCopyrightYearLines'
////    . ' /s='
//    . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_linkLines'
////    . ' /s='
//    . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_packages'
////    . ' /s='
//    . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//
//$tasksLine .= ' task:exchangeAll_subPackageLines'
////    . ' /s='
//    . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');

$tasksLine = ' task:updateAll_fileHeaders'
    . ' ';
$collectedTasks->extractTasksFromString($tasksLine);
$collectedTasks->extractTasksFromString('task:execute');

//$tasksLine .= "task: "
////    . ' /s='
//   . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');
//$tasksLine .= "task: ";
////    . ' /s='
//   . ' ';
//$collectedTasks->extractTasksFromString($tasksLine);
//$collectedTasks->extractTasksFromString('task:execute');

$tasksLine = ' task:increaseVersionId'
//    . ' /type=component'
    . ' /srcRoot="./../../RSGallery2_J4/"'
    //    . ' /isNoRecursion=true'
    . ' /name=rsgallery2'
    . ' /version=major|minor|patch|dev'
    . ' /s='
    . ' ';
$collectedTasks->extractTasksFromString($tasksLine);
$collectedTasks->extractTasksFromString('task:execute');


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
$collectedTasks->extractTasksFromString($tasksLine);
$collectedTasks->extractTasksFromString('task:execute');


//========================================================
// test external directories tasks

// clean $collectedTasks
$collectedTasks = new tasks;


$basePath = "..\\..\\LangMan4Dev";

//--- build_develop.tsk --------------------------------
// $tasksFile = "";
// $tasksFile="./tasksFile.cmd";
// $tasksFile="../../LangMan4DevProject/.buildPHP/build_develop.tsk";

//--- build_develop.tsk --------------------------------
// $tasksFile = "";
// $tasksFile="./tasksFile.cmd";
// $tasksFile="../../LangMan4DevProject/.buildPHP/updateAll_fileHeaders.tsk";
//$tasksFile="../../LangMan4DevProject/.buildPHP/build_develop.tsk";
$tasksFile="../../LangMan4DevProject/.buildPHP/build_develop_plg_webservices.tsk";

//$optionFile = '';
//$optionFile = 'xTestOptionFile.opt';
//$optionFiles [] = '.\options_version_tsk\build_develop.opt';
//$optionFiles [] = './options_version_tsk/build_step.opt';
//$optionFiles [] = '.\options_version_tsk\build_fix.opt';
//$optionFiles [] = '.\options_version_tsk\build_release.opt';
//$optionFiles [] = '.\options_version_tsk\build_major.opt

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
            $tasksFile = $option;
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

$start = commandLineLib::print_header($options, $inArgs);

/*----------------------------------------------------------
   collect task
----------------------------------------------------------*/

//--- create class object ---------------------------------

$tasks = new tasks();

if ($tasksFile != "") {
    $hasError = $tasks->extractTasksFromFile($tasksFile);
    if (!empty ($hasError)) {
        print ("Error on function extractTasksFromFile:" . $hasError
            . ' path: ' . $basePath);
    }

} else {
    if ($collectedTasks->count() > 0) {
        $testTasks = $tasks->assignTasks($collectedTasks);
    } else {
        $testTasks = $tasks->extractTasksFromString($tasksLine);
        //if (!empty ($hasError)) {
        //    print ("Error on function extractTasksFromString:" . $hasError
        //        . ' path: ' . $basePath);
        //}
    }
}

print ($tasks->tasksText());

// //--- extract tasks from string or file ---------------------------------

// if ($tasksFile != "") {
    // $hasError = $oDoBuildTasks->extractTasksFromFile($tasksFile);
    // if (!empty ($hasError)) {
        // print ("Error on function extractTasksFromFile:" . $hasError
            // . ' path: ' . $basePath);
    // }

// } else {
    // if ($collectedTasks->count() > 0) {
        // $testTasks = $oDoBuildTasks->assignTasks($collectedTasks);
    // } else {
        // $testTasks = $oDoBuildTasks->extractTasksFromString($tasksLine);
        // //if (!empty ($hasError)) {
        // //    print ("Error on function extractTasksFromString:" . $hasError
        // //        . ' path: ' . $basePath);
        // //}
    // }
// }

// print ($oDoBuildTasks->tasksText());

/*--------------------------------------------------
   execute tasks
--------------------------------------------------*/

if (empty ($hasError)) {

	// for start / end diff
	$oDoBuildTasks = new doBuildTasks(); // $basePath, $tasksLine

	//--- assign tasks ---------------------------------

    $oDoBuildTasks->assignTasks($tasks);
	
	//--- execute tasks ---------------------------------

    // create task classes, when task execute is issued the task does execute
    $hasError = $oDoBuildTasks->execute();

    if ($hasError) {
        print ("Error on function collectFiles:" . $hasError
            . ' path: ' . $basePath);
    }

    print ($oDoBuildTasks->text() . "\r\n");
}

commandLineLib::print_end($start);

print ("--- end  ---" . "\n");

