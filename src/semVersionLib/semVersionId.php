<?php

namespace Finnern\BuildExtension\src\semVersionLib;

use Finnern\BuildExtension\src\tasksLib\option;


/**
 * Sem-Version with additional build number
 *
 * Given a version number MAJOR.MINOR.PATCH, increment the:
 *
 * MAJOR version when you make incompatible API changes
 * MINOR version when you add functionality in a backward compatible manner
 * PATCH version when you make backward compatible bug fixes
 * Additional labels for pre-release and build metadata are available as
 *   extensions to the MAJOR.MINOR.PATCH format.
 *
 * A pre-release version MAY be denoted by appending a hyphen and a
 *   series of dot separated identifiers immediately following the patch version.
 * Examples: 1.0.0-alpha, 1.0.0-alpha.1, 1.0.0-0.3.7, 1.0.0-x.7.z.92, 1.0.0-x-y-z.--.
 *
 * Example: 1.0.0-alpha < 1.0.0-alpha.1 < 1.0.0-alpha.beta < 1.0.0-beta
 *    < 1.0.0-beta.2 < 1.0.0-beta.11 < 1.0.0-rc.1 < 1.0.0.  ???? not supported
 *    From 6.1.0-alpha1 to 6.1.0-alpha2 From 6.0.0-alpha3 to 6.0.0-beta1
 *    6.0.0-beta3 to 6.0.0-rc1 6.1.0-alpha1 to 6.1.0-alpha2
 */

// ToDo: Semantic $isRc  $isAlpha, $isBeta : pre release number for RC, Alpha ...
//  1.0.0-alpha.1 1.0.0-beta.11 1.0.0-rc.1


class semVersionId
{

    //
    public string $inVersionId = '';
    public string $outVersionId = '';

    //--- tasks ---------------------------------

    public bool $isIncreaseMajor = false;
    public bool $isIncreaseMinor = false;
    public bool $isIncreasePatch = false; // release
    public bool $isIncreaseBuild = false;

    public bool $isIncreasePreID = false; // beta-> alpha -> RC -> RC1
    public bool $isIncreasePreNumber = false; // beta1->beta2, alpha1-alpha2, rs1 -> rc2

    public bool $isForcePreID = false; // beta-> alpha -> RC -> RC1
    public bool $isForcePreNumber = false; // beta1->beta2, alpha1-alpha2, rs1 -> rc2
    public bool $isRemovePreID = false; // beta-> alpha -> RC -> RC1

    public bool $isBeautify = false; // read and write back


    public bool $isIncreasePreName = false;  // beta -> alpha -> rc
    public bool $isIncreasePreVersion = false;  // beta.x (not beta.1.2.3)

    public string $forceVersionId;
    public bool $isForceVersion = false;

    // release will increase major and reset release, patch and build counter ((++.->0.->0.->0)
    public bool $isBuildMain = false;

    // release will increase minor and reset patch and build (0.++.->0.->0)
    public bool $isBuildRelease = false;

    // fix will increase revision and reset build counter (0.0.++.->0)
    public bool $isBuildFix = false;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....
    public function __construct($versionId = "")
    {

        $this->inVersionId  = $versionId;
        $this->outVersionId = $versionId;

    }

    public function assignInId($versionId = "")
    {

        $this->inVersionId  = $versionId;
        $this->outVersionId = $versionId;

    }

    public function init(): void
    {
        // on read of line

        $this->inVersionId  = '';
        $this->outVersionId = '';
    }


//    public function setFlags(bool $isIncreaseMajor = false,
//        bool $isIncreaseMinor = false,
//        bool $isIncreasePatch = false, // release
//        bool $isIncreaseBuild = false) : void
//    {
//        $this->isIncreaseMajor = $isIncreaseMajor;
//        $this->isIncreaseMinor = $isIncreaseMinor;
//        $this->isIncreasePatch = $isIncreasePatch; // release
//        $this->isIncreaseBuild = $isIncreaseBuild;
//
//        // ... ToDo:
//    }

    public static function id_2_parts($versionId = ' . . . ') //: [number,number, number, number]
    {
        $major = 0;
        $minor = 0;
        $patch = 0;
        $build = 0;

        $preId     = '';
        $preNumber = 0;


        $parts = explode('-', $versionId);
        if (count($parts) > 0)
        {
            $partsVersion = explode('.', $parts[0]);

            $count = count($partsVersion);

            if ($count > 0)
            {
                $major = intval($partsVersion[0]);
            }
            if ($count > 1)
            {
                $minor = intval($partsVersion[1]);
            }
            if ($count > 2)
            {
                $patch = intval($partsVersion[2]);
            }
            if ($count > 3)
            {
                $build = intval($partsVersion[3]);
            }

            // ... ToDo: alpha, beta ...
            if (count($parts) > 1)
            {
                // Attention: Complicated pre version ids are not supported Example: "RC1ansfurtherAlphaNumeric..."

                $partsPre = $parts[1];
                //
                if (preg_match('/[a-z]+/i', $partsPre, $match))
                {
                    $preId = $match[0];
                }
//                if(preg_match('/[a-z]+([0-9]+)/i', $partsPre, $match))
                if (preg_match('/[0-9]+/', $parts[1], $match))
                {
                    $preNumber = intval($match[0]);
                }

            }
        }

        return [$major, $minor, $patch, $build, $preId, $preNumber];
    }

