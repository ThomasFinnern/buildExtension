<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileManifestLib;

/**
 * container for inner copyright line like "(c) 2005-2024 RSGallery2 Team"
 * used in
 * manifest file:
 *    <copyright>2008 - 2025  JoomGallery::ProjectTeam</copyright>
 * *.php
 *    @copyright   (c) 2003-2024 RSGallery2 Team
 */
class copyrightText_JG extends copyrightTextBase
    implements copyrightTextInterface
{

    //**   @copyright  2008 - 2025  JoomGallery::ProjectTeam                                  **

    // const COPYRIGHT_PRE_MANIFEST_FILE = "(c)";
    const COPYRIGHT_PRE_PHP_FILE = "@copyright  ";
    // 2019 start of J!4 version
    const SINCE_COPYRIGHT_DATE = "2008";

    const POST_COPYRIGHT_AUTHOR  = "JoomGallery::ProjectTeam";


    public string $copyrightPrePhp; // = "@copyright  " | "(c)";
    public string $copyrightPreManifest; // "(c)";
    public string $actCopyrightDate; // = "2024";
    public string $sinceCopyrightDate; // = "2008";
    private string $postCopyrightAuthor; // = "JoomGallery::ProjectTeam";

//    private string $yearToday;


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....
    public function __construct($copyrightText = "") {

        parent::__construct($copyrightText);

        $this->init();

        if (!empty($copyrightText)) {
            $this->scan4CopyrightInLine ($copyrightText);
        }
    }

    public function init() : void
    {
        $this->setActCopyright2Today ();

        // 2019 start of J!4 version
        $this->sinceCopyrightDate = self::SINCE_COPYRIGHT_DATE;
        $this->postCopyrightAuthor = self::POST_COPYRIGHT_AUTHOR;

        $this->copyrightPrePhp = self::COPYRIGHT_PRE_PHP_FILE;
        //$this->copyrightPreManifest = self::COPYRIGHT_PRE_MANIFEST_FILE;
    }



    //  = "(c)";
    // = "copyright  (c)";
    public function formatCopyrightPhp($middlePadCount, $endPadCount,
                                       $sinceCopyrightDate='', $actCopyrightDate=''): string // , int $padCount
    {
        // ToDo: try, catch

        //--- data source --------------------------------

        // from extern or intern
        if (empty($sinceCopyrightDate)) {
            $sinceCopyrightDate = $this->sinceCopyrightDate;
        } else {
            $this->sinceCopyrightDate = $sinceCopyrightDate;
        }

        if (empty($actCopyrightDate)) {
            $actCopyrightDate = $this->actCopyrightDate;
        } else {
            $this->actCopyrightDate = $actCopyrightDate;
        }

        //--- format text --------------------------------

        // copyright begins earlier
        // $padCount = $this->padCount;

        $copyrightLine = str_pad("**   " . $this->copyrightPrePhp, $middlePadCount, " ", STR_PAD_RIGHT);
        $copyrightLine .= $sinceCopyrightDate . ' - ' . $actCopyrightDate;
        $copyrightLine .= '  ' . $this->postCopyrightAuthor;
        $copyrightLine = str_pad($copyrightLine, $endPadCount, " ", STR_PAD_RIGHT) . '**';

        return rtrim($copyrightLine) . "\r\n";
    }

    // ToDo: just since may not exist
    public function formatCopyrightManifest($sinceCopyrightDate='', $actCopyrightDate=''): string
    {
        // ToDo: try, catch

        //--- data source --------------------------------

        // from extern or intern
        if (empty($sinceCopyrightDate)) {
            $sinceCopyrightDate = $this->sinceCopyrightDate;
        } else {
            $this->sinceCopyrightDate = $sinceCopyrightDate;
        }

        if (empty($actCopyrightDate)) {
            $actCopyrightDate = $this->actCopyrightDate;
        } else {
            $this->actCopyrightDate = $actCopyrightDate;
        }

        //--- format text --------------------------------

        $copyrightLine = $this->copyrightPreManifest
            . ' ' . $sinceCopyrightDate . '-' . $actCopyrightDate
            . ' ' . $this->postCopyrightAuthor;

        return rtrim($copyrightLine);
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- copyrightText ---" . "\r\n";


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


}