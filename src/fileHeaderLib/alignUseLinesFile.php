<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileUseDataFactory;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileUseDataBase;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;

/*================================================================================
Class exchangeAll_actCopyrightYear
================================================================================*/

class alignUseLines
{
    public string $fileName;

    public task $task;
    public readonly string $name;

    protected fileUseDataBase|null $oFileUseData;

    // just an indicator can be removed later
    private string $callerProjectId = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcFile = "")
    {
//        parent::__construct();

        $this->oFileUseData = null; // assign on need

        $this->fileName = $srcFile;
    }


    /*--------------------------------------------------------------------
    assignTask
    --------------------------------------------------------------------*/

    public function assignTask(task $task): int
    {
        $hasError = 0;

        $this->task = $task;

        // $this->taskName = $task->name;

        $options = $task->options;

        // ToDo: Extract assignOption on all assignTask
        foreach ($options->options as $option) {

//            $isBaseOption = $this->assignBaseOption($option);
//            if (!$isBaseOption) {
            $this->assignOption($option);//, $task->name);
//            }
        }

        return $hasError;
    }

    /**
     * @param  option  $option
     *
     * @return bool
     */
    // ToDo: Extract assignOption on all assignTask
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = false;
//        $isOptionConsumed = parent::assignOption($option);

        if ( ! $isOptionConsumed) {
            switch (strtolower($option->name)) {
                case strtolower('filename'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->fileName = $option->value;
                    $isOptionConsumed = true;
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

    public function assignOptionCallerProjectId(string $callerProjectId):void
    {
        $this->callerProjectId = $callerProjectId;

        $this->oFileUseData = fileUseDataFactory::oFileUseData($callerProjectId);
    }

    public function alignUseLines(string $fileName): int
    {

        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('alignUseLines' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }

            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);

            $this->oFileUseData->extractUseLines($lines);

            // Not needed but do prepare sorted lines
            $this->oFileUseData->useLinesSorted();

            $this->oFileUseData->applyBackslashType();

            // write to file
            if ($this->oFileUseData->isChanged() == true) {

                $outLines = $this->oFileUseData->fileLines();

                $isSaved = file_put_contents($fileName, $outLines);

                print (">> Changed FileName: " . $fileName . PHP_EOL);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit alignUseLines: ' . $hasError . PHP_EOL);

        return $hasError;
    }
}