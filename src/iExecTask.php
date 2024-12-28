<?php
//namespace \Vendor\App\DatabaseAccess;
namespace ExecuteTasks;

require_once "./fileNamesList.php";
require_once "./task.php";

// use \DateTime;
// use DateTime;

use FileNamesList\fileNamesList;
use task\task;

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