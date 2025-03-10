<?php

namespace Finnern\BuildExtension\src\tasksLib;

// not used see tasksOptionsTest.php: add tasks and options *.php also

use Exception;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;

/*================================================================================
Class task
================================================================================*/

class task
{

    public $name = "";

    public options $options;


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/


    public function __construct()
    {
        $this->clear();
    }

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

    public function extractTaskFromString($taskString = ""): task
    {
        $this->clear();

        try {
            $taskString = Trim($taskString);

            $taskName = '';
            $taskOptions = new options;

            // 'task01name /option1 /option2=xxx /option3="01teststring"'
            $idx = strpos($taskString, " ");

            // name without options
            if ($idx == false) {
                $taskName = substr($taskString, 5);
            } else {
                // name with options (task:exchangeActCopyrightYear /fileName=".../src/Model/GalleryTreeModel.php" /copyrightDate=1999)
                $taskName = substr($taskString, 5, $idx - 5);
                $optionsString = substr($taskString, $idx + 1);

                $taskOptions = (new options())->extractOptionsFromString($optionsString);
            }

            $this->name = $taskName;
            $this->options = $taskOptions;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    /**
     *
     * File with single task
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

            $taskLine = '';

            foreach ($lines as $line) {

                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                // ToDo use before each ? "/*" comments like lang manager

                // ignore comments
                if (!str_starts_with($line, '//')) {
                    // add into one line
                    $taskLine .= ' ' . $line;
                }

            }

            $this->extractTaskFromString($taskLine);
            // print ($this->taskText ());

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }


    public function addOption(option $option)
    {
        $this->options->addOption($option);
    }

    public function text4Line(): string
    {
        $OutTxt = "task:"; // . "\r\n";

        $OutTxt .= $this->name; // . "\r\n";
        if ($this->options->count() > 0) {
            $OutTxt .= $this->options->text4Line(); // . "\r\n";
        }

        // -> task: $OutTxt .= " "; // . "\r\n";

        return $OutTxt;
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
