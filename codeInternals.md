
# Code internals

**WIP** Work In Progress

The following information only shows the current status without completeness at the time of writing \:\-\(

##  general task structure 

Subtasks are single task requests over many files. One task will be applied to several files


### interface executeTasksInterface

Defines base functions like assignTask, assignOptions and execute

### class baseExecutionTask

Here the filenames list is living
It keeps basic options like callerProjectId 



##  general task structure by example



````
class updateAll_fileHeaders extends baseExecuteTasks
    implements executeTasksInterface
````


# task descriptions


## updateAll_fileHeaders

used for filetypes "php Ts" or ? "JS"

updateAll_fileHeaders->fileHeaderByFileData->upgradeHeader
    ->replaceStandardHeaderLines
    ->replaceForcedHeaderLines

2025.08.05:  
\>>>  
fileHeaderByFileData derives from fileHeaderData


* fileHeaderData is the base data which is different to each calling project type
* Attention: fileHeaderData references external copyright data which is used also in manifest file- **ToDo:** use seperate copyright definition *.xml and *.php/ts

===
New ?
fileHeaderByFileData includes from fileHeaderDataBase

fileHeaderDataBase


\<<<



upgradeHeader



fileHeaderByFileData

