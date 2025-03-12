<?php

namespace Finnern\BuildExtension\src;

use Exception;
use clean4GitCheckin;

use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_actCopyrightYearLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_authorLines;
use Finnern\BuildExtension\src\fileHeaderLib\updateAll_fileHeaders;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_licenseLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_linkLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_packages;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_sinceCopyrightYearLines;
use Finnern\BuildExtension\src\fileHeaderLib\exchangeAll_subPackageLines;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\fileHeaderLib\forceCreationDate;
//use Finnern\BuildExtension\src\fileHeaderLib\forceVersionId;
//use Finnern\BuildExtension\src\fileHeaderLib\increaseVersionId;

use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\tasks;


//use Finnern\BuildExtension\src\tasksLib\option;
//use Finnern\BuildExtension\src\tasksLib\options;
//use Finnern\BuildExtension\src\tasksLib\task;

$HELP_MSG = <<<EOT
    >>>
    doBuildTasks class

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class doBuildTasks
================================================================================*/

class doBuildTasks
{

    /**
     * @var tasks
     */
    public tasks $tasks;

    public executeTasksInterface $actTask;
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
//            print('*********************************************************' . "\r\n");
//            print("basePath: " . $basePath . "\r\n");
//            print("tasks: " . $tasksLine . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            $this->basePath = $basePath;
            $this->tasks = new tasks();
            $this->fileNamesList = new fileNamesList();

            if (strlen($tasksLine) > 0) {
                $this->tasks = $this->tasks->extractTasksFromString($tasksLine);
            }
            // print ($this->tasksText ());
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    /*--------------------------------------------------------------------
    applyTasks
    --------------------------------------------------------------------*/

