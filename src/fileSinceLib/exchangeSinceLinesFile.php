<?php

namespace Finnern\BuildExtension\src\fileSinceLib;

use Exception;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileSinceDataFactory;
use Finnern\BuildExtension\src\codeByCaller\fileSinceLib\fileSinceDataBase;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class exchangeSinceLinesFile
================================================================================*/

class exchangeSinceLinesFile
{
    public string $fileName;

    public task $task;
    public readonly string $name;

    protected fileSinceDataBase|null $oSinceFileData;

    // just an indicator can be removed later
    private string $callerProjectId = "";

    public bool $isForceOverwrite = false;
    public bool $isForceVersion = false;
    public bool $isLogOnly = false;
    public string $versionId = "xx.xx.xx";


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcFile = "")
    {
//        parent::__construct();

        $this->oSinceFileData = null; // assign on need

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
     * @param option $option
     *
     * @return bool
     */
    // ToDo: Extract assignOption on all assignTask
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = false;
//        $isOptionConsumed = parent::assignOption($option);

        if (!$isOptionConsumed) {
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

    public function assignOptionCallerProjectId(string $callerProjectId): void
    {
        $this->callerProjectId = $callerProjectId;

        $this->oSinceFileData = fileSinceDataFactory::oSinceFileData($callerProjectId);
    }

    // ToDo: force overwrite
    public function exchangeSinceLines(string $fileName, string $versionId): int
    {

        $hasError = 0;
        $prevAtLine = '';

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeSinceLines' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }

            print ("FileName use: " . $fileName . PHP_EOL);

            $inLines = file($fileName);

            $scanCodeLines = new scanPreHeader();

            $outLines = [];
            foreach ($inLines as $line) {

                $nextLine = $line;

                // keep state of brackets and comments and remove comment lines
                $scanCodeLines->nextLine($line);

                if ($scanCodeLines->isInPreFunctionComment) {

                    // if (str_contains($line, '@since')) {
                    if ($scanCodeLines->isAtSinceLine) {
                        {
                            // Align version to above lines space

                            $lineNbr = $scanCodeLines->lineNumber;

                            $nextLine = $this->oSinceFileData->exchangeLine($line,
                                $this->versionId,
                                $scanCodeLines->prevAlignIdx,
                                $this->isForceVersion, $this->isLogOnly,
                                $lineNbr,
                                $scanCodeLines->prevAtLine,
                                $scanCodeLines->isTabFound);
                        }
                    }
                }

                $outLines[] = $nextLine;
            }

            // write to file
            if ($this->oSinceFileData->isChanged() == true) {

                //$outLines = $this->oSinceFileData->fileLines();

                $isSaved = file_put_contents($fileName, $outLines);

                print (">> Changed FileName: " . $fileName . PHP_EOL);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeSinceLines: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    public function assignOptions(bool $isForceOverwrite, bool $isForceVersion, bool $isLogOnly, string $versionId)
    {
        $this->isForceOverwrite = $isForceOverwrite;
        $this->isForceVersion = $isForceVersion;
        $this->isLogOnly = $isLogOnly;

        $this->versionId = $versionId;
    }

}
