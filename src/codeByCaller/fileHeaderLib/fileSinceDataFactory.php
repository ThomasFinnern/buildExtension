<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;


use Finnern\BuildExtension\src\codeByCaller\fileSinceLib\fileSinceData_JG;
use Finnern\BuildExtension\src\codeByCaller\fileSinceLib\fileSinceData_L4D;
use Finnern\BuildExtension\src\codeByCaller\fileSinceLib\fileSinceData_RSG2;
use Finnern\BuildExtension\src\codeByCaller\fileSinceLib\fileSinceDataBase;

class fileSinceDataFactory
{
    /**
     *
     * @param string $callerProjectId
     * @return fileSinceDataBase
     */
    public static function oSinceFileData(string $callerProjectId): fileSinceDataBase
    {
        print ("fileSinceDataFactory:callerProjectId: " . $callerProjectId . PHP_EOL);

        switch (strtolower($callerProjectId)) {
            // rsgallery2
            case strtolower("RSG2"):
                return new fileSinceData_RSG2 ();
            // joomgallery
            case strtolower("JG"):
                return new fileSinceData_JG ();
            // lang4dev
            case strtolower("L4D"):
                return new fileSinceData_L4D ();
            default:
                throw new \Exception("Invalid caller project id");
        }
    }

}