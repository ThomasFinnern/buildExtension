<?php

namespace Finnern\BuildExtension\cleanUpLib;

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
//            print('*********************************************************' . "\r\n");
//            print ("srcRoot: " . $srcRoot . "\r\n");
//            print ("linkText: " . $linkText . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct ($srcRoot, $isNoRecursion);

//            $this->fileNamesList = new fileNamesList();
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- clean4GitCheckin ---" . "\r\n";


        $OutTxt .= "Not defined yet " . "\r\n";

        /**
         * $OutTxt .= "fileName: " . $this->fileName . "\r\n";
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . "\r\n";
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
//				case 'cleanlines': // trim / no tabs
//					print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//					break;

//				case 'Y':
//					print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//					break;
//
//				case 'Z':
//					print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//					break;

                    default:
                        print ('!!! error: required option is not supported: ' . $task->name . '.' . $option->name . ' !!!' . "\r\n");
                } // switch

                // $OutTxt .= $task->text() . "\r\n";
            }
        }

        return 0;
    }

    public function executeFile(string $filePathName): int
    {
        // create a one file 'fileNamesList' object
        $this->fileNamesList = new fileNamesList();
        $this->fileNamesList[] = $filePathName;

        $this->execute();

        return (0);
    }

    public function execute(): int
    {
        //--- collect files ---------------------------------------

        // files not set already
        if (count($this->fileNamesList->fileNames) == 0) {
            $fileNamesList = new fileNamesList ($this->srcRoot, '');
            $this->fileNamesList = $fileNamesList;

            $fileNamesList->scan4Filenames();
        } else {
            // use given files
            // $fileNamesList = $this->fileNamesList;
        }

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {
            $this->beautifyFile($fileName->srcPathFileName);
        }

        return (0);
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
                    $outLines [] = rtrim($line) . "\r\n";
                } else {
                    $trimmed = rtrim($line) . "\r\n";
                    $outLines [] = $trimmed;

                    if (strlen($trimmed) < strlen($line)) {
                        $isExchanged = true;
                    }
                }
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return [$outLines, $isExchanged];
    }

} // clean4GitCheckin

