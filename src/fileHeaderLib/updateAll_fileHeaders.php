<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

// use \DateTime;
use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
//use Finnern\BuildExtension\src\fileHeaderLib\fileHeaderByFileData;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;

/*================================================================================
Class updateAll_fileHeaders
================================================================================*/

class updateAll_fileHeaders extends baseExecuteTasks
    implements executeTasksInterface
{
    //--- use file header for task ----------------------

    public fileHeaderByFileData $fileHeaderByFileData;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion = false)
    {
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct ($srcRoot, $isNoRecursion);

            //--- use file header for task ----------------------

            $this->fileHeaderByFileData = new fileHeaderByFileData();


        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }

    }


    /**
     * @param options $options
     * @param task $task
     * @return void
     */
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = parent::assignOption($option);

        if (!$isOptionConsumed) {

            $isOptionConsumed = $this->fileHeaderByFileData->assignOption($option);
        }

//        if (!$isOptionConsumed) {
//
//            switch (strtolower($option->name)) {
//                case strtolower('X'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $isOptionConsumed = true;
//                    break;
//
//            } // switch
//        }

        return $isOptionConsumed;
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

        // tell factory to use classes
        $this->fileHeaderByFileData->assignOptionCallerProjectId($this->callerProjectId);

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);

            $this->fileHeaderByFileData->upgradeHeader($fileName->srcPathFileName);


//            // !!! test single file
//            break;
//            // !!! test single file

        }

        return 0;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- updateAll_fileHeaders ---" . PHP_EOL;


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


} // updateAll_fileHeaders

