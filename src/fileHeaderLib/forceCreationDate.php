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
//            print('*********************************************************' . "\r\n");
//            print ("Construct forceCreationDate: " . "\r\n");
////            print ("srcFile: " . $srcFile . "\r\n");
////            print ("dstFile: " . $dstFile . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct ($srcRoot = "", $isNoRecursion=false);

            // $date_format        = 'Ymd';
            $date_format = 'd.m.Y';
            $this->creationDate = date($date_format);

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
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
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                        $this->name = $option->value;
                        break;

                    case strtolower('date'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                        $this->creationDate = $option->value;
                        break;

                    default:
                        print ('!!! error: requested option is not supported: ' . $task->name . '.' . $option->name . ' !!!' . "\r\n");
                } // switch

                // $OutTxt .= $task->text() . "\r\n";
            }
        }

        return 0;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print("Execute forceCreationDate : " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        $hasError = $this->exchangeCreationDate();

        return $hasError;
    }

    function exchangeCreationDate(): int
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeCreationDate' . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            $manifestPathFileName = $this->manifestPathFileName();
            print ("manifestPathFileName: " . $manifestPathFileName . "\r\n");

            $creationDate = $this->creationDate;
            print ("CreationDate: " . $creationDate . "\r\n");

            $hasError = $this->exchangeCreationDateInManifestFile($manifestPathFileName, $creationDate);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeCreationDate: ' . $hasError . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- forceCreationDate ---" . "\r\n";


        $OutTxt .= "Text(): Not defined yet " . "\r\n";

        /**
         * $OutTxt .= "fileName: " . $this->fileName . "\r\n";
         * $OutTxt .= "fileExtension: " . $this->fileExtension . "\r\n";
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . "\r\n";
         * $OutTxt .= "filePath: " . $this->filePath . "\r\n";
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . "\r\n";
         * /**/

        return $OutTxt;
    }


} // forceCreationDate

