<?php

namespace Finnern\BuildExtension\src\tasksLib;

use Exception;

/*================================================================================
Class option
================================================================================*/

class option
{

    public string $name = "";
    public string $value = "";
    // outer quotation marks like xxx="value" or xxx='value' instead of xxx=value
    public string $quotation = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($name = "", $value = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("name: " . $name . PHP_EOL);
//            print ("value: " . $value . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->name = $name;
            //ToDo: $this->value = $this->assignValue (value); // remove '"' at start and end
            $this->value = $this->removeQuotation($value);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
//        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    private function removeQuotation(string $optionValuePart)
    {
        $optionValue = $optionValuePart;

        if ($optionValue != '') {
            $firstChar = $optionValuePart[0];
            if ($firstChar == '"' or $firstChar == "'") {
                $this->quotation = $firstChar;
                $optionValue = substr($optionValuePart, 1, -1);
            }
        }

        return $optionValue;
    }

    public function extractOptionFromString($inOptionsString = ""): option
    {
        $this->clear();

        try {
            $optionsString = Trim($inOptionsString);

            // single: /optionName or /optionName=value or /optionName="option value with spaces"

            //$optionName = '';
            $optionValue = '';

            $idx = strpos($optionsString, "=");

            // name without options
            if ($idx === false) {
                // Just name
                $optionName = substr($optionsString, 1);
            } else {
                // name with options
                $optionName = substr($optionsString, 1, $idx - 1);


                $optionValuePart = substr($optionsString, $idx + 1);
                $optionValue = $this->removeQuotation($optionValuePart);
            }

            $this->name = $optionName;
            $this->value = $optionValue;
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $this;
    }

    public function clear(): void
    {
        $this->name = '';
        $this->value = '';
        $this->quotation = '';
    }

    /*
     * One line representation
     */
    public function __toString() {
        $optionLine = '/' . $this->name;

        if ($this->value == '' && $this->quotation != '') {
            $optionLine .= "=" . $this->quotation . $this->value . $this->quotation;
        } else {
            if ($this->value != '') {
                if ($this->quotation == '') {
                    $optionLine .= "=" . $this->value;
                } else {
                    $optionLine .= "=" . $this->quotation . $this->value . $this->quotation;
                }
            }
        }

        return $optionLine;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- option ---" . PHP_EOL;

        $OutTxt .= "name: " . $this->name . PHP_EOL;
        // not outer quotation marks like xxx="value" or xxx='value' instead of xxx=value
        if ($this->quotation == '') {
            $OutTxt .= "value: " . "'" . $this->value . "'" . PHP_EOL;
        } else {
            $OutTxt .= "value: " . "'" . $this->quotation . $this->value . $this->quotation . "'" . PHP_EOL;
        }

        return $OutTxt;
    }


} // option

