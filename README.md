# buildExtension

\*.php scripts to maintain joomla component builds and automatic change like copyright year*

General tasks
* Build:   
  Collect all files of a J! extensipon into a zip file  
  Options allow for increase of copy right and version in manifest before
  Options allow for increase of copy right and version in php files before
* Exchange in files:  
  Assign standard header by project
  Exchange header parts like actual year, copyright texts ...
* future:   

## Executing a task

All \<task\>cmd.php files accept task and options file in the commandline.  
Example: ```doFileTasksCmd.php -f updateAll_fileHeaders.tsk -o options_version_tsk\build_develop.opt```

It will call a matching class \<task\>.php file to assign the options and execute the task.

Expected extension but not enforced: Task file: \*.tsk, Options file: \*.opt. More below.

### doFileTasksCmd.php: general tasks executer

Nearl all task can be adressed by calling doFileTasksCmd with task matching *tsk file. Inside doFileTasks.php a distributer calls the intended task file

The doBuildTasksCmd.php file is also intended for calling multiple tasks defined in single \*.tsk file

### buildExtensionCmd.php: general build start

As this is the main task for all joomla! extensions the buildExtensionCmd.php file is located directly in the src folder next to the doFileTasksCmd.php file

SIt support to build an extension (component, module, ...)  *.zip file
See examples in folder build_tsk

The build process will call several of the subtasks in the subfolders

### \<subtask\>cmd.php: base classes

The subfolders contain several 'subtask' cmd files. These can be called directly if needed. 

Example: ```src/fileHeaderLib/updateAll_fileHeadersCmd.php -f updateAll_fileHeaders.tsk -o options_version_tsk\build_develop.opt```

Attetion: The calling batch and the *.tsk file need to regard the deeper folder for referencing the calling project folder

### Task file

Example of \*.tsk file

```
task:buildExtension
/type=component
/srcRoot="../../RSGallery2_J4"
   // /isNoRecursion=true
/buildDir="../../RSGallery2_J4_Dev/.packages"
/name=com_rsgallery2
/extension=Lang4Dev
```

The first line defines the task, the following lines the options.  
A optional options file \*.opt has the same format and can be added 

The task refers to a php file appling the options and executing the task

### Options file

Example of *.opt file

```
options:build
/isIncreaseBuild=true
```
The intension is to predefine some options in a file and then apply a sertain set of optiosn files to a task

The name can be chosen freely and is just a reminder of the intention of the file. Some characters like blanks may be restricted


## Tasks code

### Tasks base code

Code in folder tasksLib handles complete tasks and option interpretation into objects

### *lib folders

Each folder handles a special task 

## Build tasks related

### Folder and files

Files to be changed may be searched recursively and by type 

#### Option flags used for file handling

source path
srcRoot="..."

without /isNoRecursion flag all subdirectories are scanned 
/isNoRecursion=true
/includeExt="php"
/excludeExt="";

restrict/include to list of file types
/includeExt="php xmp ini"
/excludeExt="php xmp ini";

/isWriteListToFile
/listFileName="FoundFileNamesList.txt"

## Tasks supported
For each 'sub' project exists a *cmd.php. There the use can be seen ba  

* buildExtension
* clean4GitCheckin
* doBuildTasks
* exchangeAll_actCopyrightYearLines
* exchangeAll_authorLines
* exchangeAll_licenseLines
* exchangeAll_linkLines
* exchangeAll_packageLines
* exchangeAll_sinceCopyrightYearLines
* exchangeAll_subPackageLines
* fileDateTime
* fileHeaderByFileData
* fileHeaderByFileLine
* fileHeaderData
* forceCreationDate
* forceVersionId
* increaseVersionId
* manifestFile

## Base files for handling tasks, options

these are included in 
* fileNamesList
* fithFileName
* folderName
* option
* options
* original
* task
* tasks
* updateAll_fileHeaders


## ToDo:
* Short description for each Task
* Tasks and Options as package

# Task system

The script system (above) is relaying on a library to extract task and options from the \*.tsk file

## General structure of task function / creation

Sources in src folder include 'Task'.php, 'Task'Cmd.php, 'Task'.bat, 'Task'.tsk, files

* 'Task'.php    : Executing the task(s)
* 'Task'Cmd.php : Handling commandline parameter for the task
* 'Task'.bat    : Call a 'Task'Cmd.php  with tasks and options
* 'Task'.tsk    : Commandline task and options as lines in file

### Interface to scripts

- Command line options with '-'   
  Example: ``` /srcRoot="./../../RSGallery2_J4"```    
  (sorry no spaces before/after '=' yet)
- Task lists  
  Example: ```task:exchangeAll_actCopyrightYear```
- Task files
  Containing task name and option in lines
- Options file
  containing list of Options in files

### Task and option files

Options and task lists can be loaded from a common file
In each 'Task'Cmd.php are prepared commented 'command/option lines' as example
Also Example bats exist in the src folder