    public static function parts_2_id($major = 0, $minor = 0, $patch = 0, $build = 0, $preId = '', $preNumber = 0): string
    {
        // x.x.x.x
        $versionId = strval($major) . '.' . strval($minor) . '.' . strval($patch);
        if ($build != 0)
        {
            $versionId .= '.' . strval($build);
        }

        //  rc1, alpha1, beta1 ...
        if ($preId != '')
        {
            $versionId .= '-' . $preId;
        }
        if ($preNumber != 0)
        {
            $versionId .= strval($preNumber);
        }

        return $versionId;
    }


    public function update(): string
    {

        $isChanged = false;

        // ... ToDo: alpha, beta ...

        // force
        if ($this->isForceVersion)
        {

            $this->outVersionId = $this->forceVersionId;

        }
        else
        {

            [$major, $minor, $patch, $build, $preId, $preNumber] = self::id_2_parts($this->inVersionId);

            // build: increase and reset lower parts

            // increase revision and reset build counter (0.0.++.->0 No beta...)
            if ($this->isBuildFix)
            {
                $patch++;
                $build     = 0;
                $preId     = '';
                $preNumber = 0;

                $this->outVersionId = self::parts_2_id($major, $minor, $patch, $build, $preId, $preNumber);
                print ("isBuildFix (patch++): " . $this->outVersionId) . PHP_EOL;
                $isChanged = true;
            }
            else
            {
                // increase minor and reset revision and build counter (0.++.->0.->0 No beta...)
                if ($this->isBuildRelease)
                {
                    $minor++;
                    $patch     = 0;
                    $build     = 0;
                    $preId     = '';
                    $preNumber = 0;

                    $this->outVersionId = self::parts_2_id($major, $minor, $patch, $build, $preId, $preNumber);
                    print ("isBuildRelease (minor++): " . $this->outVersionId) . PHP_EOL;
                    $isChanged = true;
                }
                else
                {

                    // increase minor and reset revision and build counter (++.->0.->0.->0 No beta...)
                    if ($this->isBuildMain)
                    {
                        $major++;
                        $minor     = 0;
                        $patch     = 0;
                        $build     = 0;
                        $preId     = '';
                        $preNumber = 0;

                        $this->outVersionId = self::parts_2_id($major, $minor, $patch, $build, $preId, $preNumber);
                        print ("isBuildMain (major++): " . $this->outVersionId) . PHP_EOL;
                        $isChanged = true;
                    }
                    else
                    {
                        // --- increase version by flags ----------------------------------------------

                        // x.0.0 will be increased. Lower parts will be reset
                        // Use for incompatible releases
                        if ($this->isIncreaseMajor)
                        {
                            $major++;
                            $minor     = 0;
                            $patch     = 0;
                            $build     = 0;
                            $preId     = '';
                            $preNumber = 0;

                            print ("isIncreaseMajor (major++): " . $major . " ");
                            $isChanged = true;
                        }

                        // 0.x.0 will be increased. Lower parts will be reset
                        // Use for releases
                        if ($this->isIncreaseMinor)
                        {
                            $minor++;
                            $patch     = 0;
                            $build     = 0;
                            $preId     = '';
                            $preNumber = 0;

                            print ("isIncreaseMinor (Minor++): " . $minor . " ");
                            $isChanged = true;
                        }

                        // 0.0.x.0 will be increased. Lower parts will be reset
                        // Use for fixes or slightly improved functions
                        // /isIncreasePatch=true
                        if ($this->isIncreasePatch)
                        {
                            $patch++;
                            $build     = 0;
                            $preId     = '';
                            $preNumber = 0;

                            print ("isIncreasePatch (patch++): " . $patch . " ");
                            $isChanged = true;
                        }

                        // 0.0.0.x will be increased. Lower parts will be reset
                        // Use for developer steps when install with
                        // different version ID is necessary
                        if ($this->isIncreaseBuild)
                        {
                            $build++;
//                            $preId = '';
//                            $preNumber = 0;

                            print ("isIncreaseBuild (build++): " . $build . " ");
                            $isChanged = true;
                        }

                        // 0.0.0.0 (beta,alpha,RC) will be increased. 
                        if ($this->isIncreasePreID)
                        {
                            if ($preId == '')
                            {
                                $preId = 'alpha';
                            }
                            else
                            {
                                if ($preId == 'alpha')
                                {
                                    $preId = 'beta';
                                }
                                else
                                {
                                    $preId = 'rc';
                                }
                            }

                            print ("isIncreasePreID (preId++): " . $preId . " ");
                            $isChanged = true;
                        }

                        // 0.0.0.0-(beta, alpha, rc)x will be increased. 
                        if ($this->isIncreasePreNumber)
                        {
                            $preNumber++;
                            print ("isIncreasePreNumber (preNumber++): " . $preNumber . " ");
                            $isChanged = true;
                        }


                        //-----------------------------------------------
                        // update to outVersionId
                        //-----------------------------------------------

                        if ($isChanged || $this->isBeautify)
                        {
                            $this->outVersionId = self::parts_2_id($major, $minor, $patch, $build, $preId, $preNumber);
                            print ("increased Version: " . $this->outVersionId) . PHP_EOL;
                        }
                    }
                }
            }
        }

        return $this->outVersionId;
    }

