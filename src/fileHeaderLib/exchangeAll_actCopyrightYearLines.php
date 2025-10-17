<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileHeaderLib\fileHeaderByFileLine;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;

/*================================================================================
Class exchangeAll_actCopyrightYear
================================================================================*/

class exchangeAll_actCopyrightYearLines extends baseExecuteTasks
    implements executeTasksInterface
{
    //--- use file lines for task ----------------------

    public fileHeaderByFileLine $fileHeaderByFileLine;

    public string $yearText = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion=false, $yearText = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("yearText: " . $yearText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct ($srcRoot, $isNoRecursion);

            $this->yearText = $yearText;

            //--- use file lines for task ----------------------

            $this->fileHeaderByFileLine = new fileHeaderByFileLine();

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }


//    public function assignTask(task $task): int
//    {
//        $hasError = 0;
//
//        $this->task = $task;
//
//        // $this->taskName = $task->name;
//
//        $options = $task->options;
//
//        // ToDo: Extract assignOption on all assignTask
//        foreach ($options->options as $option) {
//
////            $isBaseOption = $this->assignBaseOption($option);
////            if (!$isBaseOption) {
//            $this->assignOption($option);//, $task->name);
////            }
//        }
//
//        return $hasError;
//    }
//
    /**
     * @param option $option
     * @return bool
     */
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = parent::assignOption($option);

        if (!$isOptionConsumed) {

            $isOptionConsumed = $this->fileHeaderByFileLine->assignOption($option);
        }

        if ( ! $isOptionConsumed) {
            switch (strtolower($option->name)) {

                case strtolower('yearText'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->yearText = $option->value;
                    $isOptionConsumed = true;
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }


    public function execute(): int
    {
        //--- collect files ---------------------------------------

//        // files not set already
//        if (count($this->fileNamesList->fileNames) == 0) {
//            $fileNamesList = new fileNamesList ($this->srcRoot, 'php',
//                '', $this->isNoRecursion);
//            $this->fileNamesList = $fileNamesList;
//
//            $fileNamesList->scan4Filenames();
//        }

        // collect file list if not existing
        if (count($this->fileNamesList->fileNames) == 0) {
            $this->fileNamesList->execute();

            if (count($this->fileNamesList->fileNames) == 0) {

                echo '%%% Attention: No files retrieved from: "' . $this->fileNamesList->srcRoot . '"    %%%' . PHP_EOL;
                return -975;
            }
        }

        // tell factory to use classes
        $this->fileHeaderByFileLine->assignOptionCallerProjectId($this->callerProjectId);

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);

            $this->fileHeaderByFileLine->exchangeActCopyrightYear(
                $fileName->srcPathFileName,
                $this->yearText,
            );
        }

        return 0;
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- exchangeAll_actCopyrightYearLines ---" . PHP_EOL;


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

} // exchangeAll_actCopyrightYear

