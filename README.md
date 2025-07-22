# buildExtension

*.php scripts to maintain joomla component builds and automatic change like copyright year*

A *.bat calling buildExtensionCmd.php with references to task(s) (*.tsk) are the normal path to build an extension (component, module, ...)
See examples in folder build_tsk


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

* buildRelease
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

The script system (above) is relaying on a library to extract task and options from the *.tsk file

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

