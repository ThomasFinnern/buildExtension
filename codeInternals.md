


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






