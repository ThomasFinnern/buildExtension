<?php

//namespace Finnern\buildExtension\VersionId;
//namespace Finnern\BuildExtension;
namespace VersionId;

use option\option;

/**
 * Sem-Version with additional build number
 *
 */
class versionId {

    //
    public string $inVersionId='';
    public string $outVersionId='';

    //--- tasks ---------------------------------

    public bool $isIncreaseMajor = false;
    public bool $isIncreaseMinor = false;
    public bool $isIncreasePatch = false; // release
    public bool $isIncreaseBuild = false;

    public string $forceVersionId;
    public bool $isForceVersion = false;

    // release will increase minor and reset revision and build counter
    public bool $isBuildRelease = false;

    // fix will increase revision and reset build counter
    public bool $isBuildFix = false;

    // release will increase minor and reset revision and build counter
    public bool $isDevelop = false;

    // ToDo: Semantic $isRc  $isAlpha, $isBeta : pre release number for RC, Alpha ...
    //  1.0.0-alpha.1 1.0.0-beta.11 1.0.0-rc.1

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....
    public function __construct($versionId = "") {

        $this->inVersionId = $versionId;
        $this->outVersionId = $versionId;

    }

    public function init() : void
    {
        // on read of line

        $this->inVersionId = '';
        $this->outVersionId = '';
    }


    public function setFlags(bool $isIncreaseMajor = false,
        bool $isIncreaseMinor = false,
        bool $isIncreasePatch = false, // release
        bool $isIncreaseBuild = false) : void
    {
        $this->isIncreaseMajor = $isIncreaseMajor;
        $this->isIncreaseMinor = $isIncreaseMinor;
        $this->isIncreasePatch = $isIncreasePatch; // release
        $this->isIncreaseBuild = $isIncreaseBuild;
    }

    public static function id_2_numbers ($versionId = ' . . . ') //: [number,number, number, number]
    {
        $major = 0;
        $minor = 0;
        $patch = 0;
        $build = 0;

        $parts = explode('.', $versionId);

        $count = count($parts);

        if ($count > 0) { $major = intval($parts[0]); }
        if ($count > 1) { $minor = intval($parts[1]); }
        if ($count > 2) { $patch = intval($parts[2]); }
        if ($count > 3) { $build = intval($parts[3]); }

        return [$major, $minor, $patch, $build];
    }

    public static function numbers_2_id ($major=0, $minor=0, $patch=0, $build=0) : string
    {
        $versionId = strval($major) . '.' . strval($minor) . '.' . strval($patch) . '.' . strval($build);

        return $versionId;
    }

    // leave out build
    public static function numbers_2_id_release ($major=0, $minor=0, $patch=0, $build=0) : string
    {
        $versionId = strval($major) . '.' . strval($minor) . '.' . strval($patch);

        return $versionId;
    }


