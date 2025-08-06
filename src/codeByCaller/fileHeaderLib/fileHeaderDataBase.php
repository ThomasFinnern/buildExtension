<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\codeByCaller\fileManifestLib\copyrightTextBase;
use Finnern\BuildExtension\src\codeByCaller\fileManifestLib\copyrightTextFactory;
use Finnern\BuildExtension\src\fileManifestLib\copyrightText;

// ToDo: make copyright local

// ToDo: can variables $padCount/$endPadCount ... be set from *.tsk file ?

/*================================================================================
Class fileHeader data
================================================================================*/

/**
 * keeps all variables of a PHP package description header
 * function headerText: Expected result. can be inserted/replace into php code file
 *    returns a set of header lines.
 * function extractHeaderValuesFromLines:
 *    To exchange parts of the header lines they may be extracted here.
 * The variables are global so you may read a file header, change data like actual year,
 * create the lines again here and replace the original file part
 */
class fileHeaderDataBase implements fileHeaderDataInterface
{
    const PACKAGE = "RSGallery2";
    const SUBPACKAGE = "com_rsgallery2";

    const LICENSE = "GNU General Public License version 2 or later";
    //$this->license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";
    const AUTHOR = "RSGallery2 Team <team2@rsgallery2.org>";
    const LINK = "https://www.rsgallery2.org";

    //
    public string $package; // = "RSGallery2";
    //
    public string $subpackage; // = "com_rsgallery2";

    // copyright
    // " * @copyright  (c)  2003-2024 RSGallery2 Team"
    public copyrightTextBase|null $oCopyright;

//    public string $yearToday = "????";

    //public string $license = "GNU General Public License version 3 or later";
    public string $license = "GNU General Public License version 2 or later";
    //public string $license = "http://www.gnu.org/copyleft/gpl.html GNU/GPL";

    //
    public string $author = "RSGallery2 Team <team2@rsgallery2.org>";

    //
    public string $link = "https://www.rsgallery2.org";

    //
    // public string $addition = "RSGallery is Free Software";

    //
    // public string $since = ""; see constManifest.php

    //
    // public string $version = ""; see constManifest.php

    /**
     * @var string array
     */
    public $additionalLines = [];

    // adjust length of 'name' before value
    //protected int $padCount = 20; // By 'subpackage' name length
    protected int $padCount = 19; // By 'subpackage' name length
    // private int $padCountCopyright = 15; // By 'subpackage' name length

    protected int $endPadCount = 88; // By 'subpackage' name length
    // private int $padCountCopyright = 15; // By 'subpackage' name length

    public string $callerProjectId = 'RSG2'; // ToDo: create at start or assing before use

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct()
    {
        $this->oCopyright = null;

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

        if ( ! empty($this->oCopyright)) {
            $this->oCopyright->init();
        }

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

                        $callerProjectId = "";
                        $this->oCopyright = $this->oCopyright ?: copyrightTextFactory::oCopyrightText($this->callerProjectId);
                        $this->oCopyright->scan4CopyrightInLine ($line);

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
    public function extractNameFromHeaderLine(string $line) : array
    {
        $name = '';
        $behind = '';

        //  * @copyright (c) 2005-2024 RSGallery2 Team
        //  * @subpackage      com_rsgallery2
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

        $OutTxt .= $this->headerFormat('package', $this->package);
        $OutTxt .= $this->headerFormat('subpackage', $this->subpackage);
        $OutTxt .= $this->headerFormat('author', $this->author);
        $OutTxt .= $this->headerFormatCopyright();
        $OutTxt .= $this->headerFormat('license', $this->license);

        $OutTxt .= " */" . "\r\n";

        return $OutTxt;
    }

    public function headerLines(): array
    {
        $outLines = [];

        try {
            $outLines[] = "/**" . "\r\n";

            $outLines[] = $this->headerFormat('package', $this->package);
            $outLines[] = $this->headerFormat('subpackage', $this->subpackage);
            $outLines[] = $this->headerFormat('author', $this->author);
            $outLines[] = $this->headerFormatCopyright();
            $outLines[] = $this->headerFormat('license', $this->license);

            $outLines[] = " */" . "\r\n";

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }

        return $outLines;
    }

    public function headerFormat($name, $value): string // , int $padCount
    {
        // copyright begins earlier
        $padCount = $this->padCount;

        $headerLine = str_pad(" * @" . $name, $padCount, " ", STR_PAD_RIGHT);
        $headerLine .= $value;

        $headerLine = rtrim($headerLine) . "\r\n";

        return $headerLine;
    }

    public function headerFormatCopyright(): string // , int $padCount
    {
        // copyright begins earlier
//        $padCount = $this->padCount;
//
//        $headerLine = str_pad(" * @" . $this->copyrightPreHeader, $padCount, " ", STR_PAD_RIGHT);
//        $headerLine .= $sinceCopyrightDate . '-' . $actCopyrightDate;
//        $headerLine .= ' ' . $this->postCopyrightAuthor;

        $this->oCopyright = $this->oCopyright ?: copyrightTextFactory::oCopyrightText($this->callerProjectId);

        $headerLine = $this->oCopyright->formatCopyrightPhp($this->padCount, $this->endPadCount);
        $headerLine = rtrim($headerLine) . "\r\n";

        return $headerLine;
    }

    public function isDifferent(fileHeaderDataBase $fileHeaderExtern): bool
    {
        $headerLocal = $this->headerText();
        $headerExtern = $fileHeaderExtern->headerText();

        $isDifferent = $headerLocal !== $headerExtern;

        return $isDifferent;
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

        $OutTxt .= $this->headerFormat('package', $this->package);
        $OutTxt .= $this->headerFormat('subpackage', $this->subpackage);
        $OutTxt .= $this->headerFormat('author', $this->author);
        $OutTxt .= $this->headerFormatCopyright();
        $OutTxt .= $this->headerFormat('license', $this->license);

//       $OutTxt .= $this->headerFormat('link', $this->link);

        $OutTxt .= " */" . "\r\n";

        return $OutTxt;
    }

} // fileHeader
