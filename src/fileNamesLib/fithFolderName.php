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
//            print('*********************************************************' . "\r\n");
//            print ("srcFolder: " . $srcFolder . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            $this->extractNameParts($srcFolder);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    /*--------------------------------------------------------------------
    extractNameParts
    --------------------------------------------------------------------*/

    function extractNameParts($srcFolder = "")
    {
        $hasError = 0;

        try {
//            print('*********************************************************' . "\r\n");
//            print('extractNameParts' . "\r\n");
//            print("srcSpecifiedName: " . $srcFolder . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");
//            print("Collect folder: " . $srcFolder . "\r\n");

            $this->clear();

            $this->srcSpecifiedName = $srcFolder;

            $this->srcPathFolderName = realpath($srcFolder);

            //$path_parts = pathinfo($srcFolder);
            $path_parts = pathinfo($this->srcPathFolderName);

            $this->folderName = $path_parts['basename'];
            $this->folderPath = $path_parts['dirname'];
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

//        print('exit extractNameParts: ' . $hasError . "\r\n");
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
        $OutTxt .= "------------------------------------------" . "\r\n";
        $OutTxt .= "--- fithFolderName ---" . "\r\n";

        $OutTxt .= "srcSpecifiedName: " . $this->srcSpecifiedName . "\r\n";
        $OutTxt .= "folderName: " . $this->folderName . "\r\n";
        $OutTxt .= "folderPath: " . $this->folderPath . "\r\n";
        $OutTxt .= "srcPathFolderName: " . $this->srcPathFolderName . "\r\n";

        return $OutTxt;
    }

} // fithFolderName
