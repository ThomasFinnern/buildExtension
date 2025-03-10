<?php

namespace Finnern\BuildExtension\src\tasksLib;

// use DateTime;

use Exception;
use Finnern\BuildExtension\src\tasksLib\option;


/*================================================================================
Class options
================================================================================*/

class options
{

    /**
     * @var option[] $options
     */
    public $options;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($options = [])
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . "\r\n");
//            print ("count options: " . count ($options) . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            $this->options = $options;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
//        // print('exit __construct: ' . $hasError . "\r\n");
    }

    public function count(): int
    {
        return (count($this->options));
    }

    public function getOption(string $name, bool $isIgnoreCase = false): string
    {
        $value = '';

        foreach ($this->options as $option) {
            $isFound = false;

            if ($isIgnoreCase) {
                $isFound = strtolower($option->name) === strtolower($name);
            } else {
                $isFound = $option->name === $name;
            }

            if ($isFound) {
                $value = $option->value;
            }
        }

        return ($value);
    }

    public function extractOptionsFromString($inOptionsString = ""): options
    {
        $this->clear();

        try {
            $optionsString = Trim($inOptionsString);

            // multiple: /optionName or /optionName=value or /optionName="optionValue"
            while ($this->hasOptionChar($optionsString)) {
                //--- extract next option -------------------------------

                // first find '=' then check for '"' .
                $idxEqual = strpos($optionsString, "=");
                $idxEnd = strpos($optionsString, " ");

                // last option in string
                if ($idxEnd == false) {
                    $singleOption = $optionsString;

                    // No more option parts
                    $optionsString = '';
                } else {
                    //--- separate next option in string ----------------------

                    // Equal char found before end
                    // -> has value part
                    // -> check for end  '"'
                    if ($idxEqual && $idxEqual < $idxEnd) {
                        // check for '"' to adjust end index
                        //$idxBracket = strpos($optionsString, '"');
                        $idxBracket = $idxEqual + 1;

                        // option value enclosed in brackets ?
                        if ($optionsString[$idxBracket] == '"') {
                            // If found, find second one
                            $idxEnd = strpos($optionsString, '"', $idxBracket + 1);
                        }
                    }

                    // this option string part
                    $singleOption = substr($optionsString, 0, $idxEnd + 1);

                    // further options string part
                    $optionsString = substr($optionsString, $idxEnd + 1);
                    $optionsString = Trim($optionsString);
                }

                // extract actual option
                $option = (new option())->extractOptionFromString($singleOption);
                $this->addOption($option);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    public function clear(): void
    {
        $this->options = [];
    }

    /*--------------------------------------------------------------------
    extractOptionsFromString
    --------------------------------------------------------------------*/

    private function hasOptionChar(string $inOptionsString)
    {
        $isOption = false;

        $optionsString = Trim($inOptionsString);

        // /option1 /option2=xxx /option3="01teststring"
        if (str_starts_with($optionsString, '/')) {
            $isOption = true;
        }

        // -option1 -option2=xxx -option3="01teststring"
        if (str_starts_with($optionsString, '-')) {
            $isOption = true;
        }

        return $isOption;
    }

    public function addOption(option $option): void
    {
        if (!empty ($option->name)) {
            // $this->options [$option->name] = $option;
            $this->options [] = $option;
        }
    }

    public function text(): string
    {
        $OutTxt = "";
        // $OutTxt .= "options" . "\r\n";

        foreach ($this->options as $option) {
            $OutTxt .= "   " . $option->text4Line() . "\r\n";
        }

        return $OutTxt;
    }

    public function text4Line(): string
    {
        $OutTxt = ""; // . "\r\n";

        foreach ($this->options as $option) {
            $OutTxt .= " " . $option->text4Line();
        }

        return $OutTxt;
    }


} // options

