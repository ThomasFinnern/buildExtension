<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

use Exception;
use Finnern\BuildExtension\src\fileManifestLib\manifestXml;
use Finnern\BuildExtension\src\fileManifestLib\copyrightText;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\versionLib\versionId;

/*================================================================================
Class manifestFile
================================================================================*/

/*
 *
 * read and change the manifest files as required by options
 *
 *
 * the manifest file is read by creation of class
 *
 * options are following the manifest xml format see below
   plugin example
    <extension type="plugin" group="webservices" method="upgrade">
	<name>plg_webservices_content</name>
	<author>Joomla! Project</author>
	<creationDate>2019-09</creationDate>
	<copyright>(C) 2019 Open Source Matters, Inc.</copyright>
	<license>GNU General Public License version 2 or later; see LICENSE.txt</license>
	<authorEmail>admin@joomla.org</authorEmail>
	<authorUrl>www.joomla.org</authorUrl>
	<version>4.0.0</version>
	<description>PLG_WEBSERVICES_CONTENT_XML_DESCRIPTION</description>
	<namespace path="src">Joomla\Plugin\WebServices\Content</namespace>
	<files>
		<folder plugin="content">services</folder>
		<folder>src</folder>
	</files>
	<languages>
		<language tag="en-GB">language/en-GB/plg_webservices_content.ini</language>
		<language tag="en-GB">language/en-GB/plg_webservices_content.sys.ini</language>
	</languages>
</extension>

<extension type="component" method="upgrade">
  <name>com_rsgallery2</name>
  <creationDate>2024.09.13</creationDate>
  <author>RSGallery2 Team</author>
  <authorEmail>team2@rsgallery2.org</authorEmail>
  <authorUrl>https://www.rsgallery2.org</authorUrl>
  <copyright>(c) 2005-2024 RSGallery2 Team</copyright>
  <license>GNU General Public License version 2 or later;</license>
  <version>5.0.12.5</version>
  <description>COM_RSGALLERY2_XML_DESCRIPTION</description>
  <element>RSGallery2</element>
  <namespace path="src">Rsgallery2\Component\Rsgallery2</namespace>
...
</extension>

 */

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

//    private array $headerLines;
//    private array $otherLines;
//    private array $outLines;
//
//    private string $xmlLine;

    //--- line data -------------------------

