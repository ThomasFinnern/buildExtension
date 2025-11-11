<?php

namespace Finnern\BuildExtension\src\fileNamespace;

use Exception;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileUseDataBase;
use Finnern\BuildExtension\src\fileSinceLib\scanPreHeader;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class createNamespaceFile
================================================================================*/

// ToDo: sort lines
// ToDo: sort lines, keep comment above
// ToDo:  /isForceExtensionId="..."

class createNamespaceFile
{
    public string $fileName;

    public task $task;
    public readonly string $name;

    protected fileUseDataBase|null $oFileUseData;

    public bool $isLogOnly = false;

    private bool $isChanged = false;
    private bool $isLogDev = false;

    private string $company = "";
    private string $rootPath = "";
    private string $extensionType = "";
    private string $extensionName = "";
    private bool $isCompare = true;
    private bool $isForce = false;

    /**-------------------------------------------------------------------
     * construction
     * --------------------------------------------------------------------*/
    public function __construct($srcFile = "")
    {
//        parent::__construct();

        $this->oFileUseData = null; // assign on need

        $this->fileName = $srcFile;
    }

    /*--------------------------------------------------------------------
    assignTask
    --------------------------------------------------------------------*/

    public function assignTask(task $task): int
    {
        $hasError = 0;

        $this->task = $task;

        // $this->taskName = $task->name;

        $options = $task->options;

        // ToDo: Extract assignOption on all assignTask
        foreach ($options->options as $option)
        {
//            $isBaseOption = $this->assignBaseOption($option);
//            if (!$isBaseOption) {
            $this->assignOption($option);//, $task->name);
//            }
        }

        return $hasError;
    }

    /**
     * @param   option  $option
     *
     * @return bool
     */
    // ToDo: Extract assignOption on all assignTask
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = false;
//        $isOptionConsumed = parent::assignOption($option);

