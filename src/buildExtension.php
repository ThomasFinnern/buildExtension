<?php

namespace ExecuteTasks;

require_once "./baseExecuteTasks.php";
//require_once "./fileNamesList.php";
require_once "./iExecTask.php";
require_once "./manifestFile.php";
require_once "./task.php";
//require_once "./versionId.php";

use Exception;
//use FileNamesList\fileNamesList;
use ManifestFile\manifestFile;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use task\task;
//use VersionId\versionId;
use ZipArchive;

$HELP_MSG = <<<EOT
    >>>
    class buildRelease

    ToDo: option commands , example

    <<<
    EOT;


/*================================================================================
Class buildRelease
================================================================================*/

class buildRelease extends baseExecuteTasks
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
    private string $name;

//    private bool $isIncrementVersion_build = false;

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
//            print ("Construct buildRelease: " . "\r\n");
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

                $this->assignBuildReleaseOption($option, $task->name);
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
    public function assignBuildReleaseOption(mixed $option): bool
    {
        $isBuildReleaseOption = false;

        switch (strtolower($option->name)) {
            case 'builddir':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->buildDir = $option->value;
                $isBuildReleaseOption  = true;
                break;

            // com_rsgallery2'
            case 'name':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->name = $option->value;
                $isBuildReleaseOption  = true;
                break;

//                    // component name like rsgallery2 (but see above)
//                    case '':
//                        print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//                        $this->name = $option->value;
//                    $isBuildReleaseOption  = true;
//                        break;

            // extension (<element> name like RSGallery2
            case 'element':
            case 'extension':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->element = $option->value;
                $isBuildReleaseOption  = true;
                break;

            case 'type':
                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
                $this->componentType = $option->value;
                $isBuildReleaseOption  = true;
                break;

//            case 'isincrementversion_build':
//                print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//                $this->isIncrementVersion_build = $option->value;
//                $isBuildReleaseOption  = true;
//                break;

//                    case 'X':
//                        print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//                        break;
//
//                    case 'Y':
//                        print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//                        break;
//
//                    case 'Z':
//                        print ('     option: ' . $option->name . ' ' . $option->value . "\r\n");
//                        break;

            default:
                print ('!!! error: required option is not supported: ' .  $option->name . ' !!!' . "\r\n");
        } // switch

        return $isBuildReleaseOption;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print ("Execute buildRelease: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

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
                print ('!!! Default componentType: ' . $componentType . ', No build done !!!' );
        } // switch


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

        //--- update date and version --------------------------------------

        $bareName = $this->shortExtensionName();
        $manifestPathFileName = $this->manifestPathFileName();
        print ("manifestPathFileName: " . $manifestPathFileName . "\r\n");

        $isChanged = $this->exchangeDataInManifestFile($manifestPathFileName);

        //--- update second admin xml file --------------------------------------

        $manifestAdminPathFileName = $this->manifestAdminPathFileName();
        print("manifestAdminPathFileName: " . $manifestAdminPathFileName . "\r\n");
        copy($manifestPathFileName, $manifestAdminPathFileName);

        //--------------------------------------------------------------------
        // destination temp folder
        //--------------------------------------------------------------------

        $dstRoot = realpath($this->buildDir);
        print ('build dir: "' . $this->buildDir . '"' . "\r\n");
        print ('dstRoot: "' . $dstRoot . '"' . "\r\n");
        $tmpFolder = $this->buildDir . '/tmp';
        print ('temp folder(1): "' . $tmpFolder . '"' . "\r\n");
//        $tmpFolder = realpath($tmpFolder);
//        print ('temp folder(2): "' .  $tmpFolder . '"' . "\r\n");

        // create .packages folder
        if (!is_dir($dstRoot)) {
            print ('create dir: "' . $dstRoot . '"' . "\r\n");
            mkdir($dstRoot, 0777, true);

            exit(556);
        }

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

        // manifest file like 'rsgallery2.xml'
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        // install script like 'install_rsg2.php'
        if ( ! empty($this->manifestFile->scriptFile)) {
            $this->xcopyElement($this->manifestFile->scriptFile, $srcRoot, $tmpFolder);
        }

        $this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);

		$this->xcopyElement('index.html', $srcRoot, $tmpFolder);
