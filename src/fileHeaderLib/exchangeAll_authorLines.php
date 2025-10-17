<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileHeaderLib\fileHeaderByFileLine;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class exchangeAll_authorLines
================================================================================*/

/**
 * Reads file, exchanges one 'author' line
 * Standard replace text is defined in class fileHeaderData
 */
class exchangeAll_authorLines extends baseExecuteTasks
    implements executeTasksInterface
{
    //
    public string $authorText = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "",
                                $isNoRecursion=false,
                                $authorText = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("authorText: " . $authorText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct ($srcRoot, $isNoRecursion);

            $this->authorText = $authorText;

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- exchangeAll_authorLines ---" . PHP_EOL;


        $OutTxt .= "Not defined yet " . PHP_EOL;

        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
         * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
         * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;
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
                    case strtolower('authortext'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                        $this->authorText = $option->value;
                        break;


                    default:
                        print ('!!! error: requested option is not supported: ' . $task->name . '.' . $option->name . ' !!!' . PHP_EOL);
                } // switch

                // $OutTxt .= $task->text() . PHP_EOL;
            }
        }

        return 0;
    }

    public function executeFile(string $filePathName): int
    {
        // create a one file 'fileNamesList' object
        $this->fileNamesList = new fileNamesList();
        $this->fileNamesList->fileNames[] = $filePathName;

        $this->execute();

        return 0;
    }

    public function execute(): int
    {
        //--- collect files ---------------------------------------

        // collect file list if not existing
        if (count($this->fileNamesList->fileNames) == 0) {
            $this->fileNamesList->execute();

            if (count($this->fileNamesList->fileNames) == 0) {

                echo '%%% Attention: No files retrieved from: "' . $this->fileNamesList->srcRoot . '"    %%%' . PHP_EOL;
                return -975;
            }
        }

        //--- use file header author task ----------------------

        $fileHeaderByFileLine = new fileHeaderByFileLine();

        //--- iterate over all files -------------------------------------

        print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);

        foreach ($this->fileNamesList->fileNames as $fileName) {
            $fileHeaderByFileLine->exchangeAuthor($fileName->srcPathFileName);
        }

        return 0;
    }
} // exchangeAll_authorLines