    public function extractTasksFromString(mixed $tasksLine)
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromString($tasksLine));
    }

    public function assignTasks(tasks $tasks)
    {
        $this->tasks = $tasks;

        return $tasks;
    }

    public function applyTasks(): int
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('applyTasks' . "\r\n");
            // print ("task: " . $textTask . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            foreach ($this->tasks->tasks as $textTask) {
                // print ("--- apply task: " . $textTask->name . "\r\n");
                print (">>>---------------------------------" . "\r\n");

                switch (strtolower($textTask->name)) {
                    //--- let the task run -------------------------

                    case 'execute':
                        print ('>>> Call execute task: "'
                            // . $this->actTask->name
                        . '"  >>>' . "\r\n");

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

                    case 'createfilenameslist':
                        print ('Execute task: ' . $textTask->name . "\r\n");

                        $filenamesList = new fileNamesList ();
                        $this->actTask = $this->createTask($filenamesList, $textTask);
                        $filenamesList->execute();

                        print ('createFilenamesList count: ' . count ($this->fileNamesList->fileNames) . "\r\n");

                        $this->fileNamesList = $filenamesList;

                        break;

                    //--- add more files to task -----------------------

                    case 'add2filenameslist':
                        print ('Execute task: ' . $textTask->name . "\r\n");
                        $filenamesList = new fileNamesList ();
                        $filenamesList->assignTask($textTask);
                        $filenamesList->execute();

                        if (empty($this->fileNamesList)) {
                            $this->fileNamesList = new fileNamesList ();
                        }

                        print ('add2FilenamesList count: ' . count ($filenamesList->fileNamesList->fileNames) . "\r\n");

                        $this->fileNamesList->addFilenames($filenamesList->fileNames);
                        break;

                    case 'clearfilenameslist':
                        $this->fileNamesList = new fileNamesList();
                        break;

                    case 'printfilenameslist':
                        print ($this->fileNamesList->text_listFileNames());

                        // stop after print files to check the files
                        // exit (98);
                        break;


                    //=== real task definitions =================================

                    case 'buildextension':
                        $this->actTask = $this->createTask(new buildExtension (), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

                    case 'forceversionid':
                        $this->actTask = $this->createTask(new forceVersionId (), $textTask);
                        break;

                    case 'forcecreationdate':
                        $this->actTask = $this->createTask(new forceCreationDate (), $textTask);
                        break;

                    case 'increaseversionid':
                        $this->actTask = $this->createTask(new increaseVersionId (), $textTask);
                        break;

                    case 'clean4gitcheckin':
                        $this->actTask = $this->createTask(new clean4GitCheckin (), $textTask);
                        break;

                    case 'clean4release':
//                        ToDo: $this->actTask = $this->createTask (new clean4release (), $textTask);
                        break;


                    //--- exchange header tasks --------------------------------------------------

                    case 'exchangeall_licenselines':
                        $this->actTask = $this->createTask(new exchangeAll_licenseLines (), $textTask);
                        break;

                    case 'exchangeall_actcopyrightyearlines':
                        $this->actTask = $this->createTask(new exchangeAll_actCopyrightYearLines (), $textTask);
                        break;

                    case 'exchangeall_authorlines':
                        $this->actTask = $this->createTask(new exchangeAll_authorLines (), $textTask);
                        break;

                    case 'exchangeall_linklines':
                        $this->actTask = $this->createTask(new exchangeAll_linkLines (), $textTask);
                        break;

                    case 'exchangeall_packages':
                        $this->actTask = $this->createTask(new exchangeAll_packages (), $textTask);
                        break;

                    case 'exchangeall_sincecopyrightyear':
                        $this->actTask = $this->createTask(new exchangeAll_sinceCopyrightYearLines (), $textTask);
                        break;

                    case 'exchangeall_subpackagelines':
                        $this->actTask = $this->createTask(new exchangeAll_subPackageLines (), $textTask);
                        break;

                    case 'exchangeall_headers':
                        $this->actTask = $this->createTask(new buildExtension (), $textTask);
                        break;

                    case 'updateall_fileheaders':
                        $this->actTask = $this->createTask(new updateAll_fileHeaders (), $textTask);

                        // run task
                        $hasError = $this->actTask->execute();

                        break;

//                    case 'X':
//                        $this->actTask = $this->createTask (new buildExtension (), $textTask);
//                        break;
//
//                    case 'Y':
//                        $this->actTask = $this->createTask (new buildExtension (), $textTask);//                        break;
//
//                    case 'Z':
//                        $this->actTask = $this->createTask (new buildExtension (), $textTask);
//                        break;
//
                    default:
                        print ('!!! Execute unknown task: "' . $textTask->name . '" !!!');
                        throw new Exception('!!! Execute unknown task: "' . $textTask->name . '" !!!');
                } // switch

                // $OutTxt .= $task->text() . "\r\n";
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit applyTasks: ' . $hasError . "\r\n");

        return $hasError;
    }

    private function createTask(executeTasksInterface $execTask, task $textTask): executeTasksInterface
    {
        print ('Assign task: ' . $textTask->name . "\r\n");

        $execTask->assignTask($textTask);

        return $execTask;
    }

    public function tasksText()
    {
        // $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt = "";

        $OutTxt .= "--- doBuildTasks: Tasks ---" . "\r\n";

        // $OutTxt .= "Tasks count: " . $this->textTasks->count() . "\r\n";

        $OutTxt .= $this->tasks->text() . "\r\n";

        return $OutTxt;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- doBuildTasks: class  ---" . "\r\n";


        $OutTxt .= "Not defined yet " . "\r\n";

        /**
         * $OutTxt .= "fileName: " . $this->fileName . "\r\n";
         * $OutTxt .= "fileExtension: " . $this->fileExtension . "\r\n";
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . "\r\n";
         * $OutTxt .= "filePath: " . $this->filePath . "\r\n";
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . "\r\n";
         * /**/

        return $OutTxt;
    }

    public function extractTasksFromFile(mixed $taskFile)
    {
        $tasks = new tasks();
        $this->assignTasks($tasks->extractTasksFromFile($taskFile));
    }


} // doBuildTasks

