<?php

namespace Finnern\BuildExtension\src;

/*================================================================================
Class XXX
================================================================================*/

use Exception;

class XXX
{

    public string $srcFile = "";
    public string $dstFile = "";


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct($srcFile = "", $dstFile = "")
    {
        $hasError = 0;
        try {
            print('*********************************************************' . PHP_EOL);
            print ("srcFile: " . $srcFile . PHP_EOL);
            print ("dstFile: " . $dstFile . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            $this->srcFile = $srcFile;
            $this->dstFile = $dstFile;
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    funYYY
    --------------------------------------------------------------------*/

    function funYYY($zzz = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . PHP_EOL);
            print('funYYY' . PHP_EOL);
            print ("zzz: " . $zzz . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit funYYY: ' . $hasError . PHP_EOL);

        return $hasError;
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- XXX ---" . PHP_EOL;


        $OutTxt .= "Not defined yet " . PHP_EOL;

        /**
         * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
         * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
         * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;
         * /**/

        return $OutTxt;
    }


} // XXX

