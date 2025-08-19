<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileManifestLib;


class copyrightTextFactory
{
    /**
     *
     * @param string $callerProjectId
     * @return copyrightTextInterface
     */
    public static function oCopyrightText(string $callerProjectId): copyrightTextBase
    {
        print ("oCopyrightText:callerProjectId: " . $callerProjectId . PHP_EOL);

        switch (strtolower($callerProjectId)) {
            // rsgallery2
            case strtolower("RSG2"):
                return new copyrightText_RSG2 ();
            // joomgallery
            case strtolower("JG"):
                return new copyrightText_JG ();
            // lang4dev
            case strtolower("L4D"):
                return new copyrightText_L4d ();
            default:
                throw new \Exception("Invalid caller project id");
        }

    }

}