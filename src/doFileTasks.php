<?php

namespace Finnern\BuildExtension\src;

use Exception;
use Finnern\BuildExtension\src\cleanUpLib\clean4GitCheckin;
use Finnern\BuildExtension\src\fileHeaderLib\alignAll_use_Lines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_actCopyrightYearLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_authorLines;
use Finnern\BuildExtension\src\fileHeaderLib\updateAll_fileHeaders;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_licenseLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_linkLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_packages;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_sinceCopyrightYearLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_subPackageLines;
use Finnern\BuildExtension\src\fileSinceLib\exchangeAll_sinceInFiles;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\fileHeaderLib\forceCreationDate;

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\tasks;

$HELP_MSG = <<<EOT
    >>>
    doFileTasks class

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class doFileTasks
================================================================================*/

class doFileTasks
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

                    //--- assign files to task -----------------------

                    case strtolower('fileNamesList'):
                    case strtolower('createFileNamesList'):
                        print ('Execute task: ' . $textTask->name . PHP_EOL);

                        $this->actTask = $this->createTask(new fileNamesList (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();

                        print ('createFilenamesList count: ' . count ($this->fileNamesList->fileNames) . PHP_EOL);

                        break;

                    //--- add more files to task -----------------------

                    case strtolower('add2filenameslist'):
                        print ('Execute task: ' . $textTask->name . PHP_EOL);
                        $filenamesList = new fileNamesList ();
                        $filenamesList->assignTask($textTask);
                        $filenamesList->execute();

                        if (empty($this->fileNamesList)) {
                            $this->fileNamesList = new fileNamesList ();
                        }

                        print ('add2FilenamesList count: ' . count ($filenamesList->fileNames) . PHP_EOL);

                        $this->fileNamesList->addFilenames($filenamesList->fileNames);
                        break;

                    case strtolower('clearfilenameslist'):
                        $this->fileNamesList = new fileNamesList();
                        break;

                    case strtolower('printfilenameslist'):
                        print ($this->fileNamesList->text_listFileNames());

                        // stop after print files to check the files
                        // exit (98);
                        break;


                    //=== real task definitions =================================

                    case strtolower('buildextension'):
                        $this->actTask = $this->createTask(new buildExtension (), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

//                    case strtolower('forceversionid'):
//                        $this->actTask = $this->createTask(new forceVersionId (), $textTask);
//                        // run task
//                        $hasError = $this->actTask->execute();
//                        break;

                    case strtolower('forcecreationdate'):
                        $this->actTask = $this->createTask(new forceCreationDate (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

//                    case strtolower('increaseversionid'):
//                        $this->actTask = $this->createTask(new increaseVersionId (), $textTask);
//                        // run task
//                        $hasError = $this->actTask->execute();
//                        break;

                    case strtolower('clean4gitcheckin'):
                        $this->actTask = $this->createTask(new clean4GitCheckin (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('clean4release'):
//                        ToDo: $this->actTask = $this->createTask (new clean4release (), $textTask);
//                        $this->actTask = $this->createTask(new increaseVersionId (), $textTask);
//                        // run task
//                        $hasError = $this->actTask->execute();
                        break;


                    //--- exchange header tasks --------------------------------------------------

                    case strtolower('exchangeAll_licenselines'):
                        $this->actTask = $this->createTask(new exchangeAll_licenseLines (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_actcopyrightyearlines'):
                        $this->actTask = $this->createTask(new exchangeAll_actCopyrightYearLines (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_authorlines'):
                        $this->actTask = $this->createTask(new exchangeAll_authorLines (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_linklines'):
                        $this->actTask = $this->createTask(new exchangeAll_linkLines (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_packages'):
                        $this->actTask = $this->createTask(new exchangeAll_packages (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_sincecopyrightyear'):
                        $this->actTask = $this->createTask(new exchangeAll_sinceCopyrightYearLines (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_subpackagelines'):
                        $this->actTask = $this->createTask(new exchangeAll_subPackageLines (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_headers'):
                        $this->actTask = $this->createTask(new buildExtension (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('updateAll_fileheaders'):
                        $this->actTask = $this->createTask(new updateAll_fileHeaders (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('alignAll_use_Lines'):
                        $this->actTask = $this->createTask(new alignAll_use_Lines (), $textTask);
                        // run task
                        $hasError = $this->actTask->execute();
                        break;

                    case strtolower('exchangeAll_sinceInFiles_RSG2'):
                        $this->actTask = $this->createTask(new exchangeAll_sinceInFiles (), $textTask);
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

        $OutTxt .= "--- doFileTasks: Tasks ---" . PHP_EOL;

        // $OutTxt .= "Tasks count: " . $this->textTasks->count() . PHP_EOL;

        $OutTxt .= $this->tasks->text() . PHP_EOL;

        return $OutTxt;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- doFileTasks ==> " . $this->actTaskName . " ---" . PHP_EOL;

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

    public function extractTasksFromFile(mixed $taskFile) : doFileTasks
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromFile($taskFile));

        return $this;
    }

} // doFileTasks

