<?php

namespace Finnern\BuildExtension\src\fileNamesLib;

// use \DateTime;
// use DateTime;


/*================================================================================
Class fithFileName
================================================================================*/

use Exception;

class fithFileName
{

    // given name
    public string $srcSpecifiedName = "";
    // realpath
    public string $srcPathFileName = "";


    // file name part
    public string $fileName = "";
    // file name part
    public string $fileExtension = "";
    // file name part
    // file name part
    public string $fileBaseName = "";
    public string $filePath = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcFile = "")
    {
        //$hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcFile: " . $srcFile . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->extractNameParts($srcFile);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            //$hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    extractNameParts
    --------------------------------------------------------------------*/

    function extractNameParts($srcFile = ""): int
    {
        $hasError = 0;

        try {
//            print('*********************************************************' . PHP_EOL);
//            print('extractNameParts' . PHP_EOL);
//            print("srcSpecifiedName: " . $srcFile . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);
            print("    " . $srcFile . PHP_EOL);

            $this->clear();

            $this->srcSpecifiedName = $srcFile;

            $this->srcPathFileName = realpath($srcFile);

            $path_parts = pathinfo($srcFile);

            $this->fileName = $path_parts['filename'];
            $this->fileExtension = $path_parts['extension'];
            $this->fileBaseName = $path_parts['basename'];
            $this->filePath = $path_parts['dirname'];
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

//        print('exit extractNameParts: ' . $hasError . PHP_EOL);
        return $hasError;
    }

    /*--------------------------------------------------------------------
    compare for same extension
    --------------------------------------------------------------------*/

    function clear()
    {
        $this->srcSpecifiedName = "";
        $this->srcPathFileName = "";

        // file name part
        $this->fileName = "";
        $this->fileExtension = "";
        $this->fileBaseName = "";
        $this->filePath = "";

        return;
    }

    /*--------------------------------------------------------------------
    compare for name matches regex
    --------------------------------------------------------------------*/

    function hasExtension($check = '')
    {
        $hasExtension = false;

        if ($this->fileExtension == $check) {
            $hasExtension = true;
        }

        return $hasExtension;
    }

    /*--------------------------------------------------------------------
    compare for name and extension matches regex
    --------------------------------------------------------------------*/

    function nameMatchesRegEx($regex = '')
    {
        $isMatchesRegex = false;

        if (preg_match($regex, $this->fileName)) {
            $isMatchesRegex = true;
        }

        return $isMatchesRegex;
    }

    /*--------------------------------------------------------------------
    compare for name and extension matches regex
    --------------------------------------------------------------------*/

    function basenameMatchesRegEx($regex = '')
    {
        $isMatchesRegex = false;

        if (preg_match($regex, $this->fileBaseName)) {
            $isMatchesRegex = true;
        }

        return $isMatchesRegex;
    }

    /*--------------------------------------------------------------------
    clear: init to empty
    --------------------------------------------------------------------*/

    function pathMatchesRegex($regex = '')
    {
        $isMatchesRegex = false;

        if (preg_match($regex, $this->filePath)) {
            $isMatchesRegex = true;
        }

        return $isMatchesRegex;
    }

    public function text(): string
    {
        $OutTxt = "";
        $OutTxt .= "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- fithFileName ---" . PHP_EOL;

        $OutTxt .= "srcSpecifiedName: " . $this->srcSpecifiedName . PHP_EOL;
        $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
        $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
        $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
        $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
        $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;

        return $OutTxt;
    }

    public function text_NamePathLine(): string
    {
        $OutTxt = "";
        $OutTxt .= "- " . $this->fileBaseName . " :: " . $this->filePath;

        return $OutTxt;
    }

} // fithFileName

