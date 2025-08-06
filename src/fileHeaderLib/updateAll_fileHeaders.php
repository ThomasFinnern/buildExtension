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
    //--- use file header author task ----------------------

    public fileHeaderByFileData $fileHeaderByFileData;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion = false)
    {
        try {
//            print('*********************************************************' . "\r\n");
//            print ("srcRoot: " . $srcRoot . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct ($srcRoot, $isNoRecursion);

            //--- use file header author task ----------------------

            $this->fileHeaderByFileData = new fileHeaderByFileData();


        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
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
        }

        //--- iterate over all files -------------------------------------


        $this->fileHeaderByFileData->assignOptionCallerProjectId($this->callerProjectId);

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . "\r\n");

            $this->fileHeaderByFileData->upgradeHeader($fileName->srcPathFileName);


//            // !!! test single file
//            break;
//            // !!! test single file

        }

        return 0;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- updateAll_fileHeaders ---" . "\r\n";


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


} // updateAll_fileHeaders

