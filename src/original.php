<?php

namespace XXX;


// use \DateTime;

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
            print('*********************************************************' . "\r\n");
            print ("srcFile: " . $srcFile . "\r\n");
            print ("dstFile: " . $dstFile . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            $this->srcFile = $srcFile;
            $this->dstFile = $dstFile;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit __construct: ' . $hasError . "\r\n");
    }

    /*--------------------------------------------------------------------
    funYYY
    --------------------------------------------------------------------*/

    function funYYY($zzz = "")
    {
        $hasError = 0;

        try {
            print('*********************************************************' . "\r\n");
            print('funYYY' . "\r\n");
            print ("zzz: " . $zzz . "\r\n");
            print('---------------------------------------------------------' . "\r\n");
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit funYYY: ' . $hasError . "\r\n");

        return $hasError;
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- XXX ---" . "\r\n";


        $OutTxt .= "Not defined yet " . "\r\n";

        /**
         * $OutTxt .= "fileName: " . $this->fileName . "\r\n";
         * $OutTxt .= "fileExtension: " . $this->fileExtension . "\r\n";
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . "\r\n";
         * $OutTxt .= "filePath: " . $this->filePath . "\r\n";
         * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . "\r\n";
         * /**/

        return $OutTxt;
    }


} // XXX