//    public string $extType = '';
//    public string $extGroup = '';
//    private string $extVersion = '';
//    private string $extMethod = '';
//    private string $componentName = '';
//    private string $creationDate = '';
//    private string $author = '';
//    private string $authorEmail = '';
//    private string $authorUrl = '';
//    private copyrightText $copyright;
//    private string $license = '';
//    private string $description = '';
//    // Name of extension for user like RSGallery2
//    public string $element = '';
//    private string $namespace = '';
//    public string $scriptFile; // </scriptfile>install_langman4dev.php</scriptfile>
//

    //--- requests for assignment ---------------------------------

    private versionId $versionId;
    private copyrightText $copyright;

    // requests [name]= value
    private $requests = [];

    private manifestXml $manifestXml;

    //--- manifest flags ---------------------------------------

    // copyright-, version-classes have their own


    public bool $isUpdateCreationDate = false;
    // ToDo: use version and flags
    // public bool $isIncrementVersion_build = false;
    // use actual year
    public bool $isUpdateActCopyrightYear = false;


    public function __construct(
        $srcRoot = "",
        $manifestPathFileName = ''
    ) {
        try {
            parent::__construct($srcRoot, false);

            $this->manifestPathFileName = $manifestPathFileName;

            if (is_file($manifestPathFileName)) {

                // does read xml immediately
                $this->manifestXml = new manifestXml($manifestPathFileName);
            } else {
                // ToDo: error message or create file ?
                $this->manifestXml = new manifestXml($manifestPathFileName);
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

//    private function assignHeaderLine($line): bool
//    {
//        $isHeaderLine = false;
//
//        try {
//            /**
//             * <extension type="component" method="upgrade">
//             * <name>com_rsgallery2</name>
//             * <creationDate>2024.09.13</creationDate>
//             * <author>RSGallery2 Team</author>
//             * <authorEmail>team2@rsgallery2.org</authorEmail>
//             * <authorUrl>https://www.rsgallery2.org</authorUrl>
//             * <copyright>(c) 2005-2024 RSGallery2 Team</copyright>
//             * <license>GNU General Public License version 2 or later;</license>
//             * <version>5.0.12.5</version>
//             * <description>COM_RSGALLERY2_XML_DESCRIPTION</description>
//             * <element>RSGallery2</element>
//             * <namespace path="src">Rsgallery2\Component\Rsgallery2</namespace>
//             */
//
//            $itemName = $this->itemName($line);
//
//            // first section read -> (actually) exit
//            if ($itemName != '') {
//                switch ($itemName) {
//                    case 'extension':
//                        [$this->extType, $this->extGroup,
//                         $this->extVersion, $this->extMethod
//                        ] = $this->extractExtension($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'name':
//                        $this->componentName = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'creationDate':
//                        $this->creationDate = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'author':
//                        $this->author = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'authorEmail':
//                        $this->authorEmail = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'authorUrl':
//                        $this->authorUrl = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'copyright':
//                        $copyrightText = $this->extractContent($line);
//                        $this->copyright = new copyrightText($copyrightText);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'license':
//                        $this->license = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'version':
//                        // $this->versionId->inVersionId = $this->extractContent($line);
//                        $inVersionId = $this->versionId->scan4VersionIdInLine($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'description':
//                        $this->description = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'element':
//                        $this->element = $this->extractContent($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case 'namespace':
//                        // $this->namespace = $this->extractContent($line);
//                        $this->namespace = trim($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    case '?xml':
//                        $this->xmlLine = trim($line);
//                        $isHeaderLine = true;
//                        break;
//
//                    default:
//                        print ('!!! Unexpected header item name: "' . $itemName . '" !!!');
//                        throw new Exception('!!! Unexpected header item name: "' . $itemName . '" !!!');
//                }
//            }
//        } catch (Exception $e) {
//            echo 'Message: ' . $e->getMessage() . "\r\n";
//            $hasError = -101;
//        }
//
//        return $isHeaderLine;
//    }
//
//    private function createHeaderLines(): void
//    {
//        $headerLines = [];
//
//        /**
//         * <extension type="component" method="upgrade">
//         * <name>com_rsgallery2</name>
//         * <creationDate>2024.09.13</creationDate>
//         * <author>RSGallery2 Team</author>
//         * <authorEmail>team2@rsgallery2.org</authorEmail>
//         * <authorUrl>https://www.rsgallery2.org</authorUrl>
//         * <copyright>(c) 2005-2024 RSGallery2 Team</copyright>
//         * <license>GNU General Public License version 2 or later;</license>
//         * <version>5.0.12.5</version>
//         * <description>COM_RSGALLERY2_XML_DESCRIPTION</description>
//         * <element>RSGallery2</element>
//         * <namespace path="src">Rsgallery2\Component\Rsgallery2</namespace>
//         */
//
//        // <?xml version="1.0" encoding="utf-8" ? >
//        $headerLines[] = $this->xmlLine . "\r\n";
//
//        // <extension type="component" method="upgrade">
//        $headerLines[] = $this->createHeaderLineExtension () . "\r\n";
//
////                case 'name':
//        $headerLines[] = $this->createHeaderLine('name', $this->componentName) . "\r\n";
//
////                case 'creationDate':
//        $headerLines[] = $this->createHeaderLine('creationDate', $this->creationDate) . "\r\n";
//
////                case 'author':
//        $headerLines[] = $this->createHeaderLine('author', $this->author) . "\r\n";
//
////                case 'authorEmail':
//        $headerLines[] = $this->createHeaderLine('authorEmail', $this->authorEmail) . "\r\n";
//
////                case 'authorUrl':
//        $headerLines[] = $this->createHeaderLine('authorUrl', $this->authorUrl) . "\r\n";
//
////                case 'copyright':
//        $headerLines[] = $this->createHeaderLine('copyright', $this->copyright->formatCopyrightManifest()) . "\r\n";
//
////                case 'license':
//        $headerLines[] = $this->createHeaderLine('license', $this->license) . "\r\n";
//
////                case 'version':
//        if ($this->versionId->outVersionId == '') {
//            $this->versionId->outVersionId = $this->versionId->inVersionId;
//        }
//        // $headerLines[] = $this->createHeaderLine('version', $this->versionId->outVersionId) . "\r\n";
//        $headerLines[] = $this->versionId->formatVersionIdManifest () . "\r\n";
//
////                case 'description':
//        $headerLines[] = $this->createHeaderLine('description', $this->description) . "\r\n";
//
////                case 'element':
//        $headerLines[] = $this->createHeaderLine('element', $this->element) . "\r\n";
//
////                case 'namespace':
//        // $headerLines[] = $this->createHeaderLine('namespace', $this->namespace) . "\r\n";
//        $headerLines[] = '    ' . $this->namespace . "\r\n";
//
//        $this->headerLines = $headerLines;
//
//        return;
//    }
//
//    private function createHeaderLine(string $name, string $value): string {
//
//        // "    <name>com_rsgallery2</name>"
//        $line = '    <' . $name . '>' . $value . '</' . $name . '>';
//
//        return $line;
//    }

    private static function manifestPathFileName($srcRoot, $baseComponentName): string
    {
        // $manifestPathFileName = '';

        $manifestPathFileName = $srcRoot . '/' . $baseComponentName . '.xml';

        return $manifestPathFileName;
    }


//    private function baseComponentName(): string
//    {
////        if ($this->baseComponentName == '') {
////            $this->baseComponentName = substr($this->componentName, 4);
////        }
////
////        return $this->baseComponentName;
//
//        $baseComponentName = substr($this->componentName, 4);
//
//        return $baseComponentName;
//    }

    // Task name with options
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);

            // version: ToDo: create on construct and use flags on execute

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
                case strtolower('manifestFile'):
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->manifestPathFileName = $option->value;
                    $isManifestOption = true;
                    break;

                //--- xml elements values to be written --------------------------------------

                // COM_LANG4DEV, plg_webservices_content
                case strtolower('componentName'):
                    // component / module / plugin
                case strtolower('extensionType'):
                case strtolower('extensionGroup'):
                case strtolower('extensionVersion'):
                case strtolower('extensionMethod'):

                // component name like com_rsgallery2
                case strtolower('name'):
                case strtolower('author'):
                case strtolower('creationDate'):
                case strtolower('copyright'):
                case strtolower('license'):
                case strtolower('authorEmail'):
                case strtolower('authorUrl'):
                case strtolower('version'):
                case strtolower('description'):
                // element: name like RSGallery2,
                case strtolower('element'):
                case strtolower('namespace'):
                case strtolower('type'):

                case strtolower('sinceYear'):
                case strtolower('actYear'):

                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->requests[$option->name ] = $option->value;
                    $isManifestOption = true;
                    break;

                //--- flags to execute --------------------------------------

                case strtolower('isUpdateCreationDate'):
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->isUpdateCreationDate = $option->value;
                    $isManifestOption = true;
                    break;

                    // done automatically below with flags for versionId
//                case strtolower('isIncrementVersion_build'):
//                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//                    $this->isIncrementVersion_build = $option->value;
//                    $isManifestOption = true;
//                    break;

                case strtolower('isUpdateActCopyrightYear '):
                    print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                    $this->isUpdateActCopyrightYear = $option->value;
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

        // does read xml immediately
        $this->manifestXml = new manifestXml($this->manifestPathFileName);

        // Manifest file must be loaded
        if ( ! empty ($this->manifestXml)) {

            //--- version line -----------------------------------

            $isChanged = $this->increaseVersion();

            //---  -----------------------------------

            if ($this->isUpdateCreationDate) {
                $isChanged |= $this->updateCreationDate();
            }

            //---  -----------------------------------

            if ($this->isUpdateActCopyrightYear) {
                $isChanged |= $this->updateActCopyrightYear();
            }

            //--- xml variable assign requests -----------------------------------

            $isChanged |= $this->requestVariables();

            //--- save on change -----------------------------------

            if ($isChanged) {
                $isSaved = $this->manifestXml->writeManifestXml();
            }

        }

        return $hasError;
    }

    public function executeFile(string $filePathName): int
    {
        // TODO: Implement executeFile() method.
        return 0;
    }

    private function increaseVersion() : bool
    {
        $isChanged = false;

        try {
            $manifestXml = $this->manifestXml;

            //--- old version ID -----------------------------------

            $inVersionId = (string) $manifestXml->getByXml('version', '');

            // $this->versionId = new versionId($inCopyright);

            //--- update  -----------------------------------

            $this->versionId->inVersionId =  $inVersionId;

            // exchange for new version id
            $this->versionId->update();

            //--- version line -----------------------------------

            $outVersionId = $this->versionId->outVersionId;

            if ($outVersionId != $inVersionId) {

                // $manifestXml->versionId->outVersionId = $outVersionId;
                $manifestXml->setByXml('version', $outVersionId);

                $isChanged = true;
            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isChanged;
    }

    private function updateActCopyrightYear() : bool
    {
        $isChanged = false;

        try {

            $manifestXml = $this->manifestXml;

            $date_format = 'Y';
            $actYear = date($date_format);

            $isChanged = $this->assignActCopyrightYear( $actYear);


        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isChanged;
    }

    private function assignActCopyrightYear(string $actYear) : bool
    {
        $isChanged = false;

        try {

            $manifestXml = $this->manifestXml;

            //--- old version ID -----------------------------------

            $inCopyright = (string) $manifestXml->getByXml('copyright', '');

            //--- update  -----------------------------------

            $copyrightText = new copyrightText($inCopyright);

            if ($copyrightText->actCopyrightDate != $actYear) {

                $copyrightText->actCopyrightDate = $actYear;
                $outCopyrightText = $copyrightText->formatCopyrightManifest();

                $this->manifestXml->setByXml('copyright', $outCopyrightText);
                $isChanged = true;
            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isChanged;
    }

    private function assignSinceCopyrightYear(string $actYear) : bool
    {
        $isChanged = false;

        try {

            $manifestXml = $this->manifestXml;

            //--- old version ID -----------------------------------

            $inCopyright = (string) $manifestXml->getByXml('copyright', '');

            //--- update  -----------------------------------

            $copyrightText = new copyrightText($inCopyright);

            if ($copyrightText->sinceCopyrightDate != $actYear) {

                $copyrightText->sinceCopyrightDate = $actYear;
                $outCopyrightText = $copyrightText->formatCopyrightManifest();

                $this->manifestXml->setByXml('copyright', $outCopyrightText);
                $isChanged = true;
            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isChanged;
    }

    public function updateCreationDate () : bool
    {
        $isChanged = true;

        // $date = "20240824";
        $date_format = 'Y.m.d';
        $actDate = date($date_format);

        $this->manifestXml->setByXml('creationDate', $actDate);

//        $this->copyright->setActCopyright2Today();

        return $isChanged;
    }

    private function requestVariables() : bool
    {
        $isChanged = false;

        try {

            $manifestXml = $this->manifestXml;

            foreach ($this->requests as $requestName => $requestValue) {
                switch (strtolower($requestName)) {

                    case strtolower('componentName'):
                        // component / module / plugin
                    case strtolower('extensionType'):
                    case strtolower('extensionGroup'):
                    case strtolower('extensionVersion'):
                    case strtolower('extensionMethod'):

                        // component name like com_rsgallery2
                    case strtolower('name'):
                    case strtolower('author'):
                    case strtolower('creationDate'):
                    case strtolower('copyright'):
                    case strtolower('license'):
                    case strtolower('authorEmail'):
                    case strtolower('authorUrl'):
                    case strtolower('version'):
                    case strtolower('description'):
                        // element: name like RSGallery2,
                    case strtolower('element'):
                    case strtolower('namespace'):
                    case strtolower('type'):
                        // direct assignment to XML element
                        print ('     request: ' . $requestName . ' ' . $requestValue . "\r\n");
                        $this->manifestXml->setByXml($requestName, $requestValue);

                        $isChanged = true;
                        break;

                    case strtolower('actYear'):
                        print ('     request: ' . $requestName . ' ' . $requestValue . "\r\n");
                        $isChanged = $this->assignActCopyrightYear($requestValue);
                        break;

                    case strtolower('sinceYear'):
                        print ('     request: ' . $requestName . ' ' . $requestValue . "\r\n");
                        $isChanged = $this->assignSinceCopyrightYear($requestValue);
                        break;

                }
            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isChanged;
    }



//    public function readFile(string $filePathName=''): bool
//    {
//        $isRead = false;
//
//        try {
//
//            if ($filePathName != '') {
//                // save in class
//                $this->manifestPathFileName = $filePathName;
//            }
//
//            $$manifestPathFileName = $this->manifestPathFileName;
//
//            // ToDo: on xml over more lines -> better read xml objects
//
//            if (is_file($$manifestPathFileName)) {
//                $inLines = file($$manifestPathFileName);
//
//                $headerLines = [];
//                $otherLines  = [];
//
//                $isHeaderLine = true;
//                $isScriptFound = false;
//
//                foreach ($inLines as $line) {
//                    if ($isHeaderLine) {
//
//                        //--- header lines -------------------------------
//
//                        $isHeaderLine = $this->assignHeaderLine($line);
//                        if ($isHeaderLine) {
//                            $headerLines[] = $line;
//                        } else {
//                            // End of header lines
//                            $otherLines[] = $line;
//                        }
//                    } else {
//
//                        //--- later lines -------------------------------
//
//                        // assign install script
//                        if (! $isScriptFound) {
//
//                            $isScriptFound = $this->checkforScriptfile($line);
//                        }
//
//                        $otherLines[] = $line;
//                    }
//                }
//
//                $this->headerLines = $headerLines;
//                $this->otherLines  = $otherLines;
//
//                $isRead = true;
//            }
////            else {
////
////            }
//
//        } catch (Exception $e) {
//            echo 'Message: ' . $e->getMessage() . "\r\n";
//            $hasError = -101;
//        }
//
//        return $isRead;
//    }
//
//    public function writeFile(string $filePathName=''): int
//    {
//        $isSaved = false;
//
//        try {
//
//            if ($filePathName != '') {
//                // save in class
//                $this->manifestPathFileName = $filePathName;
//            }
//            $$manifestPathFileName = $this->manifestPathFileName;
//
//            // build header from variables
//            $this->createHeaderLines();
//
//            $this->outLines = array_merge ($this->headerLines, $this->otherLines);
//
//            file_put_contents($$manifestPathFileName, $this->outLines);
//            $isSaved = True;
//
//        } catch (Exception $e) {
//            echo 'Message: ' . $e->getMessage() . "\r\n";
//            $hasError = -101;
//        }
//
//        return $isSaved;
//    }

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

//    private function itemName(string $line) {
//
//        $name = '';
//
//        // is xml element
//        $idxStart = strpos($line, '<');
//
//        if ($idxStart !== false) {
//            // xml flag
//            // <?xml version="1.0" encoding="utf-8" ? >
//            if (str_contains($line, '<?xml')) {
//                $name = '?xml';
//            } else {
//
//                // standard form <element>value</element> contains '</'
//                // <name>com_rsgallery2</name>
//                $idxStandard = strpos($line, '</');
//                if ($idxStandard !== false) {
//
//                    $idxEnd = strpos($line, '>', $idxStart+1);
//                    if ($idxEnd !== false) {
//                        $name = substr($line, $idxStart + 1, $idxEnd - $idxStart-1);
//                    }
//
//                    // <namespace path="src">Rsgallery2\Component\Rsgallery2</namespace>
//                    // if blank in name use first part
//                    $idxSpace = strpos($name, ' ');
//                    if ($idxSpace !== false) {
//                        $name = substr($name, 0,$idxSpace);
//                    }
//
//                } else {
//                    // <extension type="component" method="upgrade">
//                    // if blank in name use first part
//                    $idxSpace = strpos($line, ' ',$idxStart+1);
//                    if ($idxSpace !== false) {
//                        // $name = substr($name, 0,$idxSpace - 1);
//                        $name = substr($line, $idxStart + 1, $idxSpace - 1);
//                    }
//                }
//            }
//        }
//
//        return $name;
//    }
//
//
//    private function extractContent(string $line) : string
//    {
//        $value = '';
//
//        // <element>value</element> contains -> standard form
//
//        $idxStart = strpos($line, '>');
//
//        if ($idxStart !== false) {
//
//            $idxEnd = strpos($line, '<', $idxStart + 1);
//            if ($idxEnd !== false) {
//
//                $value = substr($line, $idxStart +1, $idxEnd - $idxStart -1);
//            }
//
////                // if blank in value use first part
////                $idxSpace =strpos($value, ' ');
////                if ($idxSpace !== false) {
////
////                    $value = substr($value, $idxSpace-1);
////
////                }
//        }
//
//        return $value;
//    }
//
//    private function extractExtension(string $inLine) {
//
//        $type = '???';
//        $group = '';
//        $version = '';
//        $method = '???';
//
//        // <extension type="component" method="upgrade">
//        $line = trim($inLine);
//
//        if (str_starts_with($line, '<extension')) {
//
//            // ToDo: on multiple lines ???
//            // In one line ?
//            if (str_contains($line, '>')) {
//
//                //--- type ----------------------------------
//
//                $idxType= strpos($line, 'type=');
//                if ($idxType !== false) {
//                    $idxStart = strpos($line, '"', $idxType + 4);
//
//                    if ($idxStart !== false) {
//                        $idxEnd = strpos($line, '"', $idxStart+1);
//                        if ($idxEnd !== false) {
//                            $type = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
//                        }
//                    }
//                }
//
//                //--- group ----------------------------------
//
//                $idxGroup= strpos($line, 'group=');
//                if ($idxGroup !== false) {
//                    $idxStart = strpos($line, '"', $idxGroup + 5);
//
//                    if ($idxStart !== false) {
//                        $idxEnd = strpos($line, '"', $idxStart+1);
//                        if ($idxEnd !== false) {
//                            $group = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
//                        }
//                    }
//                }
//
//                //--- version ----------------------------------
//
//                $idxVersion= strpos($line, 'version=');
//                if ($idxVersion !== false) {
//                    $idxStart = strpos($line, '"', $idxVersion + 7);
//
//                    if ($idxStart !== false) {
//                        $idxEnd = strpos($line, '"', $idxStart+1);
//                        if ($idxEnd !== false) {
//                            $version = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
//                        }
//                    }
//                }
//
//                //--- method ----------------------------------
//
//                $idxMethod= strpos($line, 'method=');
//                if ($idxMethod !== false) {
//
//                    $idxStart = strpos($line, '"', $idxMethod +7);
//
//                    if ($idxStart !== false) {
//                        $idxEnd = strpos($line, '"', $idxStart + 1);
//                        if ($idxEnd !== false) {
//                            $method = substr($line, $idxStart + 1, $idxEnd - $idxStart - 1);
//                        }
//                    }
//                }
//
//            }
////            else {
////                // read part if exists or in next lines ...
////                // section starts
////            }
//        }
//
//        return [$type, $group, $version, $method];
//    }
//
//    private function createHeaderLineExtension(): string {
//
//        // <extension type="component" method="upgrade">
//        // "    <name>com_rsgallery2</name>"
//        $line = '<';
//        $line .= 'extension type="' . $this->extType;
//
//        if ( ! empty($this->extGroup)){
//            $line .= '" group="' . $this->extGroup;
//        }
//
//        if ( ! empty($this->extVersion)){
//            $line .= '" version="' . $this->extVersion;
//        }
//
//        $line .= '" method="' . $this->extMethod;
//        $line .= '">';
//
//        return $line;
//    }
//
//    private function checkforScriptfile(mixed $line) : bool
//    {
//        $isScriptLine = false;
//
//        try {
//            $itemName = $this->itemName($line);
//
//            if ($itemName == 'scriptfile') {
//
//                $isScriptLine = true;
//
//                $this->scriptFile =  $this->extractContent($line);
//            }
//        } catch (Exception $e) {
//            echo 'Message: ' . $e->getMessage() . "\r\n";
//            $hasError = -101;
//        }
//
//        return $isScriptLine;
//    }

}
