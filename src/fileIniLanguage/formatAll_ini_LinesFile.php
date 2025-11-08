<?php

namespace Finnern\BuildExtension\src\fileIniLanguage;

use Exception;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileUseDataBase;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class formatAll_ini_LinesFile
================================================================================*/

// ToDo: sort lines
// ToDo: sort lines, keep comment above
// ToDo:  /isForceExtensionId="..."

class formatAll_ini_LinesFile
{
    public string $fileName;

    public task $task;
    public readonly string $name;

    protected fileUseDataBase|null $oFileUseData;

    public bool $isLogOnly = false;

    private bool $isChanged = false;
    private bool $isLogDev = false;
    private bool $isSortLines = false; // not supported yet
    private bool $isRemoveDoubles = false;   // not suppoerted yet

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
        foreach ($options->options as $option)
        {

//            $isBaseOption = $this->assignBaseOption($option);
//            if (!$isBaseOption) {
            $this->assignOption($option);//, $task->name);
//            }
        }

        return $hasError;
    }

    /**
     * @param   option  $option
     *
     * @return bool
     */
    // ToDo: Extract assignOption on all assignTask
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = false;
//        $isOptionConsumed = parent::assignOption($option);

        if (!$isOptionConsumed)
        {
            switch (strtolower($option->name))
            {
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

                case strtolower('isSortLines'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isSortLines = (bool) $option->value;
                    $isOptionConsumed  = true;
                    break;
                case strtolower('isRemoveDoubles'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isRemoveDoubles = (bool) $option->value;
                    $isOptionConsumed      = true;
                    break;

                case strtolower('filename'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->fileName   = $option->value;
                    $isOptionConsumed = true;
                    break;

                default:
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

    public function formatAll_ini_lines(string $fileName): int
    {

        $hasError = 0;

        try
        {
            print('*********************************************************' . PHP_EOL);
            print('formatAll_ini_lines' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName))
            {
                $this->fileName = $fileName;
            }
            else
            {
                $fileName = $this->fileName;
            }

            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);

            $this->isChanged = false;
            $outLines        = [];

            $isFirstEmpty = true;
            foreach ($lines as $lineIdx => $line)
            {
                $trimmed = trim($line);

                if ($trimmed == '')
                {
                    // take the first empty line
                    if (!$isFirstEmpty)
                    {
                        $isFirstEmpty = true;
                        $outLines[]   = $line;
                    }
                    else
                    {
                        // remove double empty lines ?
                        if ($this->isRemoveDoubles)
                        {
                            $this->isChanged = true;
                        }
                        else
                        {
                            // keep double empty lines ?
                            $outLines[] = $line;
                        }
                    }

                    continue;
                }
                else
                {
                    $isFirstEmpty = false;
                }

                // comment '#' or ';'
                if (str_starts_with($trimmed, '#') == '' || str_starts_with($trimmed, ';'))
                {
                    $outLines[] = $line;

                    continue;
                }

                $formattedLine = $this->formatIniStyle($line);
                if ($formattedLine !== $line)
                {
                    // already enclosed, direct use of next line
                    $outLines[] = $formattedLine;

                    $this->isChanged = true;
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

            if ($this->isChanged && $this->isLogDev)
            {
                print (">>>===============================================" . $fileName . PHP_EOL);
                print ("~~~ Changed ~~~ FileName: " . $fileName . PHP_EOL);
                print (">>>===============================================" . $fileName . PHP_EOL);

                foreach ($outLines as $lineIdx => $line)
                {
                    //print ($line . PHP_EOL);
                    print ($line);
                }

                print ("<<<===============================================" . $fileName . PHP_EOL);
            }

        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit formatAll_ini_lines: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    private function formatIniStyle(mixed $line)
    {
        $formattedLine = $line;

        // ToDo: empty line ?
        // ToDo: comment line ';' '#' ?
        // ToDo:

        // Extract the individual parts of the ini line, trim them, and recreate the line.

        $parts = explode("=", $line, 2);
        $count = count($parts);

        // extension id exists
        if ($count > 0)
        {
            $langId      = trim($parts[0]);
            $translation = '';

            // translation exists
            if ($count > 1)
            {
                $translation = trim($parts[1]);
            }

            $formattedLine = $langId . '="' . $translation . '"';
        }

        return $formattedLine;
    }
}