//            // Do copy the double rsgallery2.xml
//            $adminFolder = $tmpFolder . '/administrator/components/com_rsgallery2';
//            $this->xcopyElement('rsgallery2.xml', $srcRoot, $adminFolder);

        // remove pkg_rsgallery2.xml.tmp
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

            $this->manifestAdminPathFileName = $this->srcRoot . '/administrator/components/com_lang4dev/' . $name . '.xml';
        }

        return $this->manifestAdminPathFileName;
    }

    // ToDo: move/create also in to manifest.php file ?
    private function shortExtensionName(): string
    {
        $name = $this->name;

        // com / mod extension
        if (str_starts_with($name, 'com_')
            || str_starts_with($name, 'mod_'))
        {
            // Stanadard
            $name = substr($name, 4);

        } else {

            if (str_starts_with($name, 'plg_')){
                $idx = strpos($name, '_', strlen('plg_')) + 1;
                $name = substr($name, $idx);
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
////                    print ("buildRelease: isBuildRelease: " .  $this->versionId->isBuildRelease  . "\r\n");
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
        $name = $this->shortExtensionName();

        $ZipName = $name . '.' . $componentVersion . '_' . $date . '.zip';

        return $ZipName;
    }

    private function buildModule()
    {


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

        $dstRoot = realpath($this->buildDir);
        print ('build dir: "' . $this->buildDir . '"' . "\r\n");
        print ('dstRoot: "' . $dstRoot . '"' . "\r\n");
        $tmpFolder = $this->buildDir . '/tmp';
        print ('temp folder(1): "' . $tmpFolder . '"' . "\r\n");
//        $tmpFolder = realpath($tmpFolder);
//        print ('temp folder(2): "' .  $tmpFolder . '"' . "\r\n");

        // create .packages folder
        if (!is_dir($dstRoot)) {
            print ('create dir: "' . $dstRoot . '"' . "\r\n");
            mkdir($dstRoot, 0777, true);

            exit(556);
        }

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

        // folder components exists
        if (file_exists($srcRoot . "/" . 'src')) {
            $this->xcopyElement('src', $srcRoot, $tmpFolder);
        }
        // folder api exists
        if (file_exists($srcRoot . "/" . 'language')) {
            $this->xcopyElement('language', $srcRoot, $tmpFolder);
        }
        // folder media exists
        if (file_exists($srcRoot . "/" . 'services')) {
            $this->xcopyElement('services', $srcRoot, $tmpFolder);
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

        // manifest file like 'rsgallery2.xml'
        $this->xcopyElement($bareName . '.xml', $srcRoot, $tmpFolder);
        // install script like 'install_rsg2.php'
        if ( ! empty($this->manifestFile->scriptFile)) {
            $this->xcopyElement($this->manifestFile->scriptFile, $srcRoot, $tmpFolder);
        }

//        // folder administrator exists
//        if (file_exists($srcRoot . "/" . 'administrator')) {
//            $this->xcopyElement('administrator', $srcRoot, $tmpFolder);
//        }

        if (file_exists($srcRoot . "/" . 'LICENSE.txt')) {
            $this->xcopyElement('LICENSE.txt', $srcRoot, $tmpFolder);
        } else {
            $testFolder = $srcRoot . "../../../" . 'administrator/component/' . $bareName . '/';
            if (file_exists($testFolder . "/" . 'LICENSE.txt')) {

                $this->xcopyElement('LICENSE.txt', $testFolder, $tmpFolder);
            }
        }

        if (file_exists($srcRoot . "/" . 'LICENSE.txt')) {
            $this->xcopyElement('index.html', $srcRoot, $tmpFolder);
        } else {
            
            
            
        }

//            // Do copy the double rsgallery2.xml
//            $adminFolder = $tmpFolder . '/administrator/components/com_rsgallery2';
//            $this->xcopyElement('rsgallery2.xml', $srcRoot, $adminFolder);

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
        $OutTxt .= "--- buildRelease --------" . "\r\n";

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


} // buildRelease


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
            if ($relativePath !== false) {
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

