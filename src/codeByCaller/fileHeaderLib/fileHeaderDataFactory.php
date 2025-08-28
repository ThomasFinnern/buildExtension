<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;


class fileHeaderDataFactory
{
    /**
     *
     * @param string $callerProjectId
     * @return fileHeaderDataBase
     */
    public static function oFileHeaderData(string $callerProjectId): fileHeaderDataBase
    {
        print ("fileHeaderDataFactory:callerProjectId: " . $callerProjectId . PHP_EOL);

        switch (strtolower($callerProjectId)) {
            // rsgallery2
            case strtolower("RSG2"):
                return new fileHeaderData_RSG2 ();
            // joomgallery
            case strtolower("JG"):
                return new fileHeaderData_JG ();
            // lang4dev
            case strtolower("L4D"):
                return new fileHeaderData_L4D ();
            default:
                throw new \Exception("Invalid caller project id");
        }

    }

}