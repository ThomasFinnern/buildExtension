<?php

namespace ManifestFile;

require_once "./baseExecuteTasks.php";
require_once "./copyrightText.php";
require_once "./iExecTask.php";
require_once "./versionId.php";

use CopyrightText\copyrightText;
use Exception;
use ExecuteTasks\baseExecuteTasks;
use ExecuteTasks\executeTasksInterface;
use task\task;
use VersionId\versionId;

/*================================================================================
Class manifestFile
================================================================================*/

// ToDo: !!! read xml instead of lines !!!
// ToDo: include version class handling better into manifest
class manifestFile extends baseExecuteTasks
    implements executeTasksInterface
{
    // internal
    public string $manifestPathFileName = '';
//    public string $componentName = '';
//    public string $extension = '';
//    // com, plg, mod
//    public string $type = '';
//    public string $baseComponentName = '';

    private array $headerLines;
    private array $otherLines;
    private array $outLines;

    private string $xmlLine;

    //--- line data -------------------------

    public string $extType = '';
    public string $extGroup = '';
    private string $extVersion = '';
    private string $extMethod = '';
    private string $componentName = '';
    private string $creationDate = '';
    private string $author = '';
    private string $authorEmail = '';
    private string $authorUrl = '';
    private copyrightText $copyright;
    private string $license = '';

    public versionId $versionId;
    private string $description = '';
    // Name of extension for user like RSGallery2
    public string $element = '';
    private string $namespace = '';

    public string $scriptFile; // </scriptfile>install_langman4dev.php</scriptfile>

    //--- manifest flags ---------------------------------------

    // copyright-, version-classes have their own

    public bool $isUpdateCreationDate = true;
//    private bool $isUseActualYear;
    public bool $isIncrementVersion_build = false;


    public function __construct(
        $srcRoot = "",
        $manifestPathFileName = ''
    ) {
        try {
            parent::__construct($srcRoot, false);

            $this->manifestPathFileName = $manifestPathFileName;

            if (is_file($manifestPathFileName)) {
                $this->readFile();
            }

            $this->versionId = new versionId();
            $this->copyright = new copyrightText();


        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }



//    public function __construct2($srcRoot = "",
//        $manifestPathFileName = '',
//        $componentName = '',
//        $extension = '',
//        $type = ''
//    )
//    {
//        try {
////            print('*********************************************************' . "\r\n");
////            print ("Construct increaseVersionId: " . "\r\n");
//////            print ("srcFile: " . $srcFile . "\r\n");
//////            print ("dstFile: " . $dstFile . "\r\n");
////            print('---------------------------------------------------------' . "\r\n");
//
//            parent::__construct ($srcRoot, false);
//
//            $this->manifestPathFileName = $manifestPathFileName;
//            $this->componentName = $componentName;
//            $this->extension = $extension;
//            $this->type = $type;
//
//        } catch (Exception $e) {
//            echo 'Message: ' . $e->getMessage() . "\r\n";
//        }
//    }

    private function assignHeaderLine($line): bool
    {
        $isHeaderLine = false;

        try {
            /**
             * <extension type="component" method="upgrade">
             * <name>com_rsgallery2</name>
             * <creationDate>2024.09.13</creationDate>
             * <author>RSGallery2 Team</author>
             * <authorEmail>team2@rsgallery2.org</authorEmail>
             * <authorUrl>https://www.rsgallery2.org</authorUrl>
             * <copyright>(c) 2005-2024 RSGallery2 Team</copyright>
             * <license>GNU General Public License version 2 or later;</license>
             * <version>5.0.12.5</version>
             * <description>COM_RSGALLERY2_XML_DESCRIPTION</description>
             * <element>RSGallery2</element>
             * <namespace path="src">Rsgallery2\Component\Rsgallery2</namespace>
             */

            $itemName = $this->itemName($line);

            // first section read -> (actually) exit
            if ($itemName != '') {
                switch ($itemName) {
                    case 'extension':
                        [$this->extType, $this->extGroup,
                         $this->extVersion, $this->extMethod
                        ] = $this->extractExtension($line);
                        $isHeaderLine = true;
                        break;

                    case 'name':
                        $this->componentName = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'creationDate':
                        $this->creationDate = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'author':
                        $this->author = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'authorEmail':
                        $this->authorEmail = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'authorUrl':
                        $this->authorUrl = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'copyright':
                        $copyrightText = $this->extractContent($line);
                        $this->copyright = new copyrightText($copyrightText);
                        $isHeaderLine = true;
                        break;

                    case 'license':
                        $this->license = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'version':
                        // $this->versionId->inVersionId = $this->extractContent($line);
                        $inVersionId = $this->versionId->scan4VersionIdInLine($line);
                        $isHeaderLine = true;
                        break;

                    case 'description':
                        $this->description = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'element':
                        $this->element = $this->extractContent($line);
                        $isHeaderLine = true;
                        break;

                    case 'namespace':
                        // $this->namespace = $this->extractContent($line);
                        $this->namespace = trim($line);
                        $isHeaderLine = true;
                        break;

                    case '?xml':
                        $this->xmlLine = trim($line);
                        $isHeaderLine = true;
                        break;

                    default:
                        print ('!!! Unexpected header item name: "' . $itemName . '" !!!');
                        throw new Exception('!!! Unexpected header item name: "' . $itemName . '" !!!');
                }
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isHeaderLine;
    }

    private function createHeaderLines(): void
    {
        $headerLines = [];

        /**
         * <extension type="component" method="upgrade">
         * <name>com_rsgallery2</name>
         * <creationDate>2024.09.13</creationDate>
         * <author>RSGallery2 Team</author>
         * <authorEmail>team2@rsgallery2.org</authorEmail>
         * <authorUrl>https://www.rsgallery2.org</authorUrl>
         * <copyright>(c) 2005-2024 RSGallery2 Team</copyright>
         * <license>GNU General Public License version 2 or later;</license>
         * <version>5.0.12.5</version>
         * <description>COM_RSGALLERY2_XML_DESCRIPTION</description>
         * <element>RSGallery2</element>
         * <namespace path="src">Rsgallery2\Component\Rsgallery2</namespace>
         */

        // <?xml version="1.0" encoding="utf-8" ? >
        $headerLines[] = $this->xmlLine . "\r\n";

        // <extension type="component" method="upgrade">
        $headerLines[] = $this->createHeaderLineExtension () . "\r\n";

//                case 'name':
        $headerLines[] = $this->createHeaderLine('name', $this->componentName) . "\r\n";

//                case 'creationDate':
        $headerLines[] = $this->createHeaderLine('creationDate', $this->creationDate) . "\r\n";

//                case 'author':
        $headerLines[] = $this->createHeaderLine('author', $this->author) . "\r\n";

//                case 'authorEmail':
        $headerLines[] = $this->createHeaderLine('authorEmail', $this->authorEmail) . "\r\n";

//                case 'authorUrl':
        $headerLines[] = $this->createHeaderLine('authorUrl', $this->authorUrl) . "\r\n";

//                case 'copyright':
        $headerLines[] = $this->createHeaderLine('copyright', $this->copyright->formatCopyrightManifest()) . "\r\n";

//                case 'license':
        $headerLines[] = $this->createHeaderLine('license', $this->license) . "\r\n";

//                case 'version':
        if ($this->versionId->outVersionId == '') {
            $this->versionId->outVersionId = $this->versionId->inVersionId;
        }
        // $headerLines[] = $this->createHeaderLine('version', $this->versionId->outVersionId) . "\r\n";
        $headerLines[] = $this->versionId->formatVersionIdManifest () . "\r\n";

//                case 'description':
        $headerLines[] = $this->createHeaderLine('description', $this->description) . "\r\n";

//                case 'element':
        $headerLines[] = $this->createHeaderLine('element', $this->element) . "\r\n";

//                case 'namespace':
        // $headerLines[] = $this->createHeaderLine('namespace', $this->namespace) . "\r\n";
        $headerLines[] = '    ' . $this->namespace . "\r\n";

        $this->headerLines = $headerLines;

        return;
    }

    private function createHeaderLine(string $name, string $value): string {

        // "    <name>com_rsgallery2</name>"
        $line = '    <' . $name . '>' . $value . '</' . $name . '>';

        return $line;
    }

    private static function manifestPathFileName($srcRoot, $baseComponentName): string
    {
        // $manifestPathFileName = '';

        $manifestPathFileName = $srcRoot . '/' . $baseComponentName . '.xml';

        return $manifestPathFileName;
    }


    private function baseComponentName(): string
    {
//        if ($this->baseComponentName == '') {
//            $this->baseComponentName = substr($this->componentName, 4);
//        }
//
//        return $this->baseComponentName;

        $baseComponentName = substr($this->componentName, 4);

        return $baseComponentName;
    }

    // Task name with options
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);

            // base options are already handled
            if (!$isBaseOption) {
                $isVersionOption = $this->versionId->assignVersionOption($option);
            }

            if (!$isBaseOption && !$isVersionOption) {

                $this->assignManifestOption($option, $task->name);
                // $OutTxt .= $task->text() . "\r\n";
            }
        }

        return 0;
    }

    /**
     *
     * @param   mixed  $option
     * @param   task   $task
     *
     * @return void
     */
    public function assignManifestOption(mixed $option): bool
    {
        // ToDo: on each option assign isForce... , then use it on write
        // Actual assigned options are only used if the value is not set in the manifest file

        $isManifestOption = false;


        $isVersionOption = $this->versionId->assignVersionOption($option);

        if ( ! $isVersionOption) {
            switch (strtolower($option->name)) {
                // manifestFile
                case 'manifestfile':
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->manifestPathFileName = $option->value;
                    $isManifestOption = true;
                    break;

                // component name like com_rsgallery2
                case 'componentname':
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->componentName = $option->value;
                    $isManifestOption = true;
                    break;

                // element: name like RSGallery2
                case 'extension':
                case 'element':
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->element = $option->value;
                    $isManifestOption = true;
                    break;

                // component / module / plugin
                case 'type':
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->extType = $option->value;
                    $isManifestOption = true;
                    break;

                // ToDo: if needed
                //  method="upgrade">
                case 'method':
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->extMethod = $option->value;
                    $isManifestOption = true;
                    break;

                case 'isupdatecreationdate':
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->isUpdateCreationDate = $option->value;
                    $isManifestOption = true;
                    break;

                case 'isincrementversion_build':
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->isIncrementVersion_build = $option->value;
                    $isManifestOption = true;
                    break;

            } // switch
        }

        return $isManifestOption ||  $isVersionOption;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print ("Execute manifestFile: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        $hasError = 0;
        // $hasError = $this->exchangeVersionId();

        // update manifest file name
        // $this->baseComponentName();

        if ($this->isIncrementVersion_build) {

            $this->versionId->isIncreaseBuild = true;
            print ("Manifest: isIncreaseBuild: " .  $this->versionId->isIncreaseBuild  . "\r\n");
        }

        //  Apply versionId changes from outside
        $this->versionId->update();

        if ($this->isUpdateCreationDate) {
            $this->updateCreationDate();
        }

        return $hasError;
    }

    public function executeFile(string $filePathName): int
    {
        // TODO: Implement executeFile() method.
        return 0;
    }


    public function readFile(string $filePathName=''): bool
    {
        $isRead = false;

        try {

            if ($filePathName != '') {
                // save in class
                $this->manifestPathFileName = $filePathName;
            }

            $manifestFileName = $this->manifestPathFileName;

            // ToDo: on xml over more lines -> better read xml objects

            if (is_file($manifestFileName)) {
                $inLines = file($manifestFileName);

                $headerLines = [];
                $otherLines  = [];

                $isHeaderLine = true;
                $isScriptFound = false;

                foreach ($inLines as $line) {
                    if ($isHeaderLine) {

                        //--- header lines -------------------------------

                        $isHeaderLine = $this->assignHeaderLine($line);
                        if ($isHeaderLine) {
                            $headerLines[] = $line;
                        } else {
                            // End of header lines
                            $otherLines[] = $line;
                        }
                    } else {

                        //--- later lines -------------------------------

                        // assign install script
                        if (! $isScriptFound) {

                            $isScriptFound = $this->checkforScriptfile($line);
                        }

                        $otherLines[] = $line;
                    }
                }

                $this->headerLines = $headerLines;
                $this->otherLines  = $otherLines;

                $isRead = true;
            }
//            else {
//
//            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isRead;
    }

    public function writeFile(string $filePathName=''): int
    {
        $isSaved = false;

        try {

            if ($filePathName != '') {
                // save in class
                $this->manifestPathFileName = $filePathName;
            }
            $manifestFileName = $this->manifestPathFileName;

            // build header from variables
            $this->createHeaderLines();

            $this->outLines = array_merge ($this->headerLines, $this->otherLines);

            file_put_contents($manifestFileName, $this->outLines);
            $isSaved = True;

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isSaved;
    }

//    public function extractVersionId (){
//
//
//    }
//
//    public function exchangeVersionId (){
//
//
//    }
//
    public function updateCreationDate (){

        // $date = "20240824";
        $date_format = 'Y.m.d';
        $date = date($date_format);

        $this->creationDate = $date;

        $this->copyright->setActCopyright2Today();

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

    private function itemName(string $line) {

        $name = '';

        // is xml element
        $idxStart = strpos($line, '<');

        if ($idxStart !== false) {
            // xml flag
            // <?xml version="1.0" encoding="utf-8" ? >
            if (str_contains($line, '<?xml')) {
                $name = '?xml';
            } else {

                // standard form <element>value</element> contains '</'
                // <name>com_rsgallery2</name>
                $idxStandard = strpos($line, '</');
                if ($idxStandard !== false) {

                    $idxEnd = strpos($line, '>', $idxStart+1);
                    if ($idxEnd !== false) {
                        $name = substr($line, $idxStart + 1, $idxEnd - $idxStart-1);
                    }

                    // <namespace path="src">Rsgallery2\Component\Rsgallery2</namespace>
                    // if blank in name use first part
                    $idxSpace = strpos($name, ' ');
                    if ($idxSpace !== false) {
                        $name = substr($name, 0,$idxSpace);
                    }

                } else {
                    // <extension type="component" method="upgrade">
                    // if blank in name use first part
                    $idxSpace = strpos($line, ' ',$idxStart+1);
                    if ($idxSpace !== false) {
                        // $name = substr($name, 0,$idxSpace - 1);
                        $name = substr($line, $idxStart + 1, $idxSpace - 1);
                    }
                }
            }
        }

        return $name;
    }


    private function extractContent(string $line) : string
    {
        $value = '';

        // <element>value</element> contains -> standard form

        $idxStart = strpos($line, '>');

        if ($idxStart !== false) {

            $idxEnd = strpos($line, '<', $idxStart + 1);
            if ($idxEnd !== false) {

                $value = substr($line, $idxStart +1, $idxEnd - $idxStart -1);
            }

//                // if blank in value use first part
//                $idxSpace =strpos($value, ' ');
//                if ($idxSpace !== false) {
//
//                    $value = substr($value, $idxSpace-1);
//
//                }
        }

        return $value;
    }

    private function extractExtension(string $inLine) {

        $type = '???';
        $group = '';
        $version = '';
        $method = '???';

        // <extension type="component" method="upgrade">
        $line = trim($inLine);

        if (str_starts_with($line, '<extension')) {

            // ToDo: on multiple lines ???
            // In one line ?
            if (str_contains($line, '>')) {

                //--- type ----------------------------------

                $idxType= strpos($line, 'type=');
                if ($idxType !== false) {
                    $idxStart = strpos($line, '"', $idxType + 4);

                    if ($idxStart !== false) {
                        $idxEnd = strpos($line, '"', $idxStart+1);
                        if ($idxEnd !== false) {
                            $type = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
                        }
                    }
                }

                //--- group ----------------------------------

                $idxGroup= strpos($line, 'group=');
                if ($idxGroup !== false) {
                    $idxStart = strpos($line, '"', $idxGroup + 5);

                    if ($idxStart !== false) {
                        $idxEnd = strpos($line, '"', $idxStart+1);
                        if ($idxEnd !== false) {
                            $group = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
                        }
                    }
                }

                //--- version ----------------------------------

                $idxVersion= strpos($line, 'version=');
                if ($idxVersion !== false) {
                    $idxStart = strpos($line, '"', $idxVersion + 7);

                    if ($idxStart !== false) {
                        $idxEnd = strpos($line, '"', $idxStart+1);
                        if ($idxEnd !== false) {
                            $version = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
                        }
                    }
                }

                //--- method ----------------------------------

                $idxMethod= strpos($line, 'method=');
                if ($idxMethod !== false) {

                    $idxStart = strpos($line, '"', $idxMethod +7);

                    if ($idxStart !== false) {
                        $idxEnd = strpos($line, '"', $idxStart + 1);
                        if ($idxEnd !== false) {
                            $method = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
                        }
                    }
                }

            }
//            else {
//                // read part if exists or in next lines ...
//                // section starts
//            }
        }

        return [$type, $group, $version, $method];
    }

    private function createHeaderLineExtension(): string {

        // <extension type="component" method="upgrade">
        // "    <name>com_rsgallery2</name>"
        $line = '<';
        $line .= 'extension type="' . $this->extType;

        if ( ! empty($this->extGroup)){
            $line .= '" group="' . $this->extGroup;
        }

        if ( ! empty($this->extVersion)){
            $line .= '" version="' . $this->extVersion;
        }

        $line .= '" method="' . $this->extMethod;
        $line .= '">';

        return $line;
    }

    private function checkforScriptfile(mixed $line) : bool
    {
        $isScriptLine = false;

        try {
            $itemName = $this->itemName($line);

            if ($itemName == 'scriptfile') {

                $isScriptLine = true;

                $this->scriptFile =  $this->extractContent($line);
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isScriptLine;
    }

}
