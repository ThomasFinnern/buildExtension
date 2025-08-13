<?php

namespace Finnern\BuildExtension\src\tasksLib;

use Exception;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class task
================================================================================*/

class tasks
{

    /**
     * @var task[] $tasks
     */
    public $tasks;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public
    function __construct(
        $tasks = [],
    )
    {
        $this->tasks = $tasks;
    }

    public function clear(): void
    {
        $this->tasks = [];
    }

    public function count(): int
    {
        return (count($this->tasks));
    }

    public function getTask(string $name, bool $isIgnoreCase = false): string
    {
        $value = '';

        foreach ($this->tasks as $task) {
            $isFound = false;

            if ($isIgnoreCase) {
                $isFound = strtolower($task->name) === strtolower($name);
            } else {
                $isFound = $task->name === $name;
            }

            if ($isFound) {
                $value = $task->value;
            }
        }

        return ($value);
    }

    public function extractTasksFromFile(string $taskFile): tasks
    {
        //print('*********************************************************' . "\r\n");
        print ("extractTasksFromFile: " . $taskFile . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        try {
            if (!is_file($taskFile)) {
                print ('!!! Task file not found: "' . $taskFile . '" !!!' . "\r\n");
                // not working $realPath = realpath($taskFile);
                throw new Exception('Task file not found: "' . $taskFile . '"');
            }

            $content = file_get_contents($taskFile); //Get the file
            $lines = explode("\n", $content); //Split the file by each line

            $this->extractTasksFromLines($lines);

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    /**
     * @param array $lines
     * @return void
     */
    public function extractTasksFromLines(array $lines): void
    {
        // $actTask = new task(); // dummy task

        $taskLines = [];

        try {
            foreach ($lines as $line) {

                //--- comments and trim -------------------------------------------

                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                // ignore comments
                if (str_starts_with($line, '//')) {
                    continue;
                }

                // ToDo: use before each ? "/*" comments like lang manager

                //--- useful line -------------------------------------------

                // start of task line set
                if ($this->isTaskStart($line)) {
                    // create task and assign lines
                    $actTask = new task();
                    $actTask->extractTaskFromLines($taskLines);

                    $this->addTask($actTask);
                    $taskLines = [];
                }

                $taskLines [] = $line;
            }

            // Collected task lines are available: create task
            if ( ! empty ($taskLines)) {

                // create task and assign lines
                $actTask = new task();
                $actTask->extractTaskFromLines($taskLines);

                $this->addTask($actTask);
            }

            // print ($this->tasksText ());

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

    }

    private function isTaskStart(string $tasksLine)
    {
        $isTask = false;

        $tasksLine = Trim($tasksLine);
        $checkPart = strtolower(substr($tasksLine, 0, 5));

        // /option1 /option2=xxx /option3="01teststring"
        if ($checkPart == 'task:') {
            $isTask = true;
        }

        return $isTask;
    }


    public function extractTasksFromString($inTasksLine = ""): tasks
    {
        // 2025.03.11 $this->clear();

        try {
            //        $tasks = "task:task00"
            //            . 'task:task01 /option1 /option2=xxx /option3="01 test space string"'
            //            . 'task:task02 /optionX /option2=Y /optionZ="Zteststring"';
            $tasksLine = Trim($inTasksLine);

            if ($tasksLine != '') {
                while ($this->isTaskStart($tasksLine)) {

                    //--- extract next task -------------------------------

                    $idxStart = strpos($tasksLine, ":");
                    $idxNext = strpos($tasksLine, "task:", $idxStart + 1);

                    // last task
                    if ($idxNext === false) {
                        // wrong ? $singleTask = substr($tasksLine, $idxStart + 1);
                        $singleTask = $tasksLine;

                        $tasksLine = '';
                    } else {
                        // multiple options
                        // $singleTask = substr($tasksLine, $idxStart + 1, $idxNext - $idxStart - 1 - 1);
                        $singleTask = substr($tasksLine, 0, $idxNext - 1);

                        $tasksLine = substr($tasksLine, $idxNext);
                        $tasksLine = Trim($tasksLine);
                    }

                    $task = (new task())->extractTaskFromString($singleTask);
                    $this->addTask($task);
                }
            }
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    // ToDo: A task may have more attributes like *.ext to

    public function addTask(task $task): void
    {
        if (!empty ($task->name)) {
            // $this->tasks [$task->name] = $task;
            $this->tasks [] = $task;
        }
    }

    public function assignTasks(tasks $tasks): tasks
    {
        foreach ($tasks as $task) {
            $this->tasks[] = $tasks;
        }

        return $this;
    }

    /*
     * One line representation
     */
    public function __toString() {
        $tasksLine = '';

        foreach ($this->tasks as $task) {
            $tasksLine .= " " . $task;
        }

        return $tasksLine;
    }


    /*
     * Multi line representation
     */
    public function text(): string
    {
        $OutTxt = "--- Tasks: ---" . "\r\n";

        $OutTxt .= "Tasks count: " . count($this->tasks) . "\r\n";

        foreach ($this->tasks as $task) {
            $OutTxt .= $task->text() . "\r\n";
        }

        return $OutTxt;
    }


} // task

