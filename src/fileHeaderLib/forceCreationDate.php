<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class forceCreationDate
================================================================================*/

class forceCreationDate extends baseExecuteTasks
    implements executeTasksInterface
{

    private string $creationDate;
    private string $name;

    // internal
    private string $manifestPathFileName = '';

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....
    public function __construct($srcFile = "", $isNoRecursion = false, $dstFile = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("Construct forceCreationDate: " . PHP_EOL);
////            print ("srcFile: " . $srcFile . PHP_EOL);
////            print ("dstFile: " . $dstFile . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct ($srcRoot = "", $isNoRecursion=false);

            // $date_format        = 'Ymd';
            $date_format = 'd.m.Y';
            $this->creationDate = date($date_format);

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
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
                    case strtolower('name'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                        $this->name = $option->value;
                        break;

                    case strtolower('date'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                        $this->creationDate = $option->value;
                        break;

                    default:
                        print ('!!! error: requested option is not supported: ' . $task->name . '.' . $option->name . ' !!!' . PHP_EOL);
                } // switch

                // $OutTxt .= $task->text() . PHP_EOL;
            }
        }

        return 0;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . PHP_EOL);
        print("Execute forceCreationDate : " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        $hasError = $this->exchangeCreationDate();

        return $hasError;
    }

    function exchangeCreationDate(): int
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeCreationDate' . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            $manifestPathFileName = $this->manifestPathFileName();
            print ("manifestPathFileName: " . $manifestPathFileName . PHP_EOL);

            $creationDate = $this->creationDate;
            print ("CreationDate: " . $creationDate . PHP_EOL);

            $hasError = $this->exchangeCreationDateInManifestFile($manifestPathFileName, $creationDate);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeCreationDate: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    /*--------------------------------------------------------------------
    funYYY
    --------------------------------------------------------------------*/

    private function manifestPathFileName(): string
    {
        if ($this->manifestPathFileName == '') {
            $this->manifestPathFileName = $this->srcRoot . '/' . $this->name . '.xml';
        }

        return $this->manifestPathFileName;
    }

    private function exchangeCreationDateInManifestFile(string $manifestFileName, string $strDate)
    {
        $isSaved = false;

        try {
            $lines = file($manifestFileName);
            $outLines = [];
            $isExchanged = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    // <creationDate>31. May. 2024</creationDate>
                    if (str_contains($line, '<creationDate>')) {
                        $outLine = preg_replace(
                            '/(.*>?)(.*)(<.*)/',
                            '${1}' . $strDate . '${3}',
                            $line,
                        );

                        $outLines [] = $outLine;

                        $isExchanged = true;
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

//            // prepare one string
//            $fileLines = implode("\n", $outLines);
//
//            // write to file
//            //$isSaved = File::write($manifestFileName, $fileLines);
//	        $isSaved = file_put_contents($manifestFileName, $fileLines);

//            // prepare one string
//            $fileLines = implode("", $outLines);
//
//            // write to file
//            //$isSaved = File::write($manifestFileName, $fileLines);
//	        $isSaved = file_put_contents($manifestFileName, $fileLines);

            // write to file
            //$isSaved = File::write($manifestFileName, $fileLines);
            $isSaved = file_put_contents($manifestFileName, $outLines);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $isSaved;
    }

    public function executeFile(string $filePathName): int // $isChanged
    {
        $hasError = 0;

        // $hasError = $this->exchangeCreationDate ();

        return ($hasError);
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- forceCreationDate ---" . PHP_EOL;


        $OutTxt .= "Text(): Not defined yet " . PHP_EOL;

        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
         * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
         * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;
         * /**/

        return $OutTxt;
    }


} // forceCreationDate

