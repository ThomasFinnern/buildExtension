<?php

namespace Finnern\BuildExtension\src\fileNamespace;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\task;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;

/*================================================================================
Class createNamespaceFiles
================================================================================*/

// ToDo: Force extension lang id ==> COM_RSGALLLERY2___ as start
// /isForceExtensionId="..."

class createNamespaceFiles extends baseExecuteTasks
    implements executeTasksInterface
{
    //--- use file lines for task ----------------------

    public createNamespaceFile $createNamespace;

    public string $yearText = "";

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

            $this->yearText = $yearText;

            //--- use file lines for task ----------------------

            $this->createNamespace = new createNamespaceFile();

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

            $isOptionConsumed = $this->createNamespace->assignOption($option);
        }

        if ( ! $isOptionConsumed) {
            switch (strtolower($option->name)) {

                case strtolower('yearText'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->yearText = $option->value;
                    $isOptionConsumed = true;
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

    public function execute(): int
    {
        //--- collect files ---------------------------------------

        // files not set already
//        if (count($this->fileNamesList->fileNames) == 0) {
//            $fileNamesList = new fileNamesList ($this->srcRoot, 'php',
//                '', $this->isNoRecursion);
//            $this->fileNamesList = $fileNamesList;
//
//            $fileNamesList->scan4Filenames();
//        }

        print('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!' . PHP_EOL);
//        print('createNamespace files ' . PHP_EOL);
//        print ("" . PHP_EOL);
        print ("!!! Attention: Namespaces inserted or replaced are (may be) wrong." . PHP_EOL);
        print ("!!!            Especially for modules and plugins.                " . PHP_EOL);
        print ("!!!            CamelCase in class names ;-(                       " . PHP_EOL);
        print ("Missing namespaces are indicated fine" . PHP_EOL);
//        print ("" . PHP_EOL);
        print('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!' . PHP_EOL);

        // collect file list if not existing
        if (count($this->fileNamesList->fileNames) == 0) {
            $this->fileNamesList->execute();

            if (count($this->fileNamesList->fileNames) == 0) {

                echo '%%% Attention: No files retrieved from: "' . $this->fileNamesList->srcRoot . '"    %%%' . PHP_EOL;
                return -975;
            }
        }

//        // tell factory to use classes
//        $this->createNamespace->assignOptionCallerProjectId($this->callerProjectId);

        //--- iterate over all files -------------------------------------

        foreach ($this->fileNamesList->fileNames as $fileName) {

            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);

            $this->createNamespace->createNamespace($fileName->srcPathFileName);
        }

        return 0;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- encloseAll_jexec_Files ---" . PHP_EOL;


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

} // exchangeAll_actCopyrightYear

