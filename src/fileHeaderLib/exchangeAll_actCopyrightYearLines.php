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
    /**
     * @var fileNamesList
     */

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion=false, $yearText = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . "\r\n");
//            print ("srcRoot: " . $srcRoot . "\r\n");
//            print ("yearText: " . $yearText . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct ($srcRoot, $isNoRecursion);

            $this->yearText = $yearText;

            //--- use file lines for task ----------------------

            $this->fileHeaderByFileLine = new fileHeaderByFileLine();

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
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
     * @param options $options
     * @param task $task
     * @return void
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
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
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
        }

        // tell factory to use classes
        $this->fileHeaderByFileLine->assignOptionCallerProjectId($this->callerProjectId);

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . "\r\n");

            $this->fileHeaderByFileLine->exchangeActCopyrightYear(
                $fileName->srcPathFileName,
                $this->yearText,
            );
        }

        return 0;
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- exchangeAll_actCopyrightYearLines ---" . "\r\n";


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

} // exchangeAll_actCopyrightYear