    public function update() : string {

        // force
        if ($this->isForceVersion) {

            $this->outVersionId = $this->forceVersionId;

        } else {

            [$major, $minor, $patch, $build] = self::id_2_numbers($this->inVersionId);

            // pre flags

            // increase revision and reset build counter
            if ($this->isBuildFix) {
                $patch++;
                $build = 0;
                $this->outVersionId = self::numbers_2_id($major, $minor, $patch, $build);
                print ("isBuildFix (patch++): " . $this->outVersionId) . "\r\n";
            }
            else
            {
                // increase minor and reset revision and build counter
                if ($this->isBuildRelease) {
                    $minor++;
                    $patch = 0;
                    $build = 0;
                    $this->outVersionId = self::numbers_2_id_release($major, $minor, $patch, $build);
                    print ("isBuildRelease (minor++): " . $this->outVersionId) . "\r\n";
                }
                else {

                    // --- increase version by flags 

					// 0.0.0.x will be increased. 
					// Use for developer steps when install with 
					// different version ID is necessary
                    if ($this->isIncreaseBuild) {
                        $build ++;
                        print ("isIncreaseBuild (build++): " . $build . " ");
                    }

					// 0.0.x.0 will be increased. Lower parts will be reset to zero 	
					// Use for fixes or slightly improved functions
					// /isIncreasePatch=true
                    if ($this->isIncreasePatch) {
                        $patch ++;
                        $build = 0;
                        print ("isIncreasePatch (patch++): " . $patch . " ");
                    }
					
					
					// 0.x.0 will be increased. Lower parts will be reset to zero
					// Use for releases					
                    if ($this->isIncreaseMinor) {
                        $minor ++;
                        $patch = 0;
                        $build = 0;
                        print ("isIncreaseMinor (patch++): " . $minor . " ");
                    }

					// x.0.0 will be increased. Lower parts will be reset to zero
					// Use for incompatible releases
                    if ($this->isIncreaseMajor) {
                        $major ++ ;
                        $minor = 0;
                        $patch = 0;
                        $build = 0;
                        print ("isIncreaseMajor (major++): " . $major . " ");
                    }

                    $this->outVersionId = self::numbers_2_id($major, $minor, $patch, $build);
                    print ("increased Version: " . $this->outVersionId) . "\r\n";
                }
            }

        }

        return $this->outVersionId;
    }

    // Task name with options
    public function assignVersionOption(option $option): bool
    {
        $isVersionOption = false;

        switch (strtolower($option->name)) {
            //--- Version flags -------------------------------------------------------------

            case 'forceversion':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->forceVersionId = $option->value;
                $this->isForceVersion = true;
                $isVersionOption  = true;
                break;

            case 'isincreasemajor':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->isIncreaseMajor = true;
                $isVersionOption  = true;
                break;

            case 'isincreaseminor':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->isIncreaseMinor = true;
                $isVersionOption  = true;
                break;

            case 'isincreasepatch':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->isIncreasePatch = true;
                $isVersionOption  = true;
                break;

            case 'isincreasebuild':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->isIncreaseBuild = true;
                $isVersionOption  = true;
                break;

            case 'isbuildrelease':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->isBuildRelease = $option->value;
                $isVersionOption  = true;
                break;

            case 'isbuildfix':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->isBuildFix = $option->value;
                $isVersionOption  = true;
                break;

//				case 'X':
//					print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//					break;

        } // switch

        return $isVersionOption;
    }

    public function scan4VersionIdInLine(string $line) : array
    {
        // ToDo: try, catch

        $this->init();

        // ....<version>5.0.12.5</version>
        if (str_contains($line, '<version>')) {

//            // ? use preg_match ...
//            $inVersionId = preg_replace(
//                '/.*<version>(.*)<\/version>.*/',
//                '${1}',
//                trim($line),
//            );
//
//            $this->inVersionId = $inVersionId;

            // <element>value</element> contains -> standard form

            $idxStart = strpos($line, '>');

            if ($idxStart !== false) {
                $idxEnd = strpos($line, '<', $idxStart + 1);
                if ($idxEnd !== false) {
                    $inVersionId = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
                }

                $this->inVersionId = $inVersionId;
            }

        } else {

            print ('!!! Unexpected <version> line: "' . $line . '" !!!');
            throw new \Exception('!!! Unexpected <version> line: "' . $line . '" !!!');
        }

        return [$this->inVersionId];
    }

    public function formatVersionIdManifest($outVersionId=''): string
    {
        // ToDo: try, catch

        //--- data source --------------------------------

        // from extern or intern
        if (empty($sinceCopyrightDate)) {
            $outVersionId = $this->outVersionId;
        } else {
            $this->outVersionId = $outVersionId;
        }

        //--- format text --------------------------------

        // ....<version>5.0.12.5</version>

        $versionIdLine = '    '
            . '<version>'
            . $outVersionId
            . '</version>';

        return $versionIdLine;
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- versionId ---" . "\r\n";


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