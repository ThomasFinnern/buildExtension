<?php

namespace Finnern\BuildExtension\src\tasksLib;

use Exception;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
//use Finnern\BuildExtension\src\tasksLib\option;

/**
 * Base class prepares for filename list
 */
class baseExecuteTasks
{
    // task name
    public string $taskName = '????';

    public string $srcRoot = "";

    public bool $isNoRecursion = false;

    /**
     * @var fileNamesList
     */
    public fileNamesList $fileNamesList;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct(string $srcRoot = "", bool $isNoRecursion = false)
    {
        try {
//            print('*********************************************************' . "\r\n");
//            print ("srcRoot: " . $srcRoot . "\r\n");
//            print ("yearText: " . $yearText . "\r\n");
//            print('---------------------------------------------------------' . "\r\n");

            $this->srcRoot       = $srcRoot;
            $this->isNoRecursion = $isNoRecursion;

            $this->fileNamesList = new fileNamesList();
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }

    // TODO: check all extends to remove double function
    public function assignFilesNames(fileNamesList $fileNamesList): int
    {
        $this->fileNamesList = $fileNamesList;
        return 0;
    }

    // Task name with options
    public function assignBaseOption(option $option): bool
    {
        $isBaseOption = false;

        switch (strtolower($option->name)) {
            case 'srcroot':
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->srcRoot = $option->value;
                $isBaseOption  = true;
                break;

            case 'isnorecursion':
                print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                $this->isNoRecursion = boolval($option->value);
                $isBaseOption        = true;
                break;

//				case 'X':
//					print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
//					break;

        } // switch

        return $isBaseOption;
    }


}
