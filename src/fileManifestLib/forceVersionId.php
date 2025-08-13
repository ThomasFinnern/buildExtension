<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

use Exception;
use Finnern\BuildExtension\src\fileManifestLib\manifestXml;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
// use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;

//use Finnern\BuildExtension\src\tasksLib\commandLineLib;

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

    private manifestXml $manifestXml;

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
//            print('*********************************************************' . PHP_EOL);
//            print ("Construct forceVersionId: " . PHP_EOL);
////            print ("srcFile: " . $srcFile . PHP_EOL);
////            print ("dstFile: " . $dstFile . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct ($srcRoot, $isNoRecursion);

            $this->componentName = $componentName;
            $this->componentVersion = $componentVersion;

            $this->manifestXml = new manifestXml();

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


// ToDo: separate assign options assignForceVersionOption
        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);

            if (!$isBaseOption) {
                switch (strtolower($option->name)) {
                    case strtolower('name'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                        $this->componentName = $option->value;
                        break;

                    case strtolower('version'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                        $this->componentVersion = $option->value;
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
        print("Execute forceVersionId : " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        $hasError = $this->exchangeVersionId();

        return $hasError;
    }

    function exchangeVersionId()
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeVersionId' . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            $manifestPathFileName = $this->manifestPathFileName();
            print ("manifestPathFileName: " . $manifestPathFileName . PHP_EOL);

            $componentVersion = $this->componentVersion;
            print ("version: " . $componentVersion . PHP_EOL);

            $hasError = $this->exchangeVersionInManifestFile($manifestPathFileName, $componentVersion);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit force:exchangeVersionId: ' . $hasError . PHP_EOL);

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

    private function exchangeVersionInManifestFile(string $manifestFileName, string $outVersionId) :int
    {
        $hasError = 0;

        try {

            // does read xml immediately
            $this->manifestXml = new manifestXml($manifestFileName);
            $manifestXml = $this->manifestXml;

            $inVersionId = (string) $manifestXml->getByXml('version', '');

            if ($outVersionId != $inVersionId) {

                // $manifestXml->versionId->outVersionId = $outVersionId;
                $manifestXml->setByXml('version', $outVersionId);

                $isSaved = $manifestXml->writeManifestXml();
                // $isSaved = $manifestXml->writeManifestXml($manifestFileName . '.new');
            }

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return 0;
    }

    public function executeFile(string $filePathName): int // $isChanged
    {
        $hasError = 0;

        // $hasError = $this->exchangeVersionId ();

        return ($hasError);
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- forceVersionId ---" . PHP_EOL;


        // $OutTxt .= "Not defined yet " . PHP_EOL;

        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
         * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
         * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;
         * /**/
        $OutTxt .= $this->manifestXml;

        return $OutTxt;
    }


} // forceVersionId
