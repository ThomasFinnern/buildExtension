<?php
namespace option;

class option {

    public
        $name = "";
    public
        $value = "";

    public
    function __construct($name = "", $value = "")
    {

        $this->name = $name;
        $this->value = $value;

    }

    public function text(): string
    {
        $OutTxt = " "; // . "\r\n";

        $OutTxt .= $this->name; // . "\r\n";
        if ($this->value != '') {
            $OutTxt .= '=' . $this->value; // . "\r\n";
        }

        return $OutTxt;
    }

}

namespace options;

use option\option;

class options {

	/**
	 * @var option[] $options
	 */
    public $options;

    public
    function __construct($options = [])
    {

        $this->options = $options;

    }

    public function addOption (option $option) : void {

	    if ( ! empty ($option->name))
	    {
		    $this->options [$option->name] = $option;
	    }
    }

    public function text(): string
    {
        $OutTxt = " "; // . "\r\n";

        foreach ($this->options as $option) {
            $OutTxt .= $option->text() . " ";
        }

        return $OutTxt;
    }

}

namespace task;

use option\option;
use options\options;

class task {

    public $name = "";

    public options $options;

    public
    function __construct($name = "", $options = "")
    {

        $this->name = $name;
        $this->options = $options;

    }

	public function addOption (option $option) {

		$this->options->add ($option);

	}

    public function text(): string
    {
        $OutTxt = "Task: "; // . "\r\n";

        $OutTxt .= $this->name . ' '; // . "\r\n";
        $OutTxt .= $this->options->text(); // . "\r\n";

        return $OutTxt;
    }

}

namespace tasks;

use option\option;
use task\task;

class tasks
{

    /**
     * @var task[] $tasks
     */
    public $tasks = [];

    public
    function __construct($tasks = [])
    {

        $this->tasks = $tasks;

    }


    public function addTask(task $task): void
    {

        if (!empty ($option->name)) {
            $this->tasks [$task->name] = $task;
        }
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

}