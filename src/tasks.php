<?php

namespace tasks;

require_once "./task.php";

use Exception;
use task\task;

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

    public function count(): int
    {
        return (count($this->tasks));
    }

    public function extractTasksFromString($tasksLine = ""): tasks
    {
        $this->clear();
        $this->addTasksFromString($tasksLine);

        return $this;
    }

    public function addTasksFromString($tasksLine = ""): tasks
    {
        try {
            //        $tasks = "task:task00"
            //            . 'task:task01 /option1 /option2=xxx /option3="01teststring"'
            //            . 'task:task02 /optionX /option2=Y /optionZ="Zteststring"';
            $tasksLine = Trim($tasksLine);

            if ($tasksLine != '') {
                while ($this->isTaskStart($tasksLine)) {
                    $idxStart = strpos($tasksLine, ":");
                    $idxNext = strpos($tasksLine, "task:", $idxStart + 1);

                    // last task
                    if ($idxNext == false) {
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
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    public function clear(): void
    {
        $this->tasks = [];
    }

    // extract multiple tasks from string

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

    // ToDo: A task may have more attributes like *.ext to

    public function addTask(task $task): void
    {
        if (!empty ($task->name)) {
            // $this->tasks [$task->name] = $task;
            $this->tasks [] = $task;
        }
    }

    public function extractTasksFromFile(string $taskFile): tasks
    {
        print('*********************************************************' . "\r\n");
        print ("extractTasksFromFile: " . $taskFile . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        $this->clear();

        try {
            if (!is_file($taskFile)) {
                // not working $realPath = realpath($taskFile);
                throw new Exception('Task file not found: "' . $taskFile . '"');
            }

            $content = file_get_contents($taskFile); //Get the file
            $lines = explode("\n", $content); //Split the file by each line

            $taskLine = '';

            foreach ($lines as $line) {

                $line = trim($line);
                if (empty($line)) {
                    continue;
                }

                // ToDo: use before each ? "/*" comments like lang manager

                // ignore comments
                if (!str_starts_with($line, '//')) {

                    // start of task line set
                    if (str_contains($line, 'task:')) {

                        // Collected task lines are available: create task
                        if ($taskLine != '') {

                            $task = (new task())->extractTaskFromString($taskLine);
                            $this->addTask($task);

                            $taskLine = '';
                        }
                        else
                        {
                            // add options into one task line
                            $taskLine = $line;
                        }
                    }
                    else
                    {
                        // add options into one task line
                        $taskLine .= ' ' . $line;
                    }

                }
            }

            // Collected task lines are available: create task
            if ($taskLine != '') {

                $task = (new task())->extractTaskFromString($taskLine);
                $this->addTask($task);

                $taskLine = '';
            }

            // print ($this->tasksText ());

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $this;
    }

    public function text4Line(): string
    {
        $OutTxt = "";

        foreach ($this->tasks as $task) {
            $OutTxt .= $task->text4Line() . ' ';
        }

        $OutTxt .= "\r\n";

        return $OutTxt;
    }

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

