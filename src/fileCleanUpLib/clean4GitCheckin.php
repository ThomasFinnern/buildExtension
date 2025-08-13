<?php

namespace Finnern\BuildExtension\src\cleanUpLib;

require_once 'autoload/autoload.php';


// use \DateTime;
use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class clean4GitCheckin
================================================================================*/

class clean4GitCheckin extends baseExecuteTasks
    implements executeTasksInterface
{

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcRoot = "", $isNoRecursion = False)
    {
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("linkText: " . $linkText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct ($srcRoot, $isNoRecursion);

//            $this->fileNamesList = new fileNamesList();
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- clean4GitCheckin ---" . PHP_EOL;


        $OutTxt .= "Not defined yet " . PHP_EOL;

        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
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

// ? separate class ?
//				case strtolower('cleanlines'): // trim / no tabs
//					print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//					break;

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

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);

            $this->beautifyFile($fileName->srcPathFileName);
        }

        return 0;
    }



    private function beautifyFile(string $fileName): bool
    {
        $isExchanged = false;

        try {
            $outLines = file($fileName);

            [$outLines, $isExchanged] = $this->trimLines($outLines, $isExchanged);
            [$outLines, $isExchanged] = $this->tab2spacesLines($outLines, $isExchanged);

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $isExchanged;
    }/**
 * @param false|array $lines
 * @param bool $isExchanged
 * @param array $outLines
 * @return array
 */
    public function trimLines(false|array $lines, bool $isExchanged): array
    {
        $outLines = [];

        try {
            // all lines
            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = rtrim($line) . PHP_EOL;
                } else {
                    $trimmed = rtrim($line) . PHP_EOL;
                    $outLines [] = $trimmed;

                    if (strlen($trimmed) < strlen($line)) {
                        $isExchanged = true;
                    }
                }
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return [$outLines, $isExchanged];
    }

    private function tab2spacesLines(false|array $lines, mixed $isExchanged)
    {
        $outLines = [];
        $tabReplace = "    ";

        try {
            // all lines
            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = str_replace("\t", $tabReplace, $line);;
                } else {
                    $trimmed = str_replace("\t", $tabReplace, $line);
                    $outLines [] = $trimmed;

                    if (strlen($trimmed) < strlen($line)) {
                        $isExchanged = true;
                    }
                }
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return [$outLines, $isExchanged];
    }

} // clean4GitCheckin

