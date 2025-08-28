<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

use Exception;
use Finnern\BuildExtension\src\codeByCaller\fileManifestLib\copyrightTextFactory;
use Finnern\BuildExtension\src\fileManifestLib\copyrightText;

// ToDo: make copyright local

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
class fileHeaderData_L4D extends FileHeaderDataBase
    implements fileHeaderDataInterface
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
    public copyrightText $copyright;

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
    protected int $middlePadCount = 20; // By 'subpackage' name length
    // private int $padCountCopyright = 15; // By 'subpackage' name length

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct()
    {
        parent::__construct();

        print ("->fileHeaderData_L4D: " . PHP_EOL);


        // lang4dev copyright handling
        $this->oCopyright = copyrightTextFactory::oCopyrightText('L4D');
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

    public function extractHeaderValuesFromLines(array $headerLines = [])
    {
        $hasError = 0;

        $this->additionalLines = [];

        try {
            $this->init();

            print('*********************************************************' . PHP_EOL);
            print('extractHeaderValuesFromLines' . PHP_EOL);
            print ("header lines in: " . count($headerLines) . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

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
//            print ('!!! additional header line found: "' . $name . '" !!!' . PHP_EOL);
//            if (count ($this-> additional Lines)) {
//
//            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit extractHeaderValuesFromLines: ' . $hasError . PHP_EOL);

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
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- fileHeader ---" . PHP_EOL;

        $OutTxt .= "/**" . PHP_EOL;

        $OutTxt .= $this->headerFormat('package', $this->package);
        $OutTxt .= $this->headerFormat('subpackage', $this->subpackage);
        $OutTxt .= $this->headerFormat('author', $this->author);
        $OutTxt .= $this->headerFormatCopyright();
        $OutTxt .= $this->headerFormat('license', $this->license);

        $OutTxt .= " */" . PHP_EOL;

        return $OutTxt;
    }

    public function headerLines(): array
    {
        $outLines = [];

        try {
            $outLines[] = "/**" . PHP_EOL;

            $outLines[] = $this->headerFormat('package', $this->package);
            $outLines[] = $this->headerFormat('subpackage', $this->subpackage);
            $outLines[] = $this->headerFormat('author', $this->author);
            $outLines[] = $this->headerFormatCopyright();
            $outLines[] = $this->headerFormat('license', $this->license);

            $outLines[] = " */" . PHP_EOL;

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }

        return $outLines;
    }

    public function headerFormat($name, $value): string // , int $padCount
    {
        // copyright begins earlier
        $padCount = $this->middlePadCount;

        $headerLine = str_pad(" * @" . $name, $padCount, " ", STR_PAD_RIGHT);
        $headerLine .= $value;

        $headerLine = rtrim($headerLine) . PHP_EOL;

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

        $headerLine = $this->copyright->formatCopyrightPhp($this->middlePadCount, $this->endPadCount);
        $headerLine = rtrim($headerLine) . PHP_EOL;

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
        $OutTxt .= "/**" . PHP_EOL;

        $OutTxt .= $this->headerFormat('package', $this->package);
        $OutTxt .= $this->headerFormat('subpackage', $this->subpackage);
        $OutTxt .= $this->headerFormat('author', $this->author);
        $OutTxt .= $this->headerFormatCopyright();
        $OutTxt .= $this->headerFormat('license', $this->license);

//       $OutTxt .= $this->headerFormat('link', $this->link);

        $OutTxt .= " */" . PHP_EOL;

        return $OutTxt;
    }

} // fileHeader
