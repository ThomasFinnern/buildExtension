<?php
//namespace \Vendor\App\DatabaseAccess;
namespace Finnern\BuildExtension\src\tasksLib;

use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
interface executeTasksInterface
================================================================================*/

interface executeTasksInterface
{
    // List of filenames to use
    public function assignFilesNames(fileNamesList $fileNamesList): int;

    // Task with options
    public function assignTask(task $task): int;

    public function execute(): int; // $hasError

    public function executeFile(string $filePathName): int;


}