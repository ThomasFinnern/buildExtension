<?php

namespace Finnern\BuildExtension\src\fileNamesLib;

//use \DateTime;
use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;

//use Finnern\BuildExtension\src\fileNamesLib\fithFileName;
//use Finnern\BuildExtension\src\fileNamesLib\fithFolderName;
// use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;

use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\task;

/**
 * ToDo:
 * folder name regex
 * filename regex
 * /**/
/*================================================================================
Class fileNamesList
================================================================================*/

class fileNamesList extends baseExecuteTasks
    implements executeTasksInterface
{

    /** @var fithFileName[] $fileNames */
    public array $fileNames;

    private bool $isIncludeExt = false;

    /** @var string [] */
    private array $includeExtList;

    private bool $isExcludeExt = false;
    /** @var string [] */
    private array $excludeExtList;

    private bool $isExcludeFolder = false;
    /** @var string [] */
    private array $excludeFolderList;

    private bool $isWriteListToFile = false;

    private string $listFileName = "";

    public string $srcRoot = "";

    public bool $isNoRecursion = false;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct(
        $srcPath = '',
        $includeExt = '',
        $excludeExt = '',
        $isNoRecursion = '',
        $writeListToFile = '',
    )
    {
        $hasError = 0;

        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("construct: " . PHP_EOL);
//            print ("path: " . $path . PHP_EOL);
//            print ("includeExt: " . $includeExt . PHP_EOL);
//            print ("excludeExt: " . $excludeExt . PHP_EOL);
//            print ("isNoRecursion: " . $isNoRecursion . PHP_EOL);
//            print ("writeListToFile: " . $writeListToFile . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->clean();

            $this->assignParameters($srcPath, $includeExt, $excludeExt, $isNoRecursion, $writeListToFile);

        } /*--- exception ----------------------------------------------------*/
        catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    scan4Filenames
    --------------------------------------------------------------------*/

    public function clean()
    {
        $this->fileNames = [];

        $this->srcRoot = "";

        $this->isIncludeExt = false;
        $this->includeExtList = [];

        $this->isExcludeExt = false;
        $this->excludeExtList = [];

        $this->isNoRecursion = false;

        $this->isWriteListToFile = false;

        $this->listFileName = "";
    }

    /**
     * @param mixed $srcPath
     * @param mixed $includeExt
     * @param mixed $excludeExt
     * @param mixed $isNoRecursion
     * @param mixed $writeListToFile
     *
     * @return void
     */
    public function assignParameters(
        mixed $srcPath,
        mixed $includeExt,
        mixed $excludeExt,
        mixed $isNoRecursion,
        mixed $writeListToFile,
    ): void
    {
        $this->srcRoot = $srcPath;

        [$this->isIncludeExt, $this->includeExtList] =
            $this->splitExtensionString($includeExt);
        [$this->isExcludeExt, $this->excludeExtList] =
            $this->splitExtensionString($excludeExt);

        $this->isNoRecursion = $isNoRecursion;

        if (!empty ($writeListToFile)) {
            $this->isWriteListToFile = true;

            $this->listFileName = $writeListToFile;
        }
    }

    /**
     * @param option $option
     * @return bool true on option is consumed
     */
    public function assignOption(option $option): bool
    {
//        $isOptionConsumed = parent::assignOption($option);
        $isOptionConsumed = false;

        if ( ! $isOptionConsumed) {

            switch (strtolower($option->name)) {
                case strtolower('includeExt'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    //$this->yearText = $option->value;
                    [$this->isIncludeExt, $this->includeExtList] =
                        $this->splitExtensionString($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('excludeExt'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    //$this->yearText = $option->value;
                    [$this->isExcludeExt, $this->excludeExtList] =
                        $this->splitExtensionString($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('isNoRecursion'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isNoRecursion = boolval($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('iswritelisttofile'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isWriteListToFile = boolval($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('listfilename'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->listFileName = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('srcRoot'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->srcRoot = $option->value;
                    $isOptionConsumed = true;
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

    public function execute(): int
    {
        $this->scan4Filenames();

        return 0;
    }

    private function splitExtensionString($extString = "")
    {
        $isExtFound = false;
        $extensions = [];

        if (!empty ($extString)) {
            $parts = explode(" ", $extString);

            foreach ($parts as $part) {
                if (!empty($part)) {
                    $extensions [] = $part;
                }
            }

            // one or more extension defined
            if (count($extensions) > 0) {
                $isExtFound = true;
            }
        }

        return [$isExtFound, $extensions];
    }

    public function text(): string
    {
        $OutTxt = "";
        $OutTxt .= "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- fithFileNameList ---" . PHP_EOL;

        $OutTxt .= "Properties:" . PHP_EOL;
        $OutTxt .= $this->text_listFileNames();

        $OutTxt .= "File list:" . PHP_EOL;
        $OutTxt .= $this->text_listFileNames();

        return $OutTxt;
    }

    public function text_listFileNames(): string
    {
        $OutTxt = "";

        foreach ($this->fileNames as $fileName) {
            $OutTxt .= $fileName->text_NamePathLine() . PHP_EOL;
        }

        return $OutTxt;
    }

    public function textProperties(): string
    {
        $OutTxt = "";

        $OutTxt .= "path: " . $this->srcRoot . PHP_EOL;

        $OutTxt .= "isIncludeExt: " . $this->isIncludeExt . PHP_EOL;
        $OutTxt .= "includeExtList: " .
            $this->combineExtensionString($this->includeExtList) . PHP_EOL;

        $OutTxt .= "isExcludeExt: " . $this->isExcludeExt . PHP_EOL;
        $OutTxt .= "excludeExtList: " .
            $this->combineExtensionString($this->excludeExtList) . PHP_EOL;

        $OutTxt .= "isNoRecursion: " . $this->isNoRecursion . PHP_EOL;
        $OutTxt .= "isWriteListToFile: " . $this->isWriteListToFile . PHP_EOL;

        /**/

        return $OutTxt;
    }

    private function combineExtensionString($extArray = []): string
    {
        $outTxt = implode(" ", $extArray);

        return $outTxt;
    }

    /**
     * @param $folder
     *
     * @return array|bool
     *
     * @throws Exception
     * @since version
     */
    public function filesInDir($inPath)
    {
        $files = [];
        $folders = [];

        try {
            [$files, $folders] = $this->filesAndFoldersInDir($inPath);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $files;
    }

    public function filesAndFoldersInDir($inPath)
    {
        $files = [];
        $folders = [];

        try {
            // Is the path a folder?
            if (is_dir($inPath)) {
                $items = scandir($inPath);

                foreach ($items as $item) {
                    if ($item !== '.' && $item !== '..') {
                        $path = $inPath . '/' . $item;
                        if (is_file($path)) {
                            $files[] = $path;
                        } elseif (is_dir($path)) {
                            $folders[] = $path;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return ([$files, $folders]);
    }

    public function folderInDir($inPath)
    {
        // $files = [];
        $folders = [];

        try {
            // [$files, $folders] = $this->filesAndFoldersInDir($inPath);
            [, $folders] = $this->filesAndFoldersInDir($inPath);
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $folders;
    }

    public function assignFilesNames(fileNamesList $fileNamesList): int
    {
        // ToDo: extract function to use as constructor01
        $this->srcRoot = $fileNamesList->srcRoot;
        $this->isIncludeExt = $fileNamesList->isIncludeExt;
        $this->includeExtList = $fileNamesList->includeExtList;
        $this->isExcludeExt = $fileNamesList->isExcludeExt;
        $this->excludeExtList = $fileNamesList->excludeExtList;
        $this->isNoRecursion = $fileNamesList->isNoRecursion;
        $this->isWriteListToFile = $fileNamesList->isWriteListToFile;
        $this->listFileName = $fileNamesList->listFileName;

        return 0;
    }

    public function addFilenames(array $fileNames)
    {
        // array_push($this->fileNames, $fileNames->fileNames);

        foreach ($fileNames as $fileName) {
            $this->fileNames [] = $fileName;
        }
    }

    function scan4Filenames(
        $path = '',
        $includeExt = '',
        $excludeExt = '',
        $isNoRecursion = '',
        $writeListToFile = '',
    )
    {
        $hasError = 0;

        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("scan4Filenames: " . PHP_EOL);
//            print ("path: " . $path . PHP_EOL);
//            print ("includeExt: " . $includeExt . PHP_EOL);
//            print ("excludeExt: " . $excludeExt . PHP_EOL);
//            print ("isNoRecursion: " . $isNoRecursion . PHP_EOL);
//            print ("writeListToFile: " . $writeListToFile . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            // merge with parameters (empty values will use local value
            $this->mergeParameter2Class(
                $path,
                $includeExt,
                $excludeExt,
                $isNoRecursion,
                $writeListToFile,
            );

            $this->fileNames = [];

            // iterate over folder recursively if set
            $this->scanPath4Filenames($this->srcRoot);

        } /*--- exception ----------------------------------------------------*/
        catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

//        print('exit scan4Filenames: ' . $hasError . PHP_EOL);
        return $hasError;
    }

    private function mergeParameter2Class(
        mixed $path,
        mixed $includeExt,
        mixed $excludeExt,
        mixed $isNoRecursion,
        mixed $writeListToFile,
    )
    {
        if (empty ($path)) {
            $path = $this->srcRoot;
        }
        if (empty ($includeExt)) {
            $includeExt = implode(' ', $this->includeExtList);
        }
        if (empty ($excludeExt)) {
            $excludeExt = implode(' ', $this->excludeExtList);
        }
        if (empty ($isNoRecursion)) {
            $isNoRecursion = $this->isNoRecursion;
        }
        if (empty ($writeListToFile)) {
            $writeListToFile = $this->listFileName;
        }

        $this->assignParameters($path, $includeExt, $excludeExt, $isNoRecursion, $writeListToFile);
    }

    private function scanPath4Filenames(string $inPath)
    {
        //print('*********************************************************' . PHP_EOL);
//            print (">>> scanPath4Filenames: " . PHP_EOL);
//            print ("    inPath: " . $inPath . PHP_EOL);
        print (">>> scanPath4Filenames: " . $inPath . PHP_EOL);

        try {
            [$files, $folders] = $this->filesAndFoldersInDir($inPath);

            // print ("    files count: " . count($files) . PHP_EOL);

            foreach ($files as $file) {
                $fithFileName = new fithFileName($file);

                $isExpected = $this->check4ValidFileName($fithFileName);

                // ToDo: handle include / exclude


                if ($isExpected) {
                    $this->fileNames [] = $fithFileName;
                }
            }

            // follow sub folders
            if (!$this->isNoRecursion) {
                // print ('    folders count: ' . count($folders) . PHP_EOL);

                foreach ($folders as $folder) {
                    $isExpected = $this->check4ValidFolderName($folder);

                    // $isExpected = False;
                    // $isExpected = True;


                    // ToDo: handle include / exclude


                    if ($isExpected) {
                        $this->scanPath4Filenames($folder);
                    }
                }
            } else {
                print ("NoRecursion: Exit after base folder requested: : " . count($folders) . PHP_EOL);
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
    }

    private function check4ValidFileName(fithFileName $fithFileName)
    {
        $isValid = false;

        try {
            if ($this->isIncludeExt) {
                $isValid = $this->check4ExtExists($fithFileName, $this->includeExtList);
            } else {
                if ($this->isExcludeExt) {
                    $isValid = !$this->check4ExtExists($fithFileName, $this->excludeExtList);
                } else {
                    // $isExpected = False;
                    $isValid = true;
                }
            }

            if ($fithFileName->fileBaseName == '.gitignore') {
                $isValid = false;
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $isValid;
    }

    private function check4ExtExists(fithFileName $fithFileName, array $extList)
    {
        $isFound = false;

        try {
            foreach ($extList as $ext) {
                $isFound = $fithFileName->hasExtension($ext);
                if ($isFound) {
                    break;
                }
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $isFound;
    }

    private function check4ValidFolderName(string $folder)
    {
        $isValid = true;

        try {
            $fithFolderName = new fithFolderName($folder);


            if ($fithFolderName->folderName == '.git') {
                $isValid = false;
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $isValid;
    }


//    public function subFileListByExtensions (string $includeExtList, string $excludeExtList): fileNamesList
//    {
//        // TODO: Implement subFileListByExtensions() method.
//
//    }
//

    private function clone(fileNamesList $fileNamesList): fileNamesList
    {
        $fileNamesList = new fileNamesList();

        $fileNamesList->fileNames = $this->fileNames;
        $fileNamesList->srcRoot = $this->srcRoot;
        $fileNamesList->isIncludeExt = $this->isIncludeExt;
        $fileNamesList->includeExtList = $this->includeExtList;
        $fileNamesList->isExcludeExt = $this->isExcludeExt;
        $fileNamesList->excludeExtList = $this->excludeExtList;
        $fileNamesList->isNoRecursion = $this->isNoRecursion;
        $fileNamesList->isWriteListToFile = $this->isWriteListToFile;
        $fileNamesList->listFileName = $this->listFileName;

        return $fileNamesList;
    }

} // fileNamesList

