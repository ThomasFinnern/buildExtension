<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileManifestLib;

/**
 * container for inner copyright line like "(c) 2005-2024 RSGallery2 Team"
 * used in
 * manifest file:
 *    <copyright>(c) 2005-2024 RSGallery2 Team</copyright>
 * *.php
 *    @copyright   (c) 2003-2024 RSGallery2 Team
 */
class copyrightTextBase
    implements copyrightTextInterface
{

    const COPYRIGHT_PRE_MANIFEST_FILE = "(c)";
    const COPYRIGHT_PRE_PHP_FILE = "copyright  (c)";
    // 2019 start of J!4 version
    const SINCE_COPYRIGHT_DATE = "2019";

    const POST_COPYRIGHT_AUTHOR  = "RSGallery2 Team";


    public string $copyrightPrePhp; // = "copyright  (c)" | "(c)";
    public string $copyrightPreManifest; // "(c)";
    public string $actCopyrightDate; // = "2024";
    public string $sinceCopyrightDate; // = "2019";
    private string $postCopyrightAuthor; // = "RSGallery2 Team";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....
    public function __construct($copyrightText = "") {

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
        $this->copyrightPreManifest = self::COPYRIGHT_PRE_MANIFEST_FILE;
    }

//    function useActual4SinceDate () {
//
//        $this->sinceCopyrightDate  = $this->actCopyrightDate;
//
//    }

    public function setActCopyright2Today ()  : void {

        // $date_format        = 'Ymd';
        $date_format = 'Y';
        $yearToday = date($date_format);

        $this->actCopyrightDate = $yearToday;

    }

    public function setSinceCopyright2Today ()  : void {

        // $date_format        = 'Ymd';
        $date_format = 'Y';
        $yearToday = date($date_format);

        $this->sinceCopyrightDate = $yearToday;

    }


    public function setActCopyright (string $year)  : void {

        $this->actCopyrightDate = $year;

    }

    public function setSinceCopyright (string $year) : void {

        $this->actCopyrightDate = $year;

    }

    public function scan4CopyrightInLine(string $line) : array
    {
        // ToDo: try, catch

        // fall back, preset result

        $this->init();

        //  **   @copyright  2008 - 2025  JoomGallery::ProjectTeam
        $idx = stripos($line, '@copyright');
        if ($idx !== false) {
            //$valuePart = trim(substr($line, $idx));
            // preg_match_all('/\d+/', $valuePart, $matches);
            preg_match_all('/\d+/', $line, $matches);

            $finds = $matches [0];
            if (count ($finds) > 1)
            {
                $this->sinceCopyrightDate = $finds[0];
                $this->actCopyrightDate = $finds[1];

                // author from line is last part
                $pieces = explode($this->actCopyrightDate, $line);

                // New keep default post author
                // Old extract and use again. See following lines
                //$count = count($pieces);
                //if ($count > 0) {
                //
                //    $outer = trim($pieces[$count-1]);
                //    $postCopyrightAuthor = trim(substr($outer, 0, strrpos($outer, ' ')));
                //
                //    // if found
                //    if ($postCopyrightAuthor != '')
                //    {
                //        $this->postCopyrightAuthor = $postCopyrightAuthor;
                //    }
                //}

            }
        } else {

            print ('!!! Unexpected copyright line: "' . $line . '" !!!');
            throw new \Exception('!!! Unexpected copyright line: "' . $line . '" !!!');
        }

        return [$this->sinceCopyrightDate, $this->actCopyrightDate];
        // return [$this->actCopyrightDate, $this->sinceCopyrightDate];
    }


    //  = "(c)";
    // = "copyright  (c)";
    public function formatCopyrightPhp($middlePadCount, $endPadCount, $sinceCopyrightDate='', $actCopyrightDate=''): string // , int $padCount
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

        $copyrightLine = str_pad(" * @" . $this->copyrightPrePhp, $middlePadCount, " ", STR_PAD_RIGHT);
        $copyrightLine .= $sinceCopyrightDate . '-' . $actCopyrightDate;
        $copyrightLine .= ' ' . $this->postCopyrightAuthor;

        return rtrim($copyrightLine);
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
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- copyrightText ---" . PHP_EOL;


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


}