<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileHeaderDataBase;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileHeaderDataFactory;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class fileHeaderByFile
================================================================================*/

/**
 * Exchange one header line in given file
 * Call with property value in task option
 */
class fileHeaderByFileLine // extends fileHeaderData
{
    public string $fileName;

    public task $task;
    public readonly string $name;

    protected fileHeaderDataBase|null $oFileHeader;

    // just an indicator can be removed later
    private string $callerProjectId = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcFile = "")
    {
//        parent::__construct();

        $this->oFileHeader = null; // assign on need

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
     * @param   option  $option
     *
     * @return void
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

    public function assignOptionCallerProjectId(string $callerProjectId)
    {
        $this->callerProjectId = $callerProjectId;

        $this->oFileHeader = fileHeaderDataFactory::oFileHeaderData($callerProjectId);
    }



    /*--------------------------------------------------------------------
    exchangePackage
    --------------------------------------------------------------------*/

    function exchangePackage(string $fileName = ""):int
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangePackage' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //  * @package  ....
                    if (str_contains($line, '@package')) {

                        $packageLine = $this->replacePackageLine($line);

                        if ($line != $packageLine) {
                            $outLines [] = $packageLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed , no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangePackage: ' . $hasError . PHP_EOL);

        return $hasError;
    }


    /*--------------------------------------------------------------------
    replacePackageLine
    --------------------------------------------------------------------*/

    /**
     * @param mixed $line
     * @return string
     */
    public function replacePackageLine(mixed $line): string
    {
        $oldValue = $this->oFileHeader->scan4HeaderValueInLine('package', $line);

        // assign standard
        $packageLine = $this->oFileHeader->headerFormat('package', $this->oFileHeader->package);

        return $packageLine;
    }

    public function execute(): int
    {

        $task = $this->task;
        switch (strtolower($task->name)) {
            case strtolower('exchangepackage'):
                print ('Execute task: ' . $task->name . PHP_EOL);


                break;

            case strtolower('exchangesubpackage'):
                print ('Execute task: ' . $task->name . PHP_EOL);


                break;

            case strtolower('exchangelicense'):
                print ('Execute task: ' . $task->name . PHP_EOL);

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $this->exchangeLicense($fileName);
                break;

            case strtolower('exchangeActCopyrightYear'):
                print ('Execute task: ' . $task->name . PHP_EOL);

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $copyrightDate = $options->getOption('copyrightDate');

                $this->exchangeActCopyrightYear($fileName, $copyrightDate);
                break;

            case strtolower('exchangeSinceCopyrightYear'):
                print ('Execute task: ' . $task->name . PHP_EOL);

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $copyrightDate = $options->getOption('copyrightDate');

                // ToDo: create exchangeSinceCopyrightYear function
                $this->exchangeSinceCopyrightYear($fileName, $copyrightDate);
                break;

            case strtolower('exchangeauthor'):
                print ('Execute task: ' . $task->name . PHP_EOL);

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $this->exchangeAuthor($fileName);
                break;

            case strtolower('exchangersglink'):
                print ('Execute task: ' . $task->name . PHP_EOL);


                break;

            default:
                print ('!!! Task not executed: ' . $task->name . '!!!' . PHP_EOL);

                break;
        }

        return 0;
    }

    /*--------------------------------------------------------------------
    exchangeSubPackage
    --------------------------------------------------------------------*/

    function exchangeSubPackage(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeSubPackage' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;
            $isFound = false;
            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //  ToDo:
                    if (str_contains($line, '@subpackage')) {
                        $isFound = true;

                        $subPackageLine = $this->replaceSubPackageLine($line);

                        if ($line != $subPackageLine) {
                            $outLines [] = $subPackageLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed , no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            } else {
                // insert if not found
                if ($isFound == false) {
                    $this->insertSubPackage();
                }
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeSubPackage: ' . $hasError . PHP_EOL);

        return $hasError;
    }


    /*--------------------------------------------------------------------
    exchangeLicense
    --------------------------------------------------------------------*/

    /**
     * @param mixed $line
     * @return string
     */
    public function replaceSubPackageLine(mixed $line): string
    {
        $oldValue = $this->oFileHeader->scan4HeaderValueInLine('subpackage', $line);

        // assign standard
        $subPackageLine = $this->oFileHeader->headerFormat('subpackage', $this->oFileHeader->subpackage);

        return $subPackageLine;
    }


    /*--------------------------------------------------------------------
    insertSubPackage
    --------------------------------------------------------------------*/

    function insertSubPackage(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('insertSubPackage' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;
            $isFound = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //  ToDo:
                    if (str_contains($line, '@package')) {
                        $subPackageLine = $this->replaceSubPackageLine($line);

                        if ($line != $subPackageLine) {
                            $outLines [] = $subPackageLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed , no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit insertSubPackage: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    /*--------------------------------------------------------------------
    exchangeLink
    --------------------------------------------------------------------*/

    function exchangeLink(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeLink' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //  * @link
                    if (str_contains($line, '@link')) {

                        $LinkLine = $this->replaceLinkLine($line);

                        if ($line != $LinkLine) {
                            $outLines [] = $LinkLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed , no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeLink: ' . $hasError . PHP_EOL);

        return $hasError;
    }


    /*--------------------------------------------------------------------
    exchangeAuthor
    --------------------------------------------------------------------*/

    /**
     * @param mixed $line
     * @return string
     */
    public function replaceLinkLine(mixed $line): string
    {
        $oldValue = $this->oFileHeader->scan4HeaderValueInLine('link', $line);

        // assign standard
        $LinkLine = $this->oFileHeader->headerFormat('link', $this->oFileHeader->link);

        return $LinkLine;
    }

    function exchangeLicense(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeLicense' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //  * @license     GNU General Public License version 2 or la ....
                    if (str_contains($line, '@license')) {

                        $isFound = true;

                        $licenseLine = $this->replaceLicenseLine($line);

                        if ($line != $licenseLine) {
                            // assign standard
                            $outLines [] = $licenseLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed , no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeLicense: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    /**
     * @param mixed $line
     * @return string
     */
    public function replaceLicenseLine(mixed $line): string
    {
        $oldValue = $this->oFileHeader->scan4HeaderValueInLine('license', $line);

        // assign standard
        $licenseLine = $this->oFileHeader->headerFormat('license', $this->oFileHeader->license);

        return $licenseLine;
    }

    function exchangeActCopyrightYear(string $fileName = "", string $toYear = '')
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeActCopyrightYear' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print ("Up to year in: " . $toYear . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            if (empty ($toYear)) {
                $date_format = 'Y';
                $toYear = date($date_format);
            }
            print ("Up to year use: " . $toYear . PHP_EOL);


            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //   * @copyright (c)  2020-2022 Team
                    if (str_contains($line, '@copyright')) {

                        $copyrightLine = $this->replaceActCopyrightLine($line, $toYear);

                        if ($line != $copyrightLine) {
                            $outLines [] = $copyrightLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed, no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeActCopyrightYear: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    /**
     * @param mixed $line
     * @param string $year
     * @return string
     */
    public function replaceActCopyrightLine(string $line, string $year): string
    {
        $this->oFileHeader->extractHeaderValuesFromLines([$line]);
        $this->oFileHeader->oCopyright->actCopyrightDate = $year;

        $copyrightLine = $this->oFileHeader->headerFormatCopyright();

        return $copyrightLine;
    }

    public function exchangeSinceCopyrightYear(string $fileName, string $sinceYear)
    {
        // ToDo: create exchangeSinceCopyrightYear function

//        throw new Exception("test before use: ??? overwrite valid ...");

        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeSinceCopyrightYear' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print ("Since year in: " . $sinceYear . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            if (empty ($sinceYear)) {
                $date_format = 'Y';
                $sinceYear = date($date_format);
            }
            print ("Since year use: " . $sinceYear . PHP_EOL);


            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //   * @copyright (c)  2020-2022 Team
                    if (str_contains($line, '@copyright')) {

                        $copyrightLine = $this->replaceSinceCopyrightLine($line, $sinceYear);

                        if ($line != $copyrightLine) {
                            $outLines [] = $copyrightLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed, no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeSinceCopyrightYear: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    /**
     * @param mixed $line
     * @param string $sinceYear
     * @return string
     */
    public function replaceSinceCopyrightLine(string $line, string $year): string
    {
        $this->oFileHeader->extractHeaderValuesFromLines([$line]);
        $this->oFileHeader->oCopyright->sinceCopyrightDate = $year;

        $copyrightLine = $this->oFileHeader->headerFormatCopyright();

        return $copyrightLine;
    }

    function exchangeAuthor(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('exchangeAuthor' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);
            $outLines = [];
            $isExchanged = false;

            foreach ($lines as $line) {
                if ($isExchanged) {
                    $outLines [] = $line;
                } else {
                    //  * @author     ...
                    if (str_contains($line, '@author')) {

                        $authorLine = $this->replaceAuthorLine($line);

                        // assign standard
                        if ($line != $authorLine) {
                            $outLines [] = $authorLine;
                            $isExchanged = true;
                        } else {
                            // line already fixed , no file write
                            break;
                        }
                    } else {
                        $outLines [] = $line;
                    }
                }
            }

            // write to file
            if ($isExchanged == true) {
                $isSaved = file_put_contents($fileName, $outLines);
            }
        } catch
        (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit exchangeAuthor: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    /**
     * @param mixed $line
     * @return string
     */
    public function replaceAuthorLine(mixed $line): string
    {
        $oldValue = $this->oFileHeader->scan4HeaderValueInLine('author', $line);

        // keep author
        if ($oldValue == 'finnern') {
            // "RSGallery2 Team <team2@rsgallery2.org>";
            $newValue = $this->oFileHeader->author;
        } else {
            // keep author
            if (str_starts_with(strtolower($oldValue), 'rsgallery2')) {
                // "RSGallery2 Team <team2@rsgallery2.org>";
                $newValue = $this->oFileHeader->author;
            } else {
                // $newValue      = $author; // not given then format old value
                $newValue = $oldValue;
            }
        }

        $authorLine = $this->oFileHeader->headerFormat('author', $newValue);
        return $authorLine;
    }

    public function byFileText()
    {
        $OutTxt = "";
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- fileHeaderByFile ---" . PHP_EOL;

        $OutTxt .= ">>> --- result ----------------" . PHP_EOL;

        $OutTxt .= $this->oFileHeader->text() . PHP_EOL;

        $OutTxt .= ">>> --- file data ----------------" . PHP_EOL;

        $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
//        $OutTxt .= $this->oByFile->text();

        $OutTxt .= ">>> --- file lines ----------------" . PHP_EOL;

        $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;

        return $OutTxt;
    }


} // fileHeaderByFile

