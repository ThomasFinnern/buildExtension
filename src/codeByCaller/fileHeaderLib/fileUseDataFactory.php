<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;


class fileUseDataFactory
{
    /**
     *
     * @param string $callerProjectId
     * @return fileUseDataBase
     */
    public static function oFileUseData(string $callerProjectId): fileUseDataBase
    {
        switch (strtolower($callerProjectId)) {
            // rsgallery2
            case strtolower("RSG2"):
                return new fileUseData_RSG2 ();
            // joomgallery
            case strtolower("JG"):
                return new fileUseData_JG ();
            // lang4dev
            case strtolower("L4D"):
                return new fileUseData_L4D ();
            default:
                throw new \Exception("Invalid caller project id");
        }

    }

}