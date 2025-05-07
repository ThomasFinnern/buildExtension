<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileHeaderLib\fileHeaderByFileLine;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class exchangeAll_packages
================================================================================*/

class exchangeAll_packages extends baseExecuteTasks
    implements executeTasksInterface
{
    public string $packageText = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion = false, $packageText = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . "\r\n");
//            print ("srcRoot: " . $srcRoot . "\r\n");
//            print ("packageText: " . $packageText . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct ($srcRoot = "", $isNoRecursion=false);

            $this->packageText = $packageText;

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- exchangeAll_packageLines ---" . "\r\n";


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


    // Task name with options
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);
            if (!$isBaseOption) {
                switch (strtolower($option->name)) {
                    case strtolower('packagetext'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                        $this->packageText = $option->value;
                        break;


                    default:
                        print ('!!! error: required option is not supported: ' . $task->name . '.' . $option->name . ' !!!' . "\r\n");
                } // switch

                // $OutTxt .= $task->text() . "\r\n";
            }
        }

        return 0;
    }

    public function executeFile(string $filePathName): int
    {
        // create a one file 'fileNamesList' object
        $this->fileNamesList = new fileNamesList();
        $this->fileNamesList[] = $filePathName;

        $this->execute();


        return (0);
    }

    public function execute(): int
    {
        //--- collect files ---------------------------------------

        // files not set already
        if (count($this->fileNamesList->fileNames) == 0) {
            $fileNamesList = new fileNamesList ($this->srcRoot, 'php',
                '', $this->isNoRecursion);
            $this->fileNamesList = $fileNamesList;

            $fileNamesList->scan4Filenames();
        } else {
            // use given files
            // $fileNamesList = $this->fileNamesList;
        }

        //--- use file header package task ----------------------

        $fileHeaderByFileLine = new fileHeaderByFileLine();

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {
            $fileHeaderByFileLine->exchangePackage($fileName->srcPathFileName);
        }

        return (0);
    }
} // exchangeAll_packages

