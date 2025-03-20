# Tasks/options system

Tasks are defined by options and may call subtasks

Several classes support the base data highway. Code see folder src\tasksLib


## Classes using task system:

Normally following files have to be designed

* ClasNameCmd.php
* ClasName.php

### class command file

The *Cmd.php file collects the options and assigns them to the class file.
Then it calls execute function of the class file

The *Cmd.php file supports the load of a *.tsk and multiple *.opt files.
Both contain name and flags for the task class.

Example see BuildTasksCmd.php

### class file

It supports assignment of options and the execute function is supposed to do the hard work.

The ClassName.php may call subclasses with its own option/tasks. See below

Example see BuildTasks.php

## Tasks 
A task is defined by its header 'task:<name>' and a set of options
An option starts with '/name'. It may contain an assignment like '/variable=value'

Example :
```c++
task:buildExtension
/type=component
/srcRoot="../../LangMan4Dev"
	// /isNoRecursion=true
/buildDir="../../LangMan4DevProject/.packages"
/name=com_lang4dev
/extension=Lang4Dev
```

The first line and all options can be written in one line separated by a space each

## Options 

An option starts with '/name'. It may contain an assignment like '/variable=value'

example see above

## *.tsk file 
* The first line is an info line telling name of the task (file/intention) but is otherwise ignored
* Following lines contain options. Multiple options ay be added in a line separated by a space each

The first line and all options can be written in one line separated by a space each

The task name is only used in *Cmd.php files where multiple tasks are supported
Empty lines or lines starting with '//' as comment ar ignored

Example see #Task section

## *.opt file 
The format is similar to the *.tsk file.
* The first line is an info line telling name of the option (file/intention) but is otherwise ignored
* Following lines contain options. Multiple options ay be added in a line separated by a space each

The first line and all options can be written in one line separated by a space each
Empty lines or lines starting with '//' as comment ar ignored

Example :
```c++
options:patch
// 0.0.x.0 will be increased. Lower parts will be reset to zero 	
// Use for fixes or slightly improved functions
/isIncreasePatch=true
```


## Handle subtask classes
Subtask classes must be created in the constructor of the parent class.

The options will be sent first to the subclass. Consumed options will not reach the parent class.
On same option name it is recommended that the subclass gets a '/subx:' identifier in front of the options name. See example 'manifestFileUpdate.tsk'.

?ToDo: Call sub execute in parent execute or special function using sub in parent execute ?

## Handle tasks classes

A *Cmd.php file may be designed to accept multiple classes.

See doBuildTasksCmd.php 


