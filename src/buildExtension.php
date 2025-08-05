<?php

namespace Finnern\BuildExtension\src;

use Exception;
use Finnern\BuildExtension\src\fileManifestLib\filesByManifest;
use Finnern\BuildExtension\src\fileManifestLib\manifestFile;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use ZipArchive;

$HELP_MSG = <<<EOT
    >>>
    class buildExtension

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class buildExtension
================================================================================*/

class buildExtension extends baseExecuteTasks
    implements executeTasksInterface
{
    private string $buildDir = '';

    // Handled in manifest file
    // private versionId $versionId;

    // internal
    private string $manifestPathFileName = '';
    private string $manifestAdminPathFileName = '';

    private string $componentType = '';

    private string $dateToday;
    private string $dateReleaseZip;

    // extension <element> name like RSGallery2
    private string $element;
    // 'rsgallery2' ??? com_rsgallery2'
    private string $extName = '';
    private string $prefixZipName = '';

//    private bool $isIncrementVersion_build = false;

    // todo: replace by private manifestXml $manifestXml;
    private manifestfile $manifestFile;

    private string $componentVersion = '';

    private bool $isCollectPluginsModule = false;

    private bool $isDoNotUpdateCreationDate = false;

    // calling project, may use different file header, different maintenance file ...
    //private string $callerProjectId = '';

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    // ToDo: a lot of parameters ....

    public function __construct($srcRoot = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . "\r\n");
            print ("Construct buildExtension: " . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            parent::__construct($srcRoot, false);

//            $this->srcFile = $srcFile;
//            $this->dstFile = $dstFile;

//            $this->versionId = new versionId();
            $this->manifestFile = new manifestFile();

            $this->element = "";

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

//    // Task name with options
//    public function assignTask(task $task): int
//    {
//        $this->taskName = $task->name;
//
//        $options = $task->options;
//
//        foreach ($options->options as $option) {
//
//            $isBaseOption = $this->assignBaseOption($option);
//
//            // base options are already handled
//            if (!$isBaseOption) {
//                // $isVersionOption = $this->versionId->assignVersionOption($option);
//                // ToDo: include version better into manifest
//                // -> same increase flags should be ...
//                // if (!$isVersionOption) {
//                $isManifestOption = $this->manifestFile->assignManifestOption($option);
//                // }
//            }
//
////            if (!$isBaseOption && !$isVersionOption && !$isManifestOption) {
////            if (!$isBaseOption && !$isVersionOption) {
//            if (!$isBaseOption && !$isManifestOption) {
//
//                $this->assignBuildExtensionOption($option);
//                // $OutTxt .= $task->text() . "\r\n";
//            }
//        }
//
//        return 0;
//    }

    /**
     *
     * @param mixed $option
     * @param task $task
     *
     * @return void
     */
    public function assignOption(mixed $option): bool
    {
        $isOptionConsumed = parent::assignOption($option);

        if ( ! $isOptionConsumed) {

            // $isOptionConsumed = $this->fileNamesList->assignOption($option);
            $isOptionConsumed = $this->manifestFile->assignOption($option);
        }

        if ( ! $isOptionConsumed) {

            switch (strtolower($option->name)) {
                case strtolower('builddir'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->buildDir = $option->value;
                    $isOptionConsumed = true;
                    break;

                // com_rsgallery2'
                case strtolower('name'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->extName = $option->value;
                    $isOptionConsumed = true;
                    break;

//                    // component name like rsgallery2 (but see above)
//                    case strtolower(''):
//                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                        $this->name = $option->value;
//                    $isOptionConsumed  = true;
//                        break;

                // extension (<element> name like RSGallery2
                case strtolower('element'):
                case strtolower('extension'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->element = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('type'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->componentType = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isCollectPluginsModule'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->isCollectPluginsModule = boolval($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('prefixZipName'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->prefixZipName = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isDoNotUpdateCreationDate'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->isDoNotUpdateCreationDate = boolval($option->value);
                    $isOptionConsumed = true;
                    break;


//            case strtolower('isincrementversion_build'):
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->isIncrementVersion_build = $option->value;
//                $isOptionConsumed  = true;
//                break;
//

                default:
                    print ('!!! error: requested option is not supported: ' . $option->name . ' !!!' . "\r\n");
            } // switch
        }

        return $isOptionConsumed;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print("Execute buildExtension: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        //--- validation checks --------------------------------------

        $isValid = $this->check4validInput();

        if ($isValid) {
            $componentType = $this->componentType();

            switch (strtolower($componentType)) {
                case strtolower('component'):
                    $this->buildComponent();

                    break;

                case strtolower('module'):
                    $this->buildModule();
                    break;

                case strtolower('plugin'):
                    $this->buildPlugin();
                    break;

                case strtolower('package'):
                    $this->buildPackage();
                    break;

                default:
                    print ('!!! Default componentType: ' . $componentType . ', No build done !!!');
            } // switch
        }

        return 0;
    }

    private function componentType(): string
    {
        if ($this->componentType == '') {

            $this->componentType = $this->detectCompTypeFromFile(
                $this->manifestPathFileName);

        }

        return $this->componentType;
    }

    private function buildComponent(): string
    {
        //--------------------------------------------------------------------
        // data in manifest file
        //--------------------------------------------------------------------

        //--- manifest file name --------------------------------------

        $bareName = $this->shortExtensionName();
        $manifestPathFileName = $this->manifestPathFileName();
        print ('manifestPathFileName: "' . $manifestPathFileName . '"' . "\r\n");

        //--- update date and version --------------------------------------

        // does read manifest file
        $isChanged = $this->exchangeDataInManifestFile($manifestPathFileName);

        if (!$this->manifestFile->manifestXml->isXmlLoaded) {

            print('exit buildComponent: error manifestPathFileName could not be read: ' . $manifestPathFileName . "\r\n");
            return '';
        }

        //--- update admin manifest xml file --------------------------------------

        // manifest file like 'rsgallery2.xml' needs to be in base folder and
        // component folder. Actually the root file is copied to the component
        // folder
        $manifestAdminPathFileName = $this->manifestAdminPathFileName();
        print('manifestAdminPathFileName: "' . $manifestAdminPathFileName . '"' . "\r\n");
        // is folder structure similar to joomla folders (RSG2)
        //    -> sometimes folder 'components' is left out
        if (is_dir(dirname($manifestAdminPathFileName))) {

            copy($manifestPathFileName, $manifestAdminPathFileName);
        }

        //--------------------------------------------------------------------
        // destination temp folder
        //--------------------------------------------------------------------

        print ('build dir: "' . $this->buildDir . '"' . "\r\n");

        $parentPath = dirname($this->buildDir);
        if (!is_dir($parentPath)) {
            print ('main path does not exist : "' . $parentPath . '"' . "\r\n");
            exit(557);
        }

        if (!is_dir($this->buildDir)) {
            mkdir($this->buildDir, 0777, true);
        }

        $dstRoot = realpath($this->buildDir);
        print ('dstRoot: "' . $dstRoot . '"' . "\r\n");
        $tmpFolder = $this->buildDir . '/tmp';
        print ('temp folder(1): "' . $tmpFolder . '"' . "\r\n");

        //--------------------------------------------------------------------
        // handle temp folder
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            // length big enough to do no damage
            if (strLen($tmpFolder) < 10) {
                exit (555);
            }
            print ('Delete dir: "' . $tmpFolder . '"' . "\r\n");
            delDir($tmpFolder);
        }

        // create tmp folder
        print ('Create dir: "' . $tmpFolder . '"' . "\r\n");
        mkdir($tmpFolder, 0777, true);

        //--------------------------------------------------------------------
        // extract files and folders from manifest
        //--------------------------------------------------------------------

        $filesByManifest = new filesByManifest();

        //--- insert manifestXml ---------------------------------

        $filesByManifest->manifestXml = $this->manifestFile->manifestXml->manifestXml;

        $filesByManifest->collectFilesAndFolders($this->isCollectPluginsModule);

        //--------------------------------------------------------------------
        // copy to temp
        //--------------------------------------------------------------------

        $srcRoot = $this->copy2tmpFolder($filesByManifest, $tmpFolder);

        //--------------------------------------------------------------------
        // manual assignments
        //--------------------------------------------------------------------

        //--- root files -------------------------------------------------

        //  manifest file (not included as 'fileName' in manifest file)
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        print ("\r\n");

//        // install script like 'install_rsg2.php'
//        $installScript = (string)$this->manifestFile->manifestXml->manifestXml->scriptfile;
//        $adminPath = $this->fileNamesList->srcRoot . '/administrator/components/' . $this->extName;
//        if (file_exists($adminPath . '/' . $installScript)) {
//            $this->xcopyElement($installScript, $adminPath, $tmpFolder);
//        }


        // Not needed, the license is defined in manifest or may be inside component base path
        //$this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);

        //--- remove package for rsgallery2 ---------------------------------------------

        // remove prepared pkg_rsgallery2.xml.tmp
        $packagesTmpFile = $tmpFolder . '/administrator/manifests/packages/pkg_rsgallery2.xml.tmp';
        if (file_exists($packagesTmpFile)) {
            unlink($packagesTmpFile);
        }

//            //--------------------------------------------------------------------
//            // Not changelog to root
//            //--------------------------------------------------------------------
//
//            $changelogPathFileName = $this->fileNamesList->srcRoot . '/administrator/components/com_rsgallery2/';
//            if (file_exists($changelogPathFileName)) {
//                $this->xcopyElement('changelog.xml', $changelogPathFileName, $tmpFolder);
//            }

        //--------------------------------------------------------------------
        // zip to destination
        //--------------------------------------------------------------------

        $zipFileName = $dstRoot . '/' . $this->createExtensionZipName();
        zipItRelative(realpath($tmpFolder), $zipFileName);

        //--------------------------------------------------------------------
        // remove temp
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            delDir($tmpFolder);
        }

        return $zipFileName;
    }


    private function manifestPathFileName(): string
    {
        if ($this->manifestPathFileName == '') {

            // *.xml
            $extName = $this->shortExtensionName();

            $this->manifestPathFileName = $this->fileNamesList->srcRoot . '/' . $extName . '.xml';
        }

        return $this->manifestPathFileName;
    }

    private function manifestAdminPathFileName(): string
    {
        if ($this->manifestAdminPathFileName == '') {

            $name = $this->shortExtensionName();

            $this->manifestAdminPathFileName = $this->fileNamesList->srcRoot
                . '/administrator/components/'
                . $this->extName . '/' . $name . '.xml';
        }

        return $this->manifestAdminPathFileName;
    }

    // ToDo: move/create also in manifest.php file ?
    private function shortExtensionName(): string
    {
        $extName = $this->extName;

        print ('extension extName: "' . $extName . '"' . "\r\n");

        // com / mod / plg extension
        if (str_starts_with($extName, 'com_')) {
            // Standard
            $extName = substr($extName, 4);
            // $extName = 'com_' . substr($extName, 4);

        } else {

            if (str_starts_with($extName, 'mod_')) {
                // $extName = substr($extName, 4);
                $extName = $this->extName;
            } else {

                if (str_starts_with($extName, 'plg_')) {
                    $idx = strpos($extName, '_', strlen('plg_')) + 1;
                    $extName = substr($extName, $idx);
                }
            }
        }

        print ('short extName: "' . $extName . '"' . "\r\n");
        return $extName;
    }

    // ToDo: move/create also in to manifest.php file ?
    private function destinationExtensionName(): string
    {
        $name = $this->extName;

        // com / mod extension
        if (str_starts_with($name, 'com_')) {
            // Standard
            $name = substr($name, 4);
            // $extName = 'com_' . substr($extName, 4);

        } else {

            if (str_starts_with($name, 'mod_')) {
                // $idx = strpos($extName, '_', strlen('mod_')) + 1;
                // $extName = 'mod_' . substr($extName, $idx);
                $name = $this->extName;
            } else {

                if (str_starts_with($name, 'plg_')) {
                    // $idx = strpos($extName, '_', strlen('plg_')) + 1;
                    // $extName = 'plg_' . substr($extName, $idx);
                    $name = $this->extName;
                }
            }
        }

        return $name;
    }

    /**
     * @param string $manifestPathFileName
     *
     * @return false
     */
    private function exchangeDataInManifestFile(string $manifestPathFileName)
    {

        $isSaved = false;

        // Done in constructor
        // $manifestFile = new manifestFile();
        // keep flags
        $manifestFile = $this->manifestFile;

        try {
            print ("exchangeDataInManifestFile manifestPathFileName: " . $manifestPathFileName . "\r\n");
//            // read
//            // keep flags
//            $manifestFile->versionId = $this->versionId;

            //--- read file -----------------------------------------------

            $isRead = $manifestFile->readFile($manifestPathFileName);

            if ($isRead) {
                //--- set flags -----------------------------------------------

                // $manifestFile->isUpdateCreationDate = false;
                if (!$this->isDoNotUpdateCreationDate) {
                    $manifestFile->isUpdateCreationDate = true;
                }

//                if ($this->isIncrementVersion_build) {
//                    // $manifestFile->versionId->isBuildRelease = false;
//                    $manifestFile->versionId->isBuildRelease = true;
////                    print ("buildExtension: isBuildRelease: " .  $this->versionId->isBuildRelease  . "\r\n");
//                }

                if ($this->element != '') {
                    $manifestFile->element = $this->element;
                }

                // No tasks actual
                // $manifestFile->copyright->isUpdateCopyright = false;
                // $manifestFile->copyright->isUpdateCopyright = true;


                //--- update data -----------------------------------------------

                $manifestFile->execute();

                //--- write to file -----------------------------------------------

                if ($manifestFile->isChanged) {
                    $isSaved = $manifestFile->writeFile();
                }

                //$isSaved = File::write($manifestFileName, $fileLines);;
                //     $isSaved = file_put_contents($manifestFileName, $outLines);
            }

            $this->manifestFile = $manifestFile;

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isSaved;
    }



//    private function exchangeDateInManifestFile(string $manifestFileName, array $lines)
//    {
//        $isExchanged = false;
//        $outLines = [];
//
//        try {
//
//            // ToDo: external parameter;
//            $date_format = 'Y.m.d';
//            $dateText = date($date_format);
//
//            foreach ($lines as $line) {
//                if ($isExchanged) {
//                    $outLines [] = $line;
//                } else {
//                    // <creationDate>31. May. 2024</creationDate>
//                    if (str_contains($line, '<creationDate>')) {
//                        $outLine = preg_replace(
//                            '/(.*<creationDate>)(.+)(<\/creationDate.*)/i',
//                            '${1}' . $dateText . '${3}',
//                            $line,
//                        );
//                        $outLines [] = $outLine;
//
//                        $isExchanged = true;
//                    } else {
//                        $outLines [] = $line;
//                    }
//                }
//            }
//
//        } catch (Exception $e) {
//            echo 'Message: ' . $e->getMessage() . "\r\n";
//            $hasError = -101;
//        }
//
//        return [$isExchanged, $outLines];
//    }

    private function xcopyElement(string $name, string $srcRoot, string $dstRoot)
    {
        $hasError = 0;
        try {
            $srcPath = $srcRoot . '/' . $name;
            $dstPath = $dstRoot . '/' . $name;

            //--- check path ------------------------------------------

            $srcPathTest = realpath($srcPath);
            if (empty ($srcPathTest)) {
                print ("%%% Warning: Path/file to copy could not be found: " . $srcPath . "\r\n");
            } else {

                //--- create path ------------------------------------------

                $baseDir = dirname($dstPath);
                if (!is_dir($baseDir)) {
                    mkdir($baseDir, 0777, true);
                }

//                $srcPath = str_replace('/', DIRECTORY_SEPARATOR, $srcR    oot . '/' . $extName);
//
//                // str_replace('/', '\\', __FILE__);
//                // str_replace('\\', '/', __FILE__);
//                //$dstPath = realpath ($dstRoot . '/' . $extName);
//                $dstPath = str_replace('/', DIRECTORY_SEPARATOR, $dstRoot . '/' . $extName);

                if (is_dir($srcPath)) {
                    if (!is_dir($dstPath)) {
                        mkdir($dstPath);
                    }
                    print ('/');
                    xcopyDir($srcPath, $dstPath);
                } else {
                    if (is_file($srcPath)) {
                        print ('.');
                        copy($srcPath, $dstPath);
                    } else {
                        print ("%%% Warning: Path/file could not be copied: " . $srcPath . "\r\n");
                    }
                }
            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
    }

    private function createExtensionZipName()
    {
        // rsgallery2.5.0.12.4_20240818.zip
        // extension_name.version_prefix_date.zip

        // $date = "20240824";
        $date_format = 'Ymd';
        $date = date($date_format);

        $name = $this->destinationExtensionName();
        $componentVersion = $this->componentVersion();
        $prefix = $this->prefixZipName;

        $ZipName = $name;
        if (strlen($prefix) > 0) {
            $ZipName .= '.' . $this->prefixZipName;
        }
        $ZipName .= '.' . $componentVersion;
        $ZipName .= '_' . $date . '.zip';

        return $ZipName;
    }

    private function buildModule(): string
    {
        //--------------------------------------------------------------------
        // data in manifest file
        //--------------------------------------------------------------------

        //--- manifest file extName --------------------------------------

        $bareName = $this->shortExtensionName();
        $manifestPathFileName = $this->manifestPathFileName();
        print ("manifestPathFileName: " . $manifestPathFileName . "\r\n");

        $isChanged = $this->exchangeDataInManifestFile($manifestPathFileName);

        if (!$this->manifestFile->manifestXml->isXmlLoaded) {

            print('exit buildModule: error manifestPathFileName could not be read: ' . $manifestPathFileName . "\r\n");
            return '';
        }

        //--------------------------------------------------------------------
        // destination temp folder
        //--------------------------------------------------------------------

        print ('build dir: "' . $this->buildDir . '"' . "\r\n");

        $parentPath = dirname($this->buildDir);
        if (!is_dir($parentPath)) {
            print ('main path does not exist : "' . $parentPath . '"' . "\r\n");
            exit(557);
        }

        if (!is_dir($this->buildDir)) {
            mkdir($this->buildDir, 0777, true);
        }

        $dstRoot = realpath($this->buildDir);
        print ('dstRoot: "' . $dstRoot . '"' . "\r\n");
        $tmpFolder = $this->buildDir . '/tmp';
        print ('temp folder(1): "' . $tmpFolder . '"' . "\r\n");

        //--------------------------------------------------------------------
        // handle temp folder
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            // length big enough to do no damage
            if (strLen($tmpFolder) < 10) {
                exit (555);
            }
            print ('Delete dir: "' . $tmpFolder . '"' . "\r\n");
            delDir($tmpFolder);
        }

        // create tmp folder
        print ('Create dir: "' . $tmpFolder . '"' . "\r\n");
        mkdir($tmpFolder, 0777, true);

        //--------------------------------------------------------------------
        // extract files and folders from manifest
        //--------------------------------------------------------------------

        $filesByManifest = new filesByManifest();

        //--- insert manifestXml ---------------------------------

//        $filesByManifest->manifestXml = $oManifestXml->manifestXml;
        $filesByManifest->manifestXml = $this->manifestFile->manifestXml->manifestXml;

        $filesByManifest->collectFilesAndFolders();

        //--------------------------------------------------------------------
        // copy to temp
        //--------------------------------------------------------------------

        $srcRoot = $this->copy2tmpFolder($filesByManifest, $tmpFolder);

        //--------------------------------------------------------------------
        // manual assignments
        //--------------------------------------------------------------------

        //--- root files -------------------------------------------------

        //  manifest file (not included as 'fileName' in manifest file)
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        print ("\r\n");

//        // install script like 'install_rsg2.php'
//        $installScript = (string)$this->manifestFile->manifestXml->manifestXml->scriptfile;
//        $adminPath = $this->fileNamesList->srcRoot . '/administrator/components/' . $this->extName;
//        if (file_exists($adminPath . '/' . $installScript)) {
//            $this->xcopyElement($installScript, $adminPath, $tmpFolder);
//        }

        // Not needed, the license is defined in manifest or may be inside component base path
        //$this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);

        //--- remove package for rsgallery2 ---------------------------------------------

//        // remove prepared pkg_rsgallery2.xml.tmp
//        $packagesTmpFile = $tmpFolder . '/administrator/manifests/packages/pkg_rsgallery2.xml.tmp';
//        if (file_exists($packagesTmpFile)) {
//            unlink($packagesTmpFile);
//        }

//            //--------------------------------------------------------------------
//            // Not changelog to root
//            //--------------------------------------------------------------------
//
//            $changelogPathFileName = $this->fileNamesList->srcRoot . '/administrator/components/com_rsgallery2/';
//            if (file_exists($changelogPathFileName)) {
//                $this->xcopyElement('changelog.xml', $changelogPathFileName, $tmpFolder);
//            }

        //--------------------------------------------------------------------
        // zip to destination
        //--------------------------------------------------------------------

        $zipFileName = $dstRoot . '/' . $this->createExtensionZipName();
        zipItRelative(realpath($tmpFolder), $zipFileName);

        //--------------------------------------------------------------------
        // remove temp
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            delDir($tmpFolder);
        }

        return $zipFileName;
    }

    private function buildPlugin(): string
    {
        //--------------------------------------------------------------------
        // data in manifest file
        //--------------------------------------------------------------------

        //--- update date and version --------------------------------------

        $bareName = $this->shortExtensionName();
        $manifestPathFileName = $this->manifestPathFileName();
        print ("manifestPathFileName: " . $manifestPathFileName . "\r\n");

        //--- update date and version --------------------------------------

        // does read manifest file
        $isChanged = $this->exchangeDataInManifestFile($manifestPathFileName);

        if (!$this->manifestFile->manifestXml->isXmlLoaded) {

            print('exit buildPlugin: error manifestPathFileName could not be read: ' . $manifestPathFileName . "\r\n");
            return '';
        }

        //--------------------------------------------------------------------
        // destination temp folder
        //--------------------------------------------------------------------

        print ('build dir: "' . $this->buildDir . '"' . "\r\n");

        $parentPath = dirname($this->buildDir);
        if (!is_dir($parentPath)) {
            print ('main path does not exist : "' . $parentPath . '"' . "\r\n");
            exit(557);
        }

        if (!is_dir($this->buildDir)) {
            mkdir($this->buildDir, 0777, true);
        }

        $dstRoot = realpath($this->buildDir);
        print ('dstRoot: "' . $dstRoot . '"' . "\r\n");
        $tmpFolder = $this->buildDir . '/tmp';
        print ('temp folder(1): "' . $tmpFolder . '"' . "\r\n");

        //--------------------------------------------------------------------
        // handle temp folder
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            // length big enough to do no damage
            if (strLen($tmpFolder) < 10) {
                exit (555);
            }
            print ('Delete dir: "' . $tmpFolder . '"' . "\r\n");
            delDir($tmpFolder);
        }

        // create tmp folder
        print ('Create dir: "' . $tmpFolder . '"' . "\r\n");
        mkdir($tmpFolder, 0777, true);

        //--------------------------------------------------------------------
        // extract files and folders from manifest
        //--------------------------------------------------------------------

        $filesByManifest = new filesByManifest();

        //--- insert manifestXml ---------------------------------

        $filesByManifest->manifestXml = $this->manifestFile->manifestXml->manifestXml;

        $filesByManifest->collectFilesAndFolders();

        //--------------------------------------------------------------------
        // copy to temp
        //--------------------------------------------------------------------

        $srcRoot = $this->copy2tmpFolder($filesByManifest, $tmpFolder);

        //--------------------------------------------------------------------
        // manual assignments
        //--------------------------------------------------------------------

        //--- root files -------------------------------------------------

        //  manifest file (not included as 'fileName' in manifest file)
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        print ("\r\n");

//        // install script like 'install_rsg2.php'
//        $installScript = (string)$this->manifestFile->manifestXml->manifestXml->scriptfile;
//        $adminPath = $this->fileNamesList->srcRoot . '/administrator/components/' . $this->extName;
//        if (file_exists($adminPath . '/' . $installScript)) {
//            $this->xcopyElement($installScript, $adminPath, $tmpFolder);
//        }


        // Not needed, the license is defined in manifest or may be inside component base path
        //$this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);

        //--- remove package for rsgallery2 ---------------------------------------------

        // remove prepared pkg_rsgallery2.xml.tmp
        $packagesTmpFile = $tmpFolder . '/administrator/manifests/packages/pkg_rsgallery2.xml.tmp';
        if (file_exists($packagesTmpFile)) {
            unlink($packagesTmpFile);
        }

//            //--------------------------------------------------------------------
//            // Not changelog to root
//            //--------------------------------------------------------------------
//
//            $changelogPathFileName = $this->fileNamesList->srcRoot . '/administrator/components/com_rsgallery2/';
//            if (file_exists($changelogPathFileName)) {
//                $this->xcopyElement('changelog.xml', $changelogPathFileName, $tmpFolder);
//            }

        //--------------------------------------------------------------------
        // zip to destination
        //--------------------------------------------------------------------

        $zipFileName = $dstRoot . '/' . $this->createExtensionZipName();
        zipItRelative(realpath($tmpFolder), $zipFileName);

        //--------------------------------------------------------------------
        // remove temp
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            delDir($tmpFolder);
        }

        return $zipFileName;
    }

    private function buildPackage()
    {
        // build component

        // on all module folder build module


        // on all plugins folder build plugins

        // ? Specialities

        // remove temp

    }

    public function executeFile(string $filePathName): int
    {
        // not supported
        return 0;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- buildExtension --------" . "\r\n";

        $OutTxt .= "Not defined yet " . "\r\n";

        /**
         * $OutTxt .= "fileName: " . $this->fileName . "\r\n";
         * $OutTxt .= "fileExtension: " . $this->fileExtension . "\r\n";
         * $OutTxt .= "fileBaseName: " . $this->fileBaseName . "\r\n";
         * $OutTxt .= "filePath: " . $this->filePath . "\r\n";
         * $OutTxt .= "srcRootFileName: " . $this->fileNamesList->srcRootFileName . "\r\n";
         * /**/

        return $OutTxt;
    }

    private function componentVersion()
    {
        // ToDo: option for version
        // ToDo: retrieve version from manifest

        if ($this->componentVersion == '') {

            $versionId = $this->manifestFile->versionId;
            $this->componentVersion = $versionId->outVersionId;

        }

        return $this->componentVersion;
    }

    /**
     * @param filesByManifest $filesByManifest
     * @param string $tmpFolder
     * @return false|string
     */
    public function copy2tmpFolder(filesByManifest $filesByManifest, string $tmpFolder): string|false
    {
        print ("\r\n");
        print ('--- copy to temp ------------------------------' . "\r\n");

        $srcRoot = realpath($this->fileNamesList->srcRoot);

        foreach ($filesByManifest->files as $file) {
            $this->xcopyElement($file, $srcRoot, $tmpFolder);
        }

        foreach ($filesByManifest->folders as $folder) {
            $this->xcopyElement($folder, $srcRoot, $tmpFolder);
        }

        print ("\r\n");

        return $srcRoot;
    }

    private function detectCompVersionFromFile(string $manifestPathFileName)
    {
        $componentVersion = '';

        // ToDo: read file for


        return $componentVersion;
    }

    private function detectCompTypeFromFile(string $manifestPathFileName): string
    {

        $componentType = 'component';

        $isLocal = false;
        if (!empty($this->manifestFile)) {

            if ($this->manifestFile->extType != '') {
                $componentType = $this->manifestFile->extType;
                $isLocal = true;
            }

        }

        //
        if (!$isLocal) {

            // read file
            if (is_file($manifestPathFileName)) {

                $manifestFile = new manifestFile('', $manifestPathFileName);
                $componentType = $manifestFile->extType;

            }
        }

        return $componentType;
    }


    private function check4validInput()
    {
        $isValid = true;

        //option type: "component"
        if (empty ($this->componentType)) {
            print ("option type: not set" . "\r\n");
            $isValid = false;
        }
        //option buildDir: "../../LangMan4Dev"
        if (empty ($this->fileNamesList->srcRoot)) {
            print ("option buildDir: not set" . "\r\n");
            $isValid = false;
        }
        //option buildDir: "../../LangMan4DevProject/.packages"
        if (empty ($this->buildDir)) {
            print ("option buildDir: not set" . "\r\n");
            $isValid = false;
        }
        //option extName: "com_lang4dev"
        if (empty ($this->extName)) {
            print ("option extName: not set" . "\r\n");
            $isValid = false;
        }
        //option extension: "Lang4Dev"
        if (empty ($this->element)) {
            print ("option extension: not set" . "\r\n");
            $isValid = false;
        }


        return $isValid;
    }


} // buildExtension


//========================================================
// ToDo: into folder lib

function xcopyDir($src, $dest)
{
    foreach (scandir($src) as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (!is_readable($src . '/' . $file)) {
            continue;
        }
        if (is_dir($src . '/' . $file)) {
            mkdir($dest . '/' . $file);
            xcopyDir($src . '/' . $file, $dest . '/' . $file);
        } else {
            copy($src . '/' . $file, $dest . '/' . $file);
        }
    }
}

//========================================================
// ToDo: into folder lib

function delDir($dir)
{
    // do not delete from root accidentally
    if ($dir == '') {
        return;
    }
    if (strlen($dir) < 10) {
        return;
    }

    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object)) {
                    delDir($dir . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        rmdir($dir);
    }
}

//========================================================
// ToDo: into folder lib

function zipItRelative($sourcePath, $zipFilename)
{
    print ('sourcePath: "' . $sourcePath . '"' . "\r\n");
    print ('zipFilename: "' . $zipFilename . '"' . "\r\n");

    print ("\r\n");
    print ('--- zip it ------------------------------' . "\r\n");
    print ("\r\n");

    //--- files within folders ------------------------------

    // Initialize archive object
    $zip = new ZipArchive();
//    $zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    if ($zip->open($zipFilename, ZipArchive::CREATE) === true | ZipArchive::OVERWRITE) {

        $sourcePathSlash = str_replace('\\', '/', $sourcePath);
        // print ('glob: "' . $sourcePathSlash . '/' . '"' . "\r\n");
        // print ('sourcePathSlash: "' . $sourcePathSlash . '"' . "\r\n");

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourcePathSlash),
            RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {

            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            // $file = realpath($file);

            if (is_dir($file) === true)
            {
                //$zip->addEmptyDir(str_replace($sourcePathSlash . '/', '', $file . '/'));
                $dirName = str_replace($sourcePathSlash . '/', '', $file . '/');
                $zip->addEmptyDir($dirName);
            }
            else if (is_file($file) === true)
            {
                //$zip->addFromString(str_replace($sourcePathSlash . '/', '', $file), file_get_contents($file));
                $fileName = str_replace($sourcePathSlash . '/', '', $file);
                $zip->addFromString($fileName, file_get_contents($file));
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    } else {

        print ("\r\n" . 'Can not create zip file: "' . $zipFilename . '"' . "\r\n");
    }

}

//function join_paths()
//{
//    $paths = [];
//
//    foreach (func_get_args() as $arg) {
//        if ($arg !== '') {
//            $paths[] = $arg;
//        }
//    }
//
//    return preg_replace('#/+#', '/', join('/', $paths));
//}

