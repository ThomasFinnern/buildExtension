<?php

namespace Finnern\BuildExtension\src;


use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use ZipArchive;

use Finnern\BuildExtension\src\fileManifestLib\manifestXml;
//use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\fileManifestLib\manifestFile;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;

//use Finnern\BuildExtension\src\versionLib\versionId;

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
    private string $extName='';

//    private bool $isIncrementVersion_build = false;

    // todo: replace by private manifestXml $manifestXml;
    private manifestfile $manifestFile;

    private string $componentVersion = '';

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

    // Task name with options
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);

            // base options are already handled
            if (!$isBaseOption) {
                // $isVersionOption = $this->versionId->assignVersionOption($option);
                // ToDo: include version better into manifest
                // -> same increase flags should be ...
                // if (!$isVersionOption) {
                $isManifestOption = $this->manifestFile->assignManifestOption($option);
                // }
            }

//            if (!$isBaseOption && !$isVersionOption && !$isManifestOption) {
//            if (!$isBaseOption && !$isVersionOption) {
            if (!$isBaseOption && !$isManifestOption) {

                $this->assignBuildExtensionOption($option, $task->name);
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
    public function assignBuildExtensionOption(mixed $option): bool
    {
        $isBuildExtensionOption = false;

        switch (strtolower($option->name)) {
            case 'builddir':
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->buildDir = $option->value;
                $isBuildExtensionOption  = true;
                break;

            // com_rsgallery2'
            case 'name':
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->extName = $option->value;
                $isBuildExtensionOption  = true;
                break;

//                    // component name like rsgallery2 (but see above)
//                    case '':
//                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                        $this->name = $option->value;
//                    $isBuildExtensionOption  = true;
//                        break;

            // extension (<element> name like RSGallery2
            case 'element':
            case 'extension':
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->element = $option->value;
                $isBuildExtensionOption  = true;
                break;

            case 'type':
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->componentType = $option->value;
                $isBuildExtensionOption  = true;
                break;

//            case 'isincrementversion_build':
//                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                $this->isIncrementVersion_build = $option->value;
//                $isBuildExtensionOption  = true;
//                break;

//                    case 'X':
//                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                        break;
//
//                    case 'Y':
//                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                        break;
//
//                    case 'Z':
//                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//                        break;

            default:
                print ('!!! error: required option is not supported: ' .  $option->name . ' !!!' . "\r\n");
        } // switch

        return $isBuildExtensionOption;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print ("Execute buildExtension: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        //--- validation checks --------------------------------------

        $isValid = $this->check4validInput();

        if ($isValid) {
            $componentType = $this->componentType();

            switch (strtolower($componentType)) {
                case 'component':
                    $this->buildComponent();

                    break;

                case 'module':
                    $this->buildModule();
                    break;

                case 'plugin':
                    $this->buildPlugin();
                    break;

                case 'package':
                    $this->buildPackage();
                    break;

                default:
                    print ('!!! Default componentType: ' . $componentType . ', No build done !!!');
            } // switch
        }

        return (0);
    }

    private function componentType() : string
    {
        if ($this->componentType == '') {

            $this->componentType = $this->detectCompTypeFromFile(
                $this->manifestPathFileName);

        }

        return $this->componentType;
    }

    private function buildComponent()
    {
        //--------------------------------------------------------------------
        // data in manifest file
        //--------------------------------------------------------------------

        //--- manifest file name --------------------------------------

        $bareName = $this->shortExtensionName();
        $manifestPathFileName = $this->manifestPathFileName();
        print ('manifestPathFileName: "' . $manifestPathFileName . '"' . "\r\n");

        //--- update date and version --------------------------------------

        $isChanged = $this->exchangeDataInManifestFile($manifestPathFileName);

        //--- update second admin xml file --------------------------------------

        $manifestAdminPathFileName = $this->manifestAdminPathFileName();
        print('manifestAdminPathFileName: "' . $manifestAdminPathFileName . '"' . "\r\n");
        copy($manifestPathFileName, $manifestAdminPathFileName);

        //--------------------------------------------------------------------
        // destination temp folder
        //--------------------------------------------------------------------

        print ('build dir: "' . $this->buildDir . '"' . "\r\n");

        $parentPath = dirname ($this->buildDir);
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
        // copy to temp
        //--------------------------------------------------------------------

        $srcRoot = realpath($this->srcRoot);

        // ToDo: Follow manifest file sections instead of ...

        //--- folder ----------------------------------------------------------------

        // folder administrator exists
        if (file_exists($srcRoot . "/" . 'administrator')) {
            $this->xcopyElement('administrator', $srcRoot, $tmpFolder);
        }
        // folder components exists
        if (file_exists($srcRoot . "/" . 'components')) {
            $this->xcopyElement('components', $srcRoot, $tmpFolder);
        }
        // folder api exists
        if (file_exists($srcRoot . "/" . 'api')) {
            $this->xcopyElement('api', $srcRoot, $tmpFolder);
        }
        // folder media exists
        if (file_exists($srcRoot . "/" . 'media')) {
            $this->xcopyElement('media', $srcRoot, $tmpFolder);
        }

        // must be created separately ToDo: create anyhow for ? package ?
//            // modules
//            if (file_exists($srcRoot . "/" . 'modules')) {
//                $this->xcopyElement('modules', $srcRoot, $tmpFolder);
//            }

        // must be created separately ToDo: create anyhow for ? package ?
//            // plugins
//            if (file_exists($srcRoot . "/" . 'plugins')) {
//                $this->xcopyElement('plugins', $srcRoot, $tmpFolder);
//            }

        //--- files ----------------------------------------------------------------

        // manifest file like 'rsgallery2.xml'
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        // install script like 'install_rsg2.php'
        if ( ! empty($this->manifestFile->scriptFile)) {
            $this->xcopyElement($this->manifestFile->scriptFile, $srcRoot, $tmpFolder);
        }

        $this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);

        if (file_exists($srcRoot . "/" . 'index.html.xml')) {
            $this->xcopyElement('index.html', $srcRoot, $tmpFolder);
        }

        if (file_exists($srcRoot . "/" . 'changelog.xml')) {
            $this->xcopyElement('changelog.xml', $srcRoot, $tmpFolder);
        }

        //--- extras -------------------------------------------------

        // remove prepared pkg_rsgallery2.xml.tmp
        $packagesTmpFile = $tmpFolder . '/administrator/manifests/packages/pkg_rsgallery2.xml.tmp';
        if (file_exists($packagesTmpFile)) {
            unlink($packagesTmpFile);
        }

//            //--------------------------------------------------------------------
//            // Not changelog to root
//            //--------------------------------------------------------------------
//
//            $changelogPathFileName = $this->srcRoot . '/administrator/components/com_rsgallery2/';
//            if (file_exists($changelogPathFileName)) {
//                $this->xcopyElement('changelog.xml', $changelogPathFileName, $tmpFolder);
//            }

        //--------------------------------------------------------------------
        // zip to destination
        //--------------------------------------------------------------------

        $zipFileName = $dstRoot . '/' . $this->createComponentZipName();
        zipItRelative(realpath($tmpFolder), $zipFileName);

        //--------------------------------------------------------------------
        // remove temp
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            delDir($tmpFolder);
        }
    }


    private function manifestPathFileName(): string
    {
        if ($this->manifestPathFileName == '') {

            $name = $this->shortExtensionName();

            $this->manifestPathFileName = $this->srcRoot . '/' . $name . '.xml';
        }

        return $this->manifestPathFileName;
    }

    private function manifestAdminPathFileName(): string
    {
        if ($this->manifestAdminPathFileName == '') {

            $name = $this->shortExtensionName();

            $this->manifestAdminPathFileName = $this->srcRoot
                . '/administrator/components/'
                . $this->extName . '/' . $name . '.xml';
        }

        return $this->manifestAdminPathFileName;
    }

    // ToDo: move/create also in to manifest.php file ?
    private function shortExtensionName(): string
    {
        $extName = $this->extName;

        print ('extension extName: "' . $extName . '"' . "\r\n");

        // com / mod / plg extension
        if (str_starts_with($extName, 'com_'))
        {
            // Standard
            $extName = substr($extName, 4);
            // $extName = 'com_' . substr($extName, 4);

        } else {

            if (str_starts_with($extName, 'mod_')) {
                // $extName = substr($extName, 4);
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
        if (str_starts_with($name, 'com_'))
        {
            // Standard
            $name = substr($name, 4);
            // $name = 'com_' . substr($name, 4);

        } else {

            if (str_starts_with($name, 'mod_')) {
                // $idx = strpos($name, '_', strlen('mod_')) + 1;
                // $name = 'mod_' . substr($name, $idx);
            } else {

                if (str_starts_with($name, 'plg_')) {
                    // $idx = strpos($name, '_', strlen('plg_')) + 1;
                    // $name = 'plg_' . substr($name, $idx);
                }
            }
        }

        return $name;
    }

    /**
     * @param   string  $manifestPathFileName
     *
     * @return false
     */
    private function exchangeDataInManifestFile(string $manifestPathFileName) {

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
                $manifestFile->isUpdateCreationDate = true;

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

                $isSaved = $manifestFile->writeFile();

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

            if (is_dir($srcPath)) {
                mkdir($dstPath);
                xcopyDir($srcPath, $dstPath);
            } else {
                if (is_file($srcPath)) {
                    copy($srcPath, $dstPath);
                } else {

                    print ("%%% warning path / file could not be copied: " . $srcPath . "\r\n");

                }
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
    }

    private function createComponentZipName()
    {
        // rsgallery2.5.0.12.4_20240818.zip

        // ToDo: option for version
        // ToDo: retrieve version from manifest

        // $date = "20240824";
        $date_format = 'Ymd';
        $date = date($date_format);

        $componentVersion  = $this->componentVersion ();
        $name = $this->destinationExtensionName();

        $ZipName = $name . '.' . $componentVersion . '_' . $date . '.zip';

        return $ZipName;
    }

    private function buildModule()
    {
        //--------------------------------------------------------------------
        // data in manifest file
        //--------------------------------------------------------------------

        //--- update date and version --------------------------------------

        $bareName = $this->shortExtensionName();
        $manifestPathFileName = $this->manifestPathFileName();
        print ("manifestPathFileName: " . $manifestPathFileName . "\r\n");

        $isChanged = $this->exchangeDataInManifestFile($manifestPathFileName);

        //--------------------------------------------------------------------
        // destination temp folder
        //--------------------------------------------------------------------

        print ('build dir: "' . $this->buildDir . '"' . "\r\n");

        $parentPath = dirname ($this->buildDir);
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
        // copy to temp
        //--------------------------------------------------------------------

        $srcRoot = realpath($this->srcRoot);

        //--- folder ----------------------------------------------------------------

        // folder src exists
        if (file_exists($srcRoot . "/" . 'src')) {
            $this->xcopyElement('src', $srcRoot, $tmpFolder);
        }
        // folder language exists
        if (file_exists($srcRoot . "/" . 'language')) {
            $this->xcopyElement('language', $srcRoot, $tmpFolder);
        }

        // folder media exists
        if (file_exists($srcRoot . "/" . 'media')) {
            $this->xcopyElement('media', $srcRoot, $tmpFolder);
        }

        // folder tmpl exists
        if (file_exists($srcRoot . "/" . 'tmpl')) {
            $this->xcopyElement('tmpl', $srcRoot, $tmpFolder);
        }

        //--- files ----------------------------------------------------------------

        // manifest file like 'rsgallery2.xml'
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        // install script like 'install_rsg2.php'
        if ( ! empty($this->manifestFile->scriptFile)) {
            $this->xcopyElement($this->manifestFile->scriptFile, $srcRoot, $tmpFolder);
        }

        if (file_exists($srcRoot . "/" . 'LICENSE.txt')) {
            $this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);
        } else {
            $testFolder = $srcRoot . "../../../" . 'administrator/component/' . $bareName . '/';
            if (file_exists($testFolder . "/" . 'LICENSE.txt')) {

                $this->xcopyElement('LICENSE.txt', $testFolder, $tmpFolder);
            }
        }

        if (file_exists($srcRoot . "/" . 'index.html.xml')) {
            $this->xcopyElement('index.html', $srcRoot, $tmpFolder);
        } else {
            $testFolder = $srcRoot . "../../../" . 'administrator/component/' . $bareName . '/';
            if (file_exists($testFolder . "/" . 'index.html')) {

                $this->xcopyElement('index.html', $testFolder, $tmpFolder);
            }
        }

        if (file_exists($srcRoot . "/" . 'changelog.xml')) {
            $this->xcopyElement('changelog.xml', $srcRoot, $tmpFolder);
        } else {
            $testFolder = $srcRoot . "../../../" . 'administrator/component/' . $bareName . '/';
            if (file_exists($testFolder . "/" . 'changelog.xml')) {

                $this->xcopyElement('changelog.xml', $testFolder, $tmpFolder);
            }
        }

        //--------------------------------------------------------------------
        // zip to destination
        //--------------------------------------------------------------------

        $zipFileName = $dstRoot . '/' . $this->createComponentZipName();
        zipItRelative(realpath($tmpFolder), $zipFileName);

        //--------------------------------------------------------------------
        // remove temp
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            delDir($tmpFolder);
        }

    }

    private function buildPlugin()
    {
        //--------------------------------------------------------------------
        // data in manifest file
        //--------------------------------------------------------------------

        //--- update date and version --------------------------------------

        $bareName = $this->shortExtensionName();
        $manifestPathFileName = $this->manifestPathFileName();
        print ("manifestPathFileName: " . $manifestPathFileName . "\r\n");

        $isChanged = $this->exchangeDataInManifestFile($manifestPathFileName);

        //--------------------------------------------------------------------
        // destination temp folder
        //--------------------------------------------------------------------

        print ('build dir: "' . $this->buildDir . '"' . "\r\n");

        $parentPath = dirname ($this->buildDir);
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
        // copy to temp
        //--------------------------------------------------------------------

        $srcRoot = realpath($this->srcRoot);

        // ToDo: Follow manifest file sections instead of ...

        //--- folder ----------------------------------------------------------------

        // folder src exists
        if (file_exists($srcRoot . "/" . 'src')) {
            $this->xcopyElement('src', $srcRoot, $tmpFolder);
        }
        // folder language exists
        if (file_exists($srcRoot . "/" . 'language')) {
            $this->xcopyElement('language', $srcRoot, $tmpFolder);
        }
        // folder media exists
        if (file_exists($srcRoot . "/" . 'services')) {
            $this->xcopyElement('services', $srcRoot, $tmpFolder);
        }

        //--- files ----------------------------------------------------------------

        // manifest file like 'rsgallery2.xml'
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        // install script like 'install_rsg2.php'
        if ( ! empty($this->manifestFile->scriptFile)) {
            $this->xcopyElement($this->manifestFile->scriptFile, $srcRoot, $tmpFolder);
        }

        if (file_exists($srcRoot . "/" . 'LICENSE.txt')) {
            $this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);
        } else {
            $testFolder = $srcRoot . "../../../" . 'administrator/component/' . $bareName . '/';
            if (file_exists($testFolder . "/" . 'LICENSE.txt')) {

                $this->xcopyElement('LICENSE.txt', $testFolder, $tmpFolder);
            }
        }

        if (file_exists($srcRoot . "/" . 'index.html.xml')) {
            $this->xcopyElement('index.html', $srcRoot, $tmpFolder);
        } else {
            $testFolder = $srcRoot . "../../../" . 'administrator/component/' . $bareName . '/';
            if (file_exists($testFolder . "/" . 'index.html')) {

                $this->xcopyElement('index.html', $testFolder, $tmpFolder);
            }
        }

        if (file_exists($srcRoot . "/" . 'changelog.xml')) {
            $this->xcopyElement('changelog.xml', $srcRoot, $tmpFolder);
        } else {
            $testFolder = $srcRoot . "../../../" . 'administrator/component/' . $bareName . '/';
            if (file_exists($testFolder . "/" . 'changelog.xml')) {

                $this->xcopyElement('changelog.xml', $testFolder, $tmpFolder);
            }
        }

        //--------------------------------------------------------------------
        // zip to destination
        //--------------------------------------------------------------------

        $zipFileName = $dstRoot . '/' . $this->createComponentZipName();
        zipItRelative(realpath($tmpFolder), $zipFileName);

        //--------------------------------------------------------------------
        // remove temp
        //--------------------------------------------------------------------

        // remove tmp folder
        if (is_dir($tmpFolder)) {
            delDir($tmpFolder);
        }
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
        return (0);
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
         * $OutTxt .= "srcRootFileName: " . $this->srcRootFileName . "\r\n";
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

    private function detectCompVersionFromFile(string $manifestPathFileName)
    {
        $componentVersion = '';

        // ToDo: read file for


        return $componentVersion;
    }

    private function detectCompTypeFromFile(string $manifestPathFileName) {

        $componentType = 'component';

        $isLocal = false;
        if ( ! empty($this->manifestFile)) {

            if ($this->manifestFile->extType != '') {
                $componentType = $this->manifestFile->extType;
                $isLocal       = true;
            }

        }

        //
        if ( ! $isLocal) {

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
        if (empty ($this->srcRoot)) {
            print ("option buildDir: not set" . "\r\n");
            $isValid = false;
        }
        //option buildDir: "../../LangMan4DevProject/.packages"
        if (empty ($this->buildDir)) {
            print ("option buildDir: not set" . "\r\n");
            $isValid = false;
        }
        //option name: "com_lang4dev"
        if (empty ($this->extName)) {
            print ("option name: not set" . "\r\n");
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
        if (is_dir($src . '/' . $file) && ($file != '.') && ($file != '..')) {
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

function zipItRelative($rootPath, $zipFilename)
{
    print ('rootPath: "' . $rootPath . '"' . "\r\n");
    print ('zipFilename: "' . $zipFilename . '"' . "\r\n");

    // Initialize archive object
    $zip = new ZipArchive();
    $zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY,
    );

    foreach ($files as $name => $file) {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        if (!$file->isDir()) {
            // Add current file to archive
            print ('.');

            $zip->addFile($filePath, $relativePath);
        } else {
            if ($relativePath != '') {
                print ('>');
                $zip->addEmptyDir($relativePath);
            }
        }
    }

    print ( "\r\n" . 'exit zipping' . "\r\n");

    // Zip archive will be created only after closing object
    $zip->close();
}

function join_paths()
{
    $paths = [];

    foreach (func_get_args() as $arg) {
        if ($arg !== '') {
            $paths[] = $arg;
        }
    }

    return preg_replace('#/+#', '/', join('/', $paths));
}

