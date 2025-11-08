<?php

namespace Finnern\BuildExtension\src\fileEncloseJexec;

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
Class encloseAll_jexec_LinesFile
================================================================================*/

class encloseAll_jexec_LinesFile
{
    public string $fileName;

    public task $task;
    public readonly string $name;

    protected fileUseDataBase|null $oFileUseData;

    public bool $isLogOnly = false;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/
    private bool $isChanged = false;
    private bool $isLogDev = false;

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
                case strtolower('isLogOnly'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isLogOnly  = (bool) $option->value;
                    $isOptionConsumed = true;
                    break;
                case strtolower('isLogDev'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isLogDev   = (bool) $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('filename'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->fileName = $option->value;
                    $isOptionConsumed = true;
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

//    public function assignOptionCallerProjectId(string $callerProjectId):void
//    {
//        $this->callerProjectId = $callerProjectId;
//
//        $this->oFileUseData = fileUseDataFactory::oFileUseData($callerProjectId);
//    }

    public function encloseAll_jexec_lines(string $fileName): int
    {

        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('encloseAll_jexec_lines' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }

            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);

            $this->isChanged = false;
            $outLines = [];

            foreach ($lines as $lineIdx => $line)
            {
                // do check as not found
                if (!$this->isChanged)
                {

                    // already enclosed
                    if (str_contains($line, "phpcs:disable PSR1.Files.SideEffects"))
                    {
                        // no change needed
                        if ($this->isLogDev)
                        {
                            print ("    No change, function contains 'phpcs:disable PSR1.Files.SideEffects' in line " . $lineIdx . PHP_EOL);
                        }

                        break;
                    }

                    if (str_contains($line, "'_JEXEC'"))
                    {

                        $outLines[] = "// phpcs:disable PSR1.Files.SideEffects" . PHP_EOL;
                        $outLines[] = "\defined('_JEXEC') or die;" . PHP_EOL;
                        $outLines[] = "// phpcs:enable PSR1.Files.SideEffects" . PHP_EOL;

                        $this->isChanged = true;

                        // no change needed
                        if ($this->isLogDev)
                        {
                            print ("~~~ Enclosed change needed in line " . $lineIdx . PHP_EOL);
                        }

                    }
                    else
                    {
                        $outLines[] = $line;
                    }

                }
                else
                {
                    // already enclosed, direct use of next line
                    $outLines[] = $line;
                }
            }

            // on change write to file
            if ($this->isChanged && !$this->isLogOnly)
            {
                $isSaved = file_put_contents($fileName, $outLines);

                print (">> Changed FileName: " . $fileName . PHP_EOL);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit encloseAll_jexec_lines: ' . $hasError . PHP_EOL);

        return $hasError;
    }
}
