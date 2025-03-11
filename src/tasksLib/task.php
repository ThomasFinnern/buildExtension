<?php

namespace Finnern\BuildExtension\src\tasksLib;

// not used see tasksOptionsTest.php: add tasks and options *.php also

use Exception;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;

/*================================================================================
Class task
================================================================================*/

/**
 * Keeps name and options of a task
 *
 * Assign data by one line or by file or line array
 * the file may contain empty lines or commented parts like in c++
 *
 * File content example
 *    task:clean4Checkin.tsk
 *    /type=component
 *    //srcRoot="../../LangMan4Dev"
 *    /srcRoot="../../LangMan4Dev"
 *    /isNoRecursion=false
 *
 *
 * See other *.tsk for examples
 */

// ToDo: inherit from options
class task // extends options
{
    /**
     * @var string
     */
    public $name = "";

    /**
     * @var \Finnern\BuildExtension\src\tasksLib\options List of assinged options
     */
    public options $options;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct()
    {
//        parent::__construct();
        $this->clear();
    }

    /**
     * reset to empty task
     */
    public function clear(): void
    {
        $this->name = '';
        $this->options = new options();
    }

    public function __construct1(string $name, options $options)
    {
        $this->name = $name;
        $this->options = $options;
    }

    /**
     * Extract single line containing starting task:... and multiple options line /xxx=nnn ....
     * @param string $taskStringIn
     * @return $this
     */
    public function extractTaskFromString(string $taskStringIn = ""): task
    {
        $this->clear();

        try {
            $taskStringTrimmed = Trim($taskStringIn);

            if (str_starts_with(strtolower($taskStringTrimmed), 'task:')) {

                // remove 'task:' from line, trim space after 'task: taskname'
                $taskString = trim(substr($taskStringTrimmed, 5));

                // 'task01name /option1 /option2=xxx /option3="01teststring"'
                $idx = strpos($taskString, " ");

                // name without options
                if ($idx == false) {
                    // task:....
                    $this->name = $taskString;
                } else {
                    // name with options (task:exchangeActCopyrightYear /fileName=".../src/Model/GalleryTreeModel.php" /copyrightDate=1999)
                    $this->name = substr($taskString, 0, $idx);

                    $optionsString = substr($taskString, $idx + 1);

                    $this->options->extractOptionsFromString($optionsString);
                }

            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    /**
     * Extract single task from lines of file
     * See *.tsk (*.opt) for examples
     *
     * @param string $taskFile
     * @return $this
     */
    public function extractTaskFromFile(string $taskFile): task
    {
        print('*********************************************************' . "\r\n");
        print ("extractTaskFromFile: " . $taskFile . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        $this->clear();

        try {
            if (!is_file($taskFile)) {
                // not working $realPath = realpath($taskFile);
                throw new Exception('Task file not found: "' . $taskFile . '"');
            }

            $content = file_get_contents($taskFile); //Get the file
            $lines = explode("\n", $content); //Split the file by each line

            $this->extractTaskFromLines($lines);

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    /**
     * @param \Finnern\BuildExtension\src\tasksLib\options $taskOptions
     * @param string $optionsString
     * @return void
     */
//    public function extractOptionsFromString(string $optionsString): void
//    {
//        $this->options->extractOptionsFromString($optionsString);
//    }

    /**
     * @param array $lines
     * @return void
     */
    public function extractTaskFromLines(array $lines): void
    {
        $taskLine = '';


        $this->clear();

        $isTaskNameFound = false; // then options

        foreach ($lines as $line) {

            //--- comments and trim -------------------------------------------

            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // ignore comments
            if (str_starts_with($line, '//')) {
                continue;
            }

            // ToDo: use before each ? "/*" comments like lang manager

            //--- useful line -------------------------------------------

            // find task name first
            if ( ! $isTaskNameFound) {

                if (str_starts_with(strtolower($line), 'task:')) {
                    $this->extractTaskFromString ($line);
                    $isTaskNameFound = true;
                }
            } else {
                $this->options->extractOptionsFromString ($line);
            }

        }
    }

    public function extractOptionsFromFile(string $optionsFile): options
    {
        return $this->options->extractOptionsFromFile($optionsFile);
    }

    public function extractOptionsFromLines(array $lines): void
    {
        $this->options->extractOptionsFromLines($lines);
    }

//    public function addOption(option $option): void
//    {
//        $this->options->addOption($option);
//    }

    /*
     * One line representation
     */
    public function __toString() {
        $taskLine = 'task:' . $this->name;

        // if ( ! empty ($this->options)) {
        $taskLine .= $this->options;
        //}

        return $taskLine;
    }

    public function text(): string
    {
        // $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt = "";
        $OutTxt .= "--- task: " . $this->name . "\r\n";
        if ($this->options->count() > 0) {
            // $OutTxt .= "options: ";
            $OutTxt .= $this->options->text(); // . "\r\n";
        }

        return $OutTxt;
    }



} // task
