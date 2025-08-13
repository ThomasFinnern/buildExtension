<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

// use \DateTime;
use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileHeaderLib\fileHeaderByFileLine;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class exchangeAlllinks
================================================================================*/

class exchangeAll_linkLines extends baseExecuteTasks
    implements executeTasksInterface
{
    public string $linkText = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion = false, $linkText = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("linkText: " . $linkText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct ($srcRoot, $isNoRecursion);

            $this->linkText = $linkText;

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- exchangeAll_linksLines ---" . PHP_EOL;


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
                    case strtolower('linktext'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                        $this->linkText = $option->value;
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

                echo '%%% Attention: No files retrieved from: ' . $this->fileNamesList->srcRoot . '%%%' . PHP_EOL;
                return -975;
            }
        }

        //--- use file header link task ----------------------

        $fileHeaderByFileLine = new fileHeaderByFileLine();

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);

            $fileHeaderByFileLine->exchangeLink($fileName->srcPathFileName);
        }

        return 0;
    }
} // exchangeAlllinks

