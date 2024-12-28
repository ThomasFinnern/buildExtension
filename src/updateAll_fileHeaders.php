<?php

namespace updateAll_fileHeaders;

require_once "./iExecTask.php";
require_once "./baseExecuteTasks.php";

require_once "./fileHeaderByFileData.php";


// use \DateTime;
use Exception;
use ExecuteTasks\baseExecuteTasks;
use ExecuteTasks\executeTasksInterface;
use FileHeader\fileHeaderByFileData;
use FileNamesList\fileNamesList;
use task\task;

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


    // Task name with options
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);

            // base options are already handled
            if (!$isBaseOption) {
                $isFileHeaderOption = $this->fileHeaderByFileData->assignOption($option);
            }

            if ( ! $isBaseOption && ! $isFileHeaderOption) {

                switch (strtolower($option->name)) {
//				case 'X':
//					print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//					break;
//
//				case 'Y':
//					print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//					break;
//
//				case 'Z':
//					print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//					break;

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

        // files not set already use local file names task
        if (count($this->fileNamesList->fileNames) == 0) {
            $fileNamesList = new fileNamesList ($this->srcRoot, 'php ts',
                '', $this->isNoRecursion);
            $this->fileNamesList = $fileNamesList;

            $fileNamesList->scan4Filenames();
        }
//        else {
//            // use given files
//            // $fileNamesList = $this->fileNamesList;
//        }

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {
            $this->fileHeaderByFileData->upgradeHeader($fileName->srcPathFileName);
        }

        return (0);
    }
} // updateAll_fileHeaders

