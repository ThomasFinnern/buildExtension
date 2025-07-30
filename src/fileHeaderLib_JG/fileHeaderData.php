<?php

namespace Finnern\BuildExtension\src\fileHeaderLib_JG;

use Finnern\BuildExtension\src\fileHeaderLib_JG\copyrightText;
use Exception;

/*================================================================================
Class fileHeader data
================================================================================*/

// expected header
// < ? p h p
//**
//******************************************************************************************
//**   @package    com_joomgallery                                                        **
//**   @author     JoomGallery::ProjectTeam <team@joomgalleryfriends.net>                 **
//**   @copyright  2008 - 2025  JoomGallery::ProjectTeam                                  **
//**   @license    GNU General Public License version 3 or later                          **
//*****************************************************************************************/

/**
 * keeps all variables of a PHP package description header
 * function headerText: Expected result. can be inserted/replace into php code file
 *    returns a set of header lines.
 * function extractHeaderValuesFromLines:
 *    To exchange parts of the header lines they may be extracted here.
 * The variables are global so you may read a file header, change data like actual year,
 * create the lines again here and replace the original file part
 */
class fileHeaderData
{
    const PACKAGE = "JoomGallery";
    const SUBPACKAGE = "com_joomgallery";

    const LICENSE = "GNU General Public License version 3 or later";
    //$this->license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
    const AUTHOR = "JoomGallery::ProjectTeam <team@joomgalleryfriends.net>";
    const LINK = "https://www.joomgalleryfriends.net";

    //
    public string $package; // = "JoomGallery";
    //
    public string $subpackage; // = "com_joomgallery";

    // copyright
    // " * @copyright  2008 - 2025  JoomGallery::ProjectTeam
    public copyrightText $copyright;

//    public string $yearToday = "????";

    //public string $license = "GNU General Public License version 3 or later";
    public string $license = "GNU General Public License version 3 or later";
    //public string $license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";

    //
    public string $author = ""; // "JoomGallery::ProjectTeam <team@joomgalleryfriends.net>";

    //
    public string $link = ""; // https://www.joomgalleryfriends.net";

    //
    // public string $addition = "";

    //
    // public string $since = ""; see constManifest.php

    //
    // public string $version = ""; see constManifest.php

    /**
     * @var string array
     */
    public $additionalLines = [];

    // adjust length of 'name' before value
    private int $middlePadCount = 18; // By 'subpackage' name length
    private int $endPadCount = 88; // xxx [spaces] **
    // private int $middlePadCountCopyright = 15; // By 'subpackage' name length


    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct()
    {
        $this->init();
    }

    public function init() : void
    {
//        $date_format = 'Y';
//        $this->yearToday = date($date_format);

        $this->package = self::PACKAGE;
        $this->subpackage = self::SUBPACKAGE;

        $this->license = self::LICENSE;
        $this->author = self::AUTHOR;
        $this->link = self::LINK;

        $this->copyright = new copyrightText();
    }

    /*--------------------------------------------------------------------
    extractNameFromHeaderLine
    --------------------------------------------------------------------*/

