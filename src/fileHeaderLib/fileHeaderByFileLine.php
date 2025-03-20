<?php

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\fileManifestLib\copyrightText;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class fileHeaderByFile
================================================================================*/

/**
 * Exchange one header line in given file
 * Call with property value in task option
 */
class fileHeaderByFileLine extends fileHeaderData
{

    //
    // public fileHeaderData $oByFile;

    public string $fileName;

    public task $task;
    public readonly string $name;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcFile = "")
    {
        parent::__construct();

        // dummy
        //$this->oByFile = new fileHeaderData();

        $this->fileName = $srcFile;
    }


    /*--------------------------------------------------------------------
    exchangePackage
    --------------------------------------------------------------------*/

    function exchangePackage(string $fileName = ""):int
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangePackage' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangePackage: ' . $hasError . "\r\n");

        return $hasError;
    }


    /*--------------------------------------------------------------------
    exchangeSubPackage
    --------------------------------------------------------------------*/

    /**
     * @param mixed $line
     * @return string
     */
    public function replacePackageLine(mixed $line): string
    {
        $oldValue = $this->scan4HeaderValueInLine('package', $line);

        // assign standard
        $packageLine = $this->headerFormat('package', $this->package);

        return $packageLine;
    }

    /*--------------------------------------------------------------------
    exchangeSubPackage
    --------------------------------------------------------------------*/

    function exchangeSubPackage(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeSubPackage' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeSubPackage: ' . $hasError . "\r\n");

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
        $oldValue = $this->scan4HeaderValueInLine('subpackage', $line);

        // assign standard
        $subPackageLine = $this->headerFormat('subpackage', $this->subpackage);

        return $subPackageLine;
    }


    /*--------------------------------------------------------------------
    insertSubPackage
    --------------------------------------------------------------------*/

    function insertSubPackage(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('insertSubPackage' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit insertSubPackage: ' . $hasError . "\r\n");

        return $hasError;
    }

    /*--------------------------------------------------------------------
    exchangeLink
    --------------------------------------------------------------------*/

    function exchangeLink(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeLink' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeLink: ' . $hasError . "\r\n");

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
        $oldValue = $this->scan4HeaderValueInLine('link', $line);

        // assign standard
        $LinkLine = $this->headerFormat('link', $this->link);

        return $LinkLine;
    }

    /*--------------------------------------------------------------------
    exchangeLink
    --------------------------------------------------------------------*/

    public function assignTask(task $task): int
    {
        $this->task = $task;

//        $options = $task->options;
//
//        foreach ($options->options as $option) {
//
//            switch (strtolower($option->name)) {
//
//                case '???':
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                    $this->??? = $option->value;
//                    break;

        return 0;
    }

    public function execute(): int
    {
        $task = $this->task;
        switch (strtolower($task->name)) {
            case 'exchangepackage':
                print ('Execute task: ' . $task->name . "\r\n");


                break;

            case 'exchangesubpackage':
                print ('Execute task: ' . $task->name . "\r\n");


                break;

            case 'exchangelicense':
                print ('Execute task: ' . $task->name . "\r\n");

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $this->exchangeLicense($fileName);
                break;

            case 'exchangeActCopyrightYear':
                print ('Execute task: ' . $task->name . "\r\n");

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $copyrightDate = $options->getOption('copyrightDate');

                $this->exchangeActCopyrightYear($fileName, $copyrightDate);
                break;

            case 'exchangeSinceCopyrightYear':
                print ('Execute task: ' . $task->name . "\r\n");

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $copyrightDate = $options->getOption('copyrightDate');

                // ToDo: create exchangeSinceCopyrightYear function
                $this->exchangeSinceCopyrightYear($fileName, $copyrightDate);
                break;

            case 'exchangeauthor':
                print ('Execute task: ' . $task->name . "\r\n");

                $options = $task->options;
                $fileName = $options->getOption('fileName');
                $this->exchangeAuthor($fileName);
                break;

            case 'exchangersglink':
                print ('Execute task: ' . $task->name . "\r\n");


                break;

            case 'X':
                print ('Execute task: ' . $task->name . "\r\n");


                break;

            case 'Y':
                print ('Execute task: ' . $task->name . "\r\n");


                break;

            default:
                print ('!!! Task not executed: ' . $task->name . '!!!' . "\r\n");

                break;
        }

        return 0;
    }

    function exchangeLicense(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeLicense' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeLicense: ' . $hasError . "\r\n");

        return $hasError;
    }

    /**
     * @param mixed $line
     * @return string
     */
    public function replaceLicenseLine(mixed $line): string
    {
        $oldValue = $this->scan4HeaderValueInLine('license', $line);

        // assign standard
        $licenseLine = $this->headerFormat('license', $this->license);

        return $licenseLine;
    }

    function exchangeActCopyrightYear(string $fileName = "", string $toYear = '')
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeActCopyrightYear' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print ("Up to year in: " . $toYear . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

            if (empty ($toYear)) {
                $date_format = 'Y';
                $toYear = date($date_format);
            }
            print ("Up to year use: " . $toYear . "\r\n");


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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeActCopyrightYear: ' . $hasError . "\r\n");

        return $hasError;
    }

    /**
     * @param mixed $line
     * @param string $year
     * @return string
     */
    public function replaceActCopyrightLine(string $line, string $year): string
    {
        $this->copyright = new copyrightText($line);
        $this->copyright->actCopyrightDate = $year;

        $copyrightLine = $this->headerFormatCopyright();

        return $copyrightLine;
    }

    public function exchangeSinceCopyrightYear(string $fileName, string $sinceYear)
    {
        // ToDo: create exchangeSinceCopyrightYear function

//        throw new Exception("test before use: ??? overwrite valid ...");

        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeSinceCopyrightYear' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print ("Since year in: " . $sinceYear . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

            if (empty ($sinceYear)) {
                $date_format = 'Y';
                $sinceYear = date($date_format);
            }
            print ("Since year use: " . $sinceYear . "\r\n");


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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeSinceCopyrightYear: ' . $hasError . "\r\n");

        return $hasError;
    }

    /**
     * @param mixed $line
     * @param string $sinceYear
     * @return string
     */
    public function replaceSinceCopyrightLine(string $line, string $year): string
    {
        $this->copyright = new copyrightText($line);
        $this->copyright->sinceCopyrightDate = $year;

        $copyrightLine = $this->headerFormatCopyright();

        return $copyrightLine;
    }

    function exchangeAuthor(string $fileName = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('exchangeAuthor' . "\r\n");
            print ("FileName in: " . $fileName . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit exchangeAuthor: ' . $hasError . "\r\n");

        return $hasError;
    }

    /**
     * @param mixed $line
     * @return string
     */
    public function replaceAuthorLine(mixed $line): string
    {
        $oldValue = $this->scan4HeaderValueInLine('author', $line);

        // keep author
        if ($oldValue == 'finnern') {
            // "RSGallery2 Team <team2@rsgallery2.org>";
            $newValue = $this->author;
        } else {
            // keep author
            if (str_starts_with(strtolower($oldValue), 'rsgallery2')) {
                // "RSGallery2 Team <team2@rsgallery2.org>";
                $newValue = $this->author;
            } else {
                // $newValue      = $author; // not given then format old value
                $newValue = $oldValue;
            }
        }

        $authorLine = $this->headerFormat('author', $newValue);
        return $authorLine;
    }

    public function byFileText()
    {
        $OutTxt = "";
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- fileHeaderByFile ---" . "\r\n";

        $OutTxt .= ">>> --- result ----------------" . "\r\n";

        $OutTxt .= $this->text() . "\r\n";

        $OutTxt .= ">>> --- file data ----------------" . "\r\n";

        $OutTxt .= "fileName: " . $this->fileName . "\r\n";
//        $OutTxt .= $this->oByFile->text();

        $OutTxt .= ">>> --- file lines ----------------" . "\r\n";

        $OutTxt .= "fileName: " . $this->fileName . "\r\n";

        return $OutTxt;
    }


} // fileHeaderByFile

