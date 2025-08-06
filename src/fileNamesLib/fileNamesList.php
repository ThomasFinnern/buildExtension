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
//            print('*********************************************************' . "\r\n");
//            print ("construct: " . "\r\n");
//            print ("path: " . $path . "\r\n");
//            print ("includeExt: " . $includeExt . "\r\n");
//            print ("excludeExt: " . $excludeExt . "\r\n");
//            print ("isNoRecursion: " . $isNoRecursion . "\r\n");
//            print ("writeListToFile: " . $writeListToFile . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            $this->clean();

            $this->assignParameters($srcPath, $includeExt, $excludeExt, $isNoRecursion, $writeListToFile);

        } /*--- exception ----------------------------------------------------*/
        catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }
        // // print('exit __construct: ' . $hasError . "\r\n");
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
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    //$this->yearText = $option->value;
                    [$this->isIncludeExt, $this->includeExtList] =
                        $this->splitExtensionString($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('excludeExt'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    //$this->yearText = $option->value;
                    [$this->isExcludeExt, $this->excludeExtList] =
                        $this->splitExtensionString($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('isNoRecursion'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->isNoRecursion = boolval($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('iswritelisttofile'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->isWriteListToFile = boolval($option->value);
                    $isOptionConsumed = true;
                    break;

                case strtolower('listfilename'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                    $this->listFileName = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('srcRoot'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
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
        $OutTxt .= "------------------------------------------" . "\r\n";
        $OutTxt .= "--- fithFileNameList ---" . "\r\n";

        $OutTxt .= "Properties:" . "\r\n";
        $OutTxt .= $this->text_listFileNames();

        $OutTxt .= "File list:" . "\r\n";
        $OutTxt .= $this->text_listFileNames();

        return $OutTxt;
    }

    public function text_listFileNames(): string
    {
        $OutTxt = "";

        foreach ($this->fileNames as $fileName) {
            $OutTxt .= $fileName->text_NamePathLine() . "\r\n";
        }

        return $OutTxt;
    }

    public function textProperties(): string
    {
        $OutTxt = "";

        $OutTxt .= "path: " . $this->srcRoot . "\r\n";

        $OutTxt .= "isIncludeExt: " . $this->isIncludeExt . "\r\n";
        $OutTxt .= "includeExtList: " .
            $this->combineExtensionString($this->includeExtList) . "\r\n";

        $OutTxt .= "isExcludeExt: " . $this->isExcludeExt . "\r\n";
        $OutTxt .= "excludeExtList: " .
            $this->combineExtensionString($this->excludeExtList) . "\r\n";

        $OutTxt .= "isNoRecursion: " . $this->isNoRecursion . "\r\n";
        $OutTxt .= "isWriteListToFile: " . $this->isWriteListToFile . "\r\n";

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
//            print('*********************************************************' . "\r\n");
//            print ("scan4Filenames: " . "\r\n");
//            print ("path: " . $path . "\r\n");
//            print ("includeExt: " . $includeExt . "\r\n");
//            print ("excludeExt: " . $excludeExt . "\r\n");
//            print ("isNoRecursion: " . $isNoRecursion . "\r\n");
//            print ("writeListToFile: " . $writeListToFile . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

//        print('exit scan4Filenames: ' . $hasError . "\r\n");
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
        //print('*********************************************************' . "\r\n");
//            print (">>> scanPath4Filenames: " . "\r\n");
//            print ("    inPath: " . $inPath . "\r\n");
        print (">>> scanPath4Filenames: " . $inPath . "\r\n");

        try {
            [$files, $folders] = $this->filesAndFoldersInDir($inPath);

            // print ("    files count: " . count($files) . "\r\n");

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
                // print ('    folders count: ' . count($folders) . "\r\n");

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
                print ("NoRecursion: Exit after base folder requested: : " . count($folders) . "\r\n");
            }
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
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