    function extractHeaderValuesFromLines(array $headerLines = [])
    {
        $hasError = 0;

        $this->additionalLines = [];

        try {
            $this->init();

            print('*********************************************************' . "\r\n");
            print('extractHeaderValuesFromLines' . "\r\n");
            print ("header lines in: " . count($headerLines) . "\r\n");
            print('---------------------------------------------------------' . "\r\n");

            foreach ($headerLines as $line) {
                [$name, $behind] = $this->extractNameFromHeaderLine($line);

                if (!empty ($name)) {
                    if ($name == 'copyright') {
//                        // extract dates from line
//                        [$this->sinceCopyrightDate, $this->actCopyrightDate] =
//                            $this->scan4CopyrightHeaderInLine($line);
                        $this->copyright = new copyrightText($line);
                    } else {
                        $value = $this->scan4HeaderValueInLine($name, $line);

                        switch ($name) {
                            case strtolower('package'):
                                $this->package = $value;
                                break;
                            case strtolower('subpackage'):
                                $this->subpackage = $value;
                                break;
                            case strtolower('license'):
                                $this->license = $value;
                                break;
                            case strtolower('author'):
                                $this->author = $value;
                                break;
                            case strtolower('link'):
                                $this->link = $value;
                                break;

                            default:
                                if (trim($line) != '') {
                                    $this->additionalLines [] = $line;
                                }
                                break;
                        }
                    }
                } else {
                    if (trim($line) != '') {
                        $this->additionalLines [] = $line;
                    }
                }

            } // for lines n section

//            // ToDo: Write to log file with actual name
//            print ('!!! additional header line found: "' . $name . '" !!!' . "\r\n");
//            if (count ($this-> additional Lines)) {
//
//            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        print('exit extractHeaderValuesFromLines: ' . $hasError . "\r\n");

        return $hasError;
    }

    /*--------------------------------------------------------------------
    extractHeaderValuesFromLines
    --------------------------------------------------------------------*/

    // '(c)' of copyright will be ignored here
    private function extractNameFromHeaderLine(string $line) : array
    {
        $name = '';
        $behind = '';

        //  * @copyright  2008 - 2025  JoomGallery::ProjectTeam
        $atIdx = strpos($line, '@');
        if (!empty($atIdx)) {
            $blankIdx = strpos($line, ' ', $atIdx + 1);

            $name = substr($line, $atIdx + 1, $blankIdx - $atIdx - 1);
            $name = trim($name);
            $behind = substr($line, $blankIdx + 1);
            $behind = trim($behind);
        }

        return [$name, $behind];
    }

    public function scan4HeaderValueInLine(string $name, string $line): string
    {
        $value = '';

        $idx = strpos($line, '@' . $name);
        if ($idx !== false) {
            $idx += 1 + strlen($name);

            $value = trim(substr($line, $idx));
        }

        return $value;
    }

    public function text(): string
    {
        $OutTxt = "";
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- fileHeader ---" . "\r\n";

        $OutTxt .= "/**" . "\r\n";
        $OutTxt .= "******************************************************************************************" . "\r\n";

//        $OutTxt .= $this->headerFormat('package', $this->package);
//        $OutTxt .= $this->headerFormat('subpackage', $this->subpackage);
        $OutTxt .= $this->headerFormat('package', $this->subpackage);
//        $OutTxt .= $this->headerFormat('subpackage', $this->package);
        $OutTxt .= $this->headerFormat('author', $this->author);
        $OutTxt .= $this->headerFormatCopyright();
        $OutTxt .= $this->headerFormat('license', $this->license);

        $OutTxt .= "*****************************************************************************************/" . "\r\n";

        return $OutTxt;
    }

    public function headerLines(): array
    {
        $outLines = [];

        try {
            $outLines[] = "/**" . "\r\n";

//            $outLines[] = $this->headerFormat('package', $this->package);
//            $outLines[] = $this->headerFormat('subpackage', $this->subpackage);
            $outLines[] = $this->headerFormat('package', $this->subpackage);
//            $outLines[] = $this->headerFormat('subpackage', $this->package);
            $outLines[] = $this->headerFormat('author', $this->author);
            $outLines[] = $this->headerFormatCopyright();
            $outLines[] = $this->headerFormat('license', $this->license);

            $outLines[] = " */" . "\r\n";

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }

        return $outLines;
    }

    public function headerFormat($name, $value): string // , int $middlePadCount
    {
        // copyright begins earlier
        $middlePadCount = $this->middlePadCount;
        $endPadCount    = $this->endPadCount;

        $headerLine = str_pad("**   @" . $name, $middlePadCount, " ", STR_PAD_RIGHT);
        $headerLine .= $value;
        $headerLine = str_pad($headerLine, $endPadCount, " ", STR_PAD_RIGHT) . '**';

        $headerLine = rtrim($headerLine) . "\r\n";

        return $headerLine;
    }

    public function headerFormatCopyright(): string // , int $middlePadCount
    {
        // copyright begins earlier
//        $middlePadCount = $this->middlePadCount;
//
//        $headerLine = str_pad("**   @" . $this->copyrightPreHeader, $middlePadCount, " ", STR_PAD_RIGHT);
//        $headerLine .= $sinceCopyrightDate . '-' . $actCopyrightDate;
//        $headerLine .= ' ' . $this->postCopyrightAuthor;

        $headerLine = $this->copyright->formatCopyrightPhp($this->middlePadCount, $this->endPadCount);
        $headerLine = rtrim($headerLine) . "\r\n";

        return $headerLine;
    }

    public function isDifferent(fileHeaderData $fileHeaderExtern): bool
    {
        $headerLocal = $this->headerText();
        $headerExtern = $fileHeaderExtern->headerText();

        return $headerLocal !== $headerExtern;
    }

    public function isDifferentByString(string $externHeaderAsString): bool
    {
        $headerLocal = $this->headerText();
        $headerExtern = $externHeaderAsString;

        return $headerLocal !== $headerExtern;
    }

    public function headerText() : string
    {
        $OutTxt = "";
        $OutTxt .= "/**" . "\r\n";
        $OutTxt .= "******************************************************************************************" . "\r\n";

//        $OutTxt .= $this->headerFormat('package', $this->package);
//        $OutTxt .= $this->headerFormat('subpackage', $this->subpackage);
        $OutTxt .= $this->headerFormat('package', $this->subpackage);
//        $OutTxt .= $this->headerFormat('subpackage', $this->package);
        $OutTxt .= $this->headerFormat('author', $this->author);
        $OutTxt .= $this->headerFormatCopyright();
        $OutTxt .= $this->headerFormat('license', $this->license);

//       $OutTxt .= $this->headerFormat('link', $this->link);

        $OutTxt .= "*****************************************************************************************/" . "\r\n";

        return $OutTxt;
    }

} // fileHeader