        if (!$isOptionConsumed)
        {
            switch (strtolower($option->name))
            {

                case strtolower('company'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->company    = $option->value;
                    $isOptionConsumed = true;
                    break;
                case strtolower('extensionType'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->extensionType = $option->value;
                    $isOptionConsumed    = true;
                    break;
                case strtolower('extensionName'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->extensionName = $option->value;
                    $isOptionConsumed    = true;
                    break;
                case strtolower('rootPath'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->rootPath   = $option->value;
                    $isOptionConsumed = true;
                    break;


                case strtolower('isCompare'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isCompare  = (bool) $option->value;
                    $isOptionConsumed = true;
                    break;
                case strtolower('isForce'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isForce    = (bool) $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isLogOnly'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isLogOnly  = (bool) $option->value;
                    $isOptionConsumed = true;
                    break;
                case strtolower('isLogDev'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isLogDev   = (bool) $option->value;
                    $isOptionConsumed = true;
                    break;


                case strtolower('filename'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->fileName   = $option->value;
                    $isOptionConsumed = true;
                    break;

                default:
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

//    public function assignOptionCallerProjectId(string $callerProjectId):void
//    {
//        $this->callerProjectId = $callerProjectId;
//
//        $this->oFileUseData = fileUseDataFactory::oFileUseData($callerProjectId);
//    }

    public function createNamespace(string $fileName): int
    {
        $hasError = 0;

        try
        {
            print('*********************************************************' . PHP_EOL);
            print('createNamespace' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            if (!empty ($fileName))
            {
                $this->fileName = $fileName;
            }
            else
            {
                $fileName = $this->fileName;
            }

            print ("FileName use: " . $fileName . PHP_EOL);

            $lines = file($fileName);

            $scanCodeLines = new scanPreHeader();

            $this->isChanged = false;
            $outLines        = [];

            $isNamespaceExists = false;
            foreach ($lines as $lineIdx => $line)
            {
                // namespace line created or found
                if (!$isNamespaceExists)
                {
                    $bareLine = $scanCodeLines->nextLine($line);

                    // outside comment, namespace found ?
                    if (strlen($bareLine) > 0 && str_contains($bareLine, 'namespace'))
                    {
                        $isNamespaceExists = true;

                        // compare with expected
                        if ($this->isCompare)
                        {
                            $namespace = $this->createNamespaceLine($fileName);

                            $testBareLine = trim($bareLine);
                            if (trim($bareLine) !== $namespace)
                            {
                                if ($this->isLogDev)
                                {
                                    print ("Delta namespace in line: " . $scanCodeLines->lineNumber . PHP_EOL);
                                    print ("  Found:    '" . trim($bareLine) . "'" . PHP_EOL);
                                    print ("  Expected: '" . $namespace . "'" . PHP_EOL);

                                    $test = "...";
                                }
                            }
                        }

                        // force created
                        if ($this->isForce)
                        {
                            $namespace = $this->createNamespaceLine($fileName);

                            if (trim($bareLine) !== $namespace)
                            {
                                if ($this->isLogDev)
                                {
                                    print ("Forced namespace in line: " . $scanCodeLines->lineNumber . PHP_EOL);
                                    print ("  Found:  '" . trim($bareLine) . "'" . PHP_EOL);
                                    print ("  Forced: '" . $namespace . "'" . PHP_EOL);

                                    $line            = $namespace . PHP_EOL;
                                    $this->isChanged = true;
                                }

                                $line = $this->createNamespaceLine($fileName);
                            }
                        }
                    }
                    else
                    {
                        // In single comment line
                        if ($scanCodeLines->isSingleCommentLine)
                        {
                            $isCreateNamespace = false;

                            // (1) Create before comment line indicating following  _JEXEC line
                            //
                            if (str_contains($line, 'phpcs:disable '))
                            {
                                $isCreateNamespace = true;
                                $isNamespaceExists = true;
                            }

                            // (2) Create before _JEXEC line
                            if (str_contains($bareLine, '_JEXEC'))
                            {
                                $isCreateNamespace = true;
                                $isNamespaceExists = true;
                            }

                            // (3) Create before use namespacing
                            if (str_starts_with(trim($bareLine), 'use '))
                            {
                                $isCreateNamespace = true;
                                $isNamespaceExists = true;
                            }

                            if ($isCreateNamespace)
                            {
                                $namespace = $this->createNamespaceLine($fileName);

                                $outLines[] = "" . PHP_EOL;
                                $outLines[] = $namespace . PHP_EOL;
                                $outLines[] = "" . PHP_EOL;

                                $this->isChanged = true;

                                if ($this->isLogDev)
                                {
                                    print ("Created namespace in line: " . $scanCodeLines->lineNumber . PHP_EOL);
                                    print ("   '" . $namespace . "'" . PHP_EOL);
                                }
                            }
                        }
                    }
                }

                // use actual line
                $outLines[] = $line;
            }

            // on change write to file
            if ($this->isChanged && !$this->isLogOnly)
            {
                $outLines = str_replace("\r", '', $outLines); // remove carriage returns
                $isSaved  = file_put_contents($fileName, $outLines);

                print (">> Changed FileName: " . $fileName . PHP_EOL);
            }

            if ($this->isChanged && $this->isLogDev)
            {
                print (">>>===============================================" . PHP_EOL);
                print ("~~~ Changed ~~~ FileName: " . $fileName . PHP_EOL);
                print (">>>===============================================" . PHP_EOL);

//                foreach ($outLines as $lineIdx => $line)
//                {
//                    //print ($line . PHP_EOL);
//                    print ($line);
//                }

//                print ("<<<===============================================" . PHP_EOL);
            }

        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit createNamespace: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    // namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;
    // e:\wamp64\www\joomla5x\administrator\components\com_rsgallery2\src\Model\GalleriesModel.php
    // rootPath: e:\wamp64\www\joomla5x\

    private function createNamespaceLine(string $fileName)
    {
        $namespace = "";

        $company       = $this->company;
        $rootPath      = $this->rootPath;
        $extensionType = $this->extensionType;
        $extensionName = $this->extensionName;

        $autoType = $this->extractType(realpath(dirname($fileName)), $extensionType);

        $behindPath = $this->extractBehindPathParts($fileName, $rootPath, $autoType);

        $namespace = 'namespace ' . $company . '\\' . ucfirst($autoType) . '\\' . $extensionName . '\\' . $behindPath . ';';

        return $namespace;
    }


    // namespace Rsgallery2\Component\Rsgallery2\Administrator\Helper;
    // e:\wamp64\www\joomla5x\administrator\components\com_rsgallery2\helpers\Galleries.php
    // namespace Rsgallery2\Component\Rsgallery2\Administrator\Model;
    // d:\Entwickl\2025\_gitHub\RSGallery2_J4\administrator\components\com_rsgallery2\src\Model\GalleryModel.php

    // namespace Rsgallery2\Component\Rsgallery2\Site\Model;
    // e:\wamp64\www\joomla5x\components\com_rsgallery2\src\Model\ImageFileModel.php

    // namespace Joomla\Module\LatestActions\Administrator\Dispatcher;
    // e:\wamp64\www\joomla5x\administrator\modules\mod_latestactions\src\Dispatcher\Dispatcher.php
    // namespace Joomla\Module\ArticlesCategories\Site\Helper;
    // e:\wamp64\www\joomla5x\modules\mod_articles_categories\src\Helper\ArticlesCategoriesHelper.php

    // namespace Joomla\Plugin\Content\Finder\Extension;
    // e:\wamp64\www\joomla5x\plugins\content\finder\src\Extension\Finder.php
    private function extractBehindPathParts(string $fileName, string $rootPath, string $extensionType)
    {
        $behindPath = "";

        //--- align both paths with same DIRECTORY_SEPARATOR -------------------------------

        $filePath        = dirname($fileName);
        $unifiedPathName = realpath($filePath);
        if ($unifiedPathName === false)
        {
            // try to replace slashed instead
            // dos
            $unifiedPathName = str_replace('/', '\\', $fileName);
        }

        $unifiedRootPath = realpath($rootPath);
        if ($unifiedRootPath === false)
        {
            // try to replace slashed instead
            // dos
            $unifiedRootPath = str_replace('/', '\\', $rootPath);
        }

        //--- remove root -----------------------------------------------

        $behindPathBackslash = "";

        // does it match exactly
        if (str_starts_with($unifiedPathName, $unifiedRootPath))
        {
            $behindPathSlash     = substr($unifiedPathName, strlen($unifiedRootPath));
            $behindPathBackslash = str_replace('/', '\\', $behindPathSlash);

            if (str_starts_with($behindPathBackslash, '\\'))
            {
                $behindPathBackslash = substr($behindPathBackslash, 1);
            }

        }

        //--- detect and add behind parts ----------------------------

        $behindPath = "";

        // lower case
        $behindPathBackslash = strtolower($behindPathBackslash);

        $parts = explode('\\', $behindPathBackslash);

        // example \components\com_rsgallery2\helper
        if (count($parts) > 2)
        {

            //--- component administrator/site -------------------------------------------

//            if ($extensionType = 'component') {
//                if($parts[0] == 'administrator') {
//                    $behindPath .= ucfirst('administrator');
//                    array_shift ($parts);
//                } else {
//                    $behindPath .= ucfirst('site');
//                }
//            }


            switch ($extensionType)
            {

                case 'component':
                    // administrator/site
                    if ($parts[0] == 'administrator')
                    {
                        $behindPath .= ucfirst('administrator') . '\\';

                        // align to site length
                        array_shift($parts);
                    }
                    else
                    {
                        if ($parts[0] == 'api')
                        {
                            $behindPath .= ucfirst('api') . '\\';

                            // align to site length
                            array_shift($parts);
                        }
                        else
                        {
                        $behindPath .= ucfirst('site') . '\\';
                        }
                    }

                    // 'component'
                    array_shift($parts);

                    // component name
                    array_shift($parts);

                    // '/src'
                    if (!empty($parts))
                    {
                        if ($parts[0] == 'src')
                        {
                            array_shift($parts);
                        }
                    }

                    // following folders will be appended
                    foreach ($parts as $part)
                    {
                        $behindPath .= ucfirst($part) . '\\';
                    }

                    break;

                case 'module':
                    // administrator/site
                    if ($parts[0] == 'administrator')
                    {
                        $behindPath .= ucfirst('administrator') . '\\';

                        // align to site length
                        array_shift($parts);
                    }
                    else
                    {
                        $behindPath .= ucfirst('site') . '\\';
                    }

                    // 'modules'
                    array_shift($parts);

                    // component name
                    array_shift($parts);

                    // '/src'
                    if (!empty($parts))
                    {
                        if ($parts[0] == 'src')
                        {
                            array_shift($parts);
                        }
                    }

                    // following folders will be appended
                    foreach ($parts as $part)
                    {
                        $behindPath .= ucfirst($part) . '\\';
                    }
                    break;

                case 'plugin':

                    // 'plugin'
                    array_shift($parts);

                    // plugin area
                    $behindPath .= ucfirst($parts[0]) . '\\';
                    array_shift($parts);

                    // plugin  name
                    array_shift($parts);

                    // '/src'
                    if (!empty($parts))
                    {
                        if ($parts[0] == 'src')
                        {
                            array_shift($parts);
                        }
                    }

                    // following folders will be appended
                    foreach ($parts as $part)
                    {
                        $behindPath .= ucfirst($part) . '\\';
                    }
                    break;

                default:
                    print ("!!! Unknown extension type: '" . $extensionType . "'" . PHP_EOL);
                    print ("    at '" . $fileName . "'" . PHP_EOL);

                    break;
            }

        }

        if(str_ends_with($behindPath, '\\')) {
            $behindPath = substr($behindPath, 0, -1);
        }

        return $behindPath;
    }

    private function extractType(string $filePath, string $extensionType): string
    {
        $filePath = str_replace('/', '\\', $filePath);

        // component
        if (str_contains($filePath, '\\components\\'))
        {
            $extensionType = 'component';
        }

        // plugin
        if (str_contains($filePath, '\\plugins\\'))
        {
            $extensionType = 'plugin';
        }

        // model
        if (str_contains($filePath, '\\modules\\'))
        {
            $extensionType = 'module';
        }

        // ? API

        return $extensionType;
    }
}
