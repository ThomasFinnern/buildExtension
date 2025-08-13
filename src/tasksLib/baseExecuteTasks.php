<?php

namespace Finnern\BuildExtension\src\tasksLib;

use Exception;
use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;


/**
 * Base class prepares for filename list
 */
class baseExecuteTasks
{
    // task name
    public string $taskName = '????';

    // public string $srcRoot = "";

    public string $callerProjectId = "";

    // public bool $isNoRecursion = false;

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
//            print('*********************************************************' . PHP_EOL);
//            print ("srcRoot: " . $srcRoot . PHP_EOL);
//            print ("yearText: " . $yearText . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            //$this->srcRoot       = $srcRoot;
            //$this->isNoRecursion = $isNoRecursion;
            $this->callerProjectId = 'rsg2';

            $this->fileNamesList = new fileNamesList($srcRoot, '', '', $isNoRecursion);

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    // TODO: check all extends to remove double function
    public function assignFilesNames(fileNamesList $fileNamesList): int
    {
        foreach ($this->fileNamesList as $fileName) {
            $this->fileNamesList->fileNames[] = $fileName;
        }

        return 0;
    }

    /**
     * Assign task name and  options
     *
     * @param task $task
     * @return int
     */
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        $this->assignOptions($options, $task->name);

        return 0;
    }

    /**
     * @param options $options
     * @param task $task
     * @return bool
     */
    public function assignOptions(options $options, $taskName): int
    {

        foreach ($options->options as $option) {

            $isParentOption = $this->assignOption($option);
            if (! $isParentOption) {
                print ('%%% warning: requested option is not supported: ' . $taskName . '.' . $option->name . ' !!!' . PHP_EOL);
            }
        }

        return 0;
    }

    /**
     * @param option $option
     * @return bool true on option is consumed
     */
    public function assignOption(option $option): bool
    {
        // $isOptionConsumed = $this->fileNamesList->assignOption($option);
        $isOptionConsumed = $this->fileNamesList->assignOption($option);

        if (!$isOptionConsumed) {

            switch (strtolower($option->name)) {
//                case strtolower('srcroot'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $this->srcRoot = $option->value;
//                    $this->filenamesList->srcRoot = $this->srcRoot;
//
//                    $isOptionConsumed = true;
//                    break;

                case strtolower('callerProjectId'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->callerProjectId = $option->value;
                    $isOptionConsumed = true;
                    break;

//                case strtolower('isnorecursion'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $this->isNoRecursion = boolval($option->value);
//                    $isOptionConsumed = true;
//                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

    /**
     * Execute action on one file
     *
     * @param string $filePathName
     * @return int
     */
    public function executeFile(string $filePathName): int
    {
        $this->fileNamesList = new fileNamesList();
        $this->fileNamesList->fileNames[] = $filePathName;

        $this->execute();

        return 0;
    }



}
