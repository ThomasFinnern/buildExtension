<?php

namespace Finnern\BuildExtension\src\fileNamesLib;

// use DateTime;


/* ToDo: functions
hasExtension
nameMatchesRegEx
pathMatches regex

text ();
/**/


/*================================================================================
Class fithFolderName
================================================================================*/

use Exception;

class fithFolderName
{

    // given name
    public $srcSpecifiedName = "";
    // realpath
    public $srcPathFolderName = "";

    // folder name part
    public $folderName = "";
    // file name part
    public $folderPath = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcFolder = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcFolder: " . $srcFolder . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->extractNameParts($srcFolder);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    extractNameParts
    --------------------------------------------------------------------*/

    function extractNameParts($srcFolder = "")
    {
        $hasError = 0;

        try {
//            print('*********************************************************' . PHP_EOL);
//            print('extractNameParts' . PHP_EOL);
//            print("srcSpecifiedName: " . $srcFolder . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);
//            print("Collect folder: " . $srcFolder . PHP_EOL);

            $this->clear();

            $this->srcSpecifiedName = $srcFolder;

            $this->srcPathFolderName = realpath($srcFolder);

            //$path_parts = pathinfo($srcFolder);
            $path_parts = pathinfo($this->srcPathFolderName);

            $this->folderName = $path_parts['basename'];
            $this->folderPath = $path_parts['dirname'];
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

//        print('exit extractNameParts: ' . $hasError . PHP_EOL);
        return $hasError;
    }

    /*--------------------------------------------------------------------
    clear: init to empty
    --------------------------------------------------------------------*/

    function clear()
    {
        $this->srcSpecifiedName = "";
        $this->srcPathFolderName = "";

        // file name part
        $this->folderName = "";
        $this->folderPath = "";

        return;
    }

    public function text(): string
    {
        $OutTxt = "";
        $OutTxt .= "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- fithFolderName ---" . PHP_EOL;

        $OutTxt .= "srcSpecifiedName: " . $this->srcSpecifiedName . PHP_EOL;
        $OutTxt .= "folderName: " . $this->folderName . PHP_EOL;
        $OutTxt .= "folderPath: " . $this->folderPath . PHP_EOL;
        $OutTxt .= "srcPathFolderName: " . $this->srcPathFolderName . PHP_EOL;

        return $OutTxt;
    }

} // fithFolderName
