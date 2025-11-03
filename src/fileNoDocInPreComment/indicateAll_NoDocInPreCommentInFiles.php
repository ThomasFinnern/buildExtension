<?php
namespace Finnern\BuildExtension\src\fileNoDocInPreComment;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\tasksLib\option;

/*================================================================================
Class exchangeAll_actCopyrightYear
================================================================================*/

class indicateAll_NoDocInPreCommentInFiles extends baseExecuteTasks
    implements executeTasksInterface
{
    //--- use file lines for task ----------------------

    public indicateAll_NoDocInPreCommentInFile $missPreHeaderInFile;

    public bool $isForceOverwrite = false;
    public bool $isForceVersion = false;
    public bool $isLogOnly = false;
	//private bool $isLogDev = false;

	public string $versionId = "xx.xx.xx";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

	public function __construct($srcRoot = "", $isNoRecursion = false, $yearText = "")
    {
        $hasError = 0;
        try {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("yearText: " . $yearText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            parent::__construct($srcRoot, $isNoRecursion);

            $this->isForceOverwrite = false;
            $this->isForceVersion = false;
            $this->isLogOnly = false;
            //$this->isLogDev = false;
            $this->versionId = "xx.xx.xx";

            //--- use file lines for task ----------------------

            $this->missPreHeaderInFile = new indicateAll_NoDocInPreCommentInFile();

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /**
     * @param option $option
     * * @return bool
     */
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = parent::assignOption($option);

        if (!$isOptionConsumed) {

            $isOptionConsumed = $this->missPreHeaderInFile->assignOption($option);
        }

        if (!$isOptionConsumed) {
            switch (strtolower($option->name)) {

//                case strtolower('isLogOnly'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $this->isLogOnly = (bool)$option->value;
//                    $isOptionConsumed = true;
//                    break;
//
//                case strtolower('isLogDev'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $this->isLogDev = (bool)$option->value;
//                    $isOptionConsumed = true;
//                    break;
//
//                case strtolower('versionId'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $this->versionId = $option->value;
//                    $isOptionConsumed = true;
//                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

    public function execute(): int
    {
        //--- collect files ---------------------------------------

//        // files not set already
//        if (count($this->fileNamesList->fileNames) == 0) {
//            $fileNamesList = new fileNamesList ($this->srcRoot, 'php',
//                '', $this->isNoRecursion);
//            $this->fileNamesList = $fileNamesList;
//
//            $fileNamesList->scan4Filenames();
//        }

        // collect file list if not existing
        if (count($this->fileNamesList->fileNames) == 0) {
            $this->fileNamesList->execute();

            if (count($this->fileNamesList->fileNames) == 0) {

                echo '%%% Attention: No files retrieved from: "' . $this->fileNamesList->srcRoot . '"    %%%' . PHP_EOL;
                return -975;
            }
        }

//        // tell factory to use classes
//        $this->missPreHeaderInFile->assignOptionCallerProjectId($this->callerProjectId);

//        $this->missPreHeaderInFile->assignOptions($this->isLogOnly, $this->isLogDev);

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);

            $this->missPreHeaderInFile->indicateNoDocInPreHeaderInLines($fileName->srcPathFileName);
        }

        return 0;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- indicateAll_NoDocInPreCommentInFiles ---" . PHP_EOL;


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

} // class

