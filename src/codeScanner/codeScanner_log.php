<?php

namespace Finnern\BuildExtension\src\codeScanner;

use Exception;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\tasks;

$HELP_MSG = <<<EOT
    >>>
    codeScanner_log class

    Calls other log tasks
    <<<
    EOT;


/*================================================================================
Class codeScanner_log
================================================================================*/

class codeScanner_log
{

    /**
     * @var tasks
     */
    public tasks $tasks;

    public executeTasksInterface $actTask;
    public string $actTaskName = 'no task defined';
    /**
     * @var fileNamesList
     */
    public fileNamesList $fileNamesList;

    //
    public string $basePath = "";


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($basePath = "", $tasksLine = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print("basePath: " . $basePath . PHP_EOL);
//            print("tasks: " . $tasksLine . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->basePath = $basePath;
            $this->tasks = new tasks();
            $this->fileNamesList = new fileNamesList();

            if (strlen($tasksLine) > 0) {
                $this->tasks = $this->tasks->extractTasksFromString($tasksLine);
            }
            // print ($this->tasksText ());
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    applyTasks
    --------------------------------------------------------------------*/

    public function extractTasksFromString(mixed $tasksLine): void
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromString($tasksLine));
    }

    public function assignTasks(tasks $tasks): tasks
    {
        $this->tasks = $tasks;

        return $tasks;
    }

    public function execute(): int
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('applyTasks' . PHP_EOL);
            // print ("task: " . $textTask . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            foreach ($this->tasks->tasks as $textTask) {
                // print ("--- apply task: " . $textTask->name . PHP_EOL);
                print (">>>---------------------------------" . PHP_EOL);

                $this->actTaskName = $textTask->name;

                switch (strtolower($textTask->name)) {
                    //--- let the task run -------------------------

                    case strtolower('execute'):
                        print ('>>> Call execute task: "'
                            // . $this->actTask->name
                            . '"  >>>' . PHP_EOL);

                        // ToDo: dummy task
//                        if (empty ($this->actTask)){
//                            $this->actTask = new executeTasksInterface ();
//                        }

                        // prepared filenames list
                        $this->actTask->assignFilesNames($this->fileNamesList);

                        // run task
                        $hasError = $this->actTask->execute();

//                        // stop after first task
//                        exit (99);

                        break;

                    //=== real task definitions =================================

                    case strtolower('codeScanner_logIdent'):
                        $this->actTask = $this->createTask(new codeScanner_logIndent (), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

                    case strtolower('codeScanner_logComment'):
                        $this->actTask = $this->createTask(new codeScanner_logComment (), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();
                        break;


                    default:
                        print ('!!! Execute unknown task: "' . $textTask->name . '" !!!' . PHP_EOL);
                        throw new Exception('!!! Execute unknown task: "' . $textTask->name . '" !!!');
                } // switch

                // $OutTxt .= $task->text() . PHP_EOL;
            }
        } catch (Exception $e) {
            echo '!!! applyTasks: Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit applyTasks: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    private function createTask(executeTasksInterface $execTask, task $textTask): executeTasksInterface
    {
        print ('Assign task: ' . $textTask->name . PHP_EOL);

        $execTask->assignTask($textTask);

        return $execTask;
    }

    public function tasksText(): string
    {
        // $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt = "";

        $OutTxt .= "--- codeScanner_log: Tasks ---" . PHP_EOL;

        // $OutTxt .= "Tasks count: " . $this->textTasks->count() . PHP_EOL;

        $OutTxt .= $this->tasks->text() . PHP_EOL;

        return $OutTxt;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- codeScanner_log ==> " . $this->actTaskName . " ---" . PHP_EOL;

        if ( !empty ($this->actTask)) {
            $OutTxt .= $this->actTask->text();
        } else {
            $OutTxt .= ">>> text(): object actTask is not defined" . PHP_EOL;
        }
        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
         * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
         * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;
         * /**/

        return $OutTxt;
    }

    public function extractTasksFromFile(mixed $taskFile) : codeScanner_log
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromFile($taskFile));

        return $this;
    }

} // class

