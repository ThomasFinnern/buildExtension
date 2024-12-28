<?php

namespace forceVersionId;

require_once "./iExecTask.php";
require_once "./baseExecuteTasks.php";

use Exception;
use ExecuteTasks\baseExecuteTasks;
use ExecuteTasks\executeTasksInterface;
use FileNamesList\fileNamesList;
use task\task;

//use function commandLine\argsAndOptions;
//use function commandLine\print_header;
//use function commandLine\print_end;

/*================================================================================
Class forceVersionId
================================================================================*/

class forceVersionId extends baseExecuteTasks
    implements executeTasksInterface
{
    // internal
    private string $componentVersion = '';
    private string $componentName = '';
    private string $manifestPathFileName = '';

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....

    public function __construct($srcRoot = "",
                                $isNoRecursion=false,
                                $componentName = '',
                                $componentVersion="1.2.3.444")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . "\r\n");
//            print ("Construct forceVersionId: " . "\r\n");
////            print ("srcFile: " . $srcFile . "\r\n");
////            print ("dstFile: " . $dstFile . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct ($srcRoot, $isNoRecursion);

            $this->componentName = $componentName;
            $this->componentVersion = $componentVersion;

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
                    case 'name':
                        print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                        $this->componentName = $option->value;
                        break;

                    case 'version':
                        print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                        $this->componentVersion = $option->value;
                        break;

                    default:
                        print ('!!! error: required option is not supported: ' . $task->name . '.' . $option->name . ' !!!' . "\r\n");
                } // switch

                // $OutTxt .= $task->text() . "\r\n";
            }
        }

        return 0;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print ("Execute forceVersionId : " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        $hasError = $this->exchangeVersionId();

        return $hasError;
    }

    function exchangeVersionId()
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeVersionId' . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            $manifestPathFileName = $this->manifestPathFileName();
            print ("manifestPathFileName: " . $manifestPathFileName . "\r\n");

            $componentVersion = $this->componentVersion;
            print ("version: " . $componentVersion . "\r\n");

            $hasError = $this->exchangeVersionInManifestFile($manifestPathFileName, $componentVersion);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeVersionId: ' . $hasError . "\r\n");

        return $hasError;
    }

    /*--------------------------------------------------------------------
    funYYY
    --------------------------------------------------------------------*/

    private function manifestPathFileName(): string
    {
        if ($this->manifestPathFileName == '') {
            $this->manifestPathFileName = $this->srcRoot . '/' . $this->componentName . '.xml';
        }

        return $this->manifestPathFileName;
    }

    private function exchangeVersionInManifestFile(string $manifestFileName, string $strVersion)
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
                    // 	<version>5.0.12.4</version>
                    if (str_contains($line, '<version>')) {

                        //--- assign version -----------------------------------

                        $this->versionId->outVersionId = $strVersion;

                        //--- version line -----------------------------------

                        $outLine = $this->versionId->formatVersionIdManifest () . "\r\n";

                        //--- keep line -----------------------------------

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

        // $hasError = $this->exchangeVersionId ();

        return ($hasError);
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- forceVersionId ---" . "\r\n";


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


} // forceVersionId