    // Task name with options
    public function assignVersionOption(option $option): bool
    {
        $isVersionOption = false;

        switch (strtolower($option->name))
        {
            //--- Version flags -------------------------------------------------------------

            case strtolower('forceversion'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->forceVersionId = $option->value;
                $this->isForceVersion = true;
                $isVersionOption      = true;
                break;

            case strtolower('isincreasemajor'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isIncreaseMajor = true;
                $isVersionOption       = true;
                break;

            case strtolower('isincreaseminor'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isIncreaseMinor = true;
                $isVersionOption       = true;
                break;

            case strtolower('isincreasepatch'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isIncreasePatch = true;
                $isVersionOption       = true;
                break;

            case strtolower('isincreasebuild'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isIncreaseBuild = true;
                $isVersionOption       = true;
                break;

            case strtolower('isbuildmain'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isBuildMain = $option->value;
                $isVersionOption   = true;
                break;

            case strtolower('isbuildrelease'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isBuildRelease = $option->value;
                $isVersionOption      = true;
                break;

            case strtolower('isbuildfix'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isBuildFix = $option->value;
                $isVersionOption  = true;
                break;

            case strtolower('isIncreasePreID'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isIncreasePreID = $option->value;
                $isVersionOption       = true;
                break;

            case strtolower('isIncreasePreNumber'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isIncreasePreNumber = $option->value;
                $isVersionOption           = true;
                break;

            case strtolower('isForcePreID'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isForcePreID = $option->value;
                $isVersionOption    = true;
                break;

            case strtolower('isForcePreNumber'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isForcePreNumber = $option->value;
                $isVersionOption        = true;
                break;

            case strtolower('isRemovePreID'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isRemovePreID = $option->value;
                $isVersionOption     = true;
                break;

            case strtolower('isBeautify'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->isBeautify = true;
                $isVersionOption     = true;
                break;

        } // switch

        return $isVersionOption;
    }

    public function scan4VersionIdInLine(string $line): array
    {
        // ToDo: try, catch

        $this->init();

        // ....<version>5.0.12.5</version>
        if (str_contains($line, '<version>'))
        {

//            // ? use preg_match ...
//            $inVersionId = preg_replace(
//                '/.*<version>(.*)<\/version>.*/',
//                '${1}',
//                trim($line),
//            );
//
//            $this->inVersionId = $inVersionId;
//            $this->versionId->assignInId ($inVersionId);

            // <element>value</element> contains -> standard form

            $idxStart = strpos($line, '>');

            if ($idxStart !== false)
            {
                $idxEnd = strpos($line, '<', $idxStart + 1);
                if ($idxEnd !== false)
                {
                    $inVersionId = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
                }

                // $this->inVersionId = $inVersionId;
                $this->versionId->assignInId ($inVersionId);
            }

        }
        else
        {

            print ('!!! Unexpected <version> line: "' . $line . '" !!!');
            throw new \Exception('!!! Unexpected <version> line: "' . $line . '" !!!');
        }

        return [$this->inVersionId];
    }

    public function formatVersionIdManifest($outVersionId = ''): string
    {
        // ToDo: try, catch

        //--- data source --------------------------------

        // from extern or intern
        if (empty($outVersionId))
        {
            $outVersionId = $this->outVersionId;
        }
        else
        {
            $this->outVersionId = $outVersionId;
        }

        //--- format text --------------------------------

        // ....<version>5.0.12.5</version>

        $versionIdLine = '    ' . '<version>' . $outVersionId . '</version>';

        return $versionIdLine;
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- semVersionId ---" . PHP_EOL;


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