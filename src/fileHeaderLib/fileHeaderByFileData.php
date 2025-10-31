<?php
// ToDo: Init write to log file with actual name

namespace Finnern\BuildExtension\src\fileHeaderLib;

use Exception;
//use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileHeaderData;
//use Finnern\BuildExtension\src\codeByCaller\fileManifestLib\copyrightTextFactory;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileHeaderDataBase;
use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileHeaderDataFactory;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\options;
use Finnern\BuildExtension\src\tasksLib\task;

/*================================================================================
Class fileHeaderByFileData
================================================================================*/

/**
 * Upgrade complete header in given file
 *   * Will add missing lines
 *   * Will keep author lines and similar from file
 *   * will force license to standard value from code
 *
 * ??? ToDo: Call with property values in task option ???
 * To force the exchange of a single line use fileHeaderByFileLine
 */
class fileHeaderByFileData // extends fileHeaderData
{
    public string $fileName;

    /** * @var string array */
    public array $fileHeaderLines = [];

    /** * @var string array */
    public array $newHeaderLines = [];

    /** * @var string array */
    public array $preFileLines = [];
    /** * @var string array */
    public array $postFileLines = [];

    protected fileHeaderDataBase|null $oFileHeader;

    public bool $isValid = false;

    public task $task;
    public readonly string $name;


    //--- flags ----------------------------------

    // ToDo: copyright flags: Set and use in copyright text with execute

    // --- Std value ------
    public bool $isForceStdPackage = false;
    public bool $isForceStdSubpackage = false;
    public bool $isForceStdActCopyright = false;
    public bool $isForceStdSinceCopyright = false;
    public bool $isForceSinceCopyrightToToday = false;
    public bool $isForceStdLicense = false;
    public bool $isForceStdAuthor = false;

    // --- Force value ------
    public bool $isForcePackage = false;
    public bool $isForceSubpackage = false;
    public bool $isForceActCopyright = false;
    public bool $isForceSinceCopyright = false;
    public bool $isForceActCopyrightToToday = false;
    public bool $isForceLicense = false;
    public bool $isForceAuthor = false;

    // --- Value to be used on force ------
    public string $valueForcePackage = "";
    public string $valueForceSubpackage = "";
    public string $valueForceActCopyright = "";
    public string $valueForceSinceCopyright = "";
    public string $valueForceCopyright = "";
    public string $valueForceLicense = "";
    public string $valueForceAuthor = "";

    // just an indicator can be removed later
    private string $callerProjectId = "";

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/
    private string $isUpdateActCopyrightDate;

    public function __construct($srcFile = "")
    {
        // parent::__construct();

        $this->oFileHeader = null; // assign on need

        $this->fileName = $srcFile;

        $this->initFlags();
    }


    /*--------------------------------------------------------------------
    importFileData
    --------------------------------------------------------------------*/

    private function initFlags():void
    {
        $this->isUpdateActCopyrightDate = true;

        $this->isForceStdPackage = false;
        $this->isForceStdSubpackage = false;
        $this->isForceStdActCopyright = false;
        $this->isForceStdSinceCopyright = false;
        $this->isForceSinceCopyrightToToday = false;
        $this->isForceStdLicense = false;
        $this->isForceStdAuthor = false;

        $this->isForcePackage = false;
        $this->isForceSubpackage = false;
        $this->isForceActCopyright = false;
        $this->isForceSinceCopyright = false;
        $this->isForceActCopyrightToToday = false;
        $this->isForceLicense = false;
        $this->isForceAuthor = false;

        $this->valueForcePackage = "";
        $this->valueForceSubpackage = "";
        $this->valueForceActCopyright = "";
        $this->valueForceSinceCopyright = "";
        $this->valueForceCopyright = "";
        $this->valueForceLicense = "";
        $this->valueForceAuthor = "";
    }

    public function assignTask(task $task): int
    {
        $hasError = 0;

        $this->task = $task;

        // $this->taskName = $task->name;

        $options = $task->options;

        // ToDo: Extract assignOption on all assignTask
        foreach ($options->options as $option) {

//            $isBaseOption = $this->assignBaseOption($option);
//            if (!$isBaseOption) {
                $this->assignOption($option);//, $task->name);
//            }
        }

        return $hasError;
    }

    /**
     * @param   options  $options
     * @param   task              $task
     *
     * @return void
     */
    // ToDo: Extract assignOption on all assignTask
    public function assignOption(option $option): bool
    {
        $isOptionConsumed = false;
//        $isOptionConsumed = parent::assignOption($option);

        if ( ! $isOptionConsumed) {
            switch (strtolower($option->name)) {
                case strtolower('filename'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->fileName = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isupdatecreationdate'):
                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                    $this->isUpdateActCopyrightDate = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcestdpackage'):
                    $this->isForceStdPackage = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcestdsubpackage'):
                    $this->isForceStdSubpackage = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcestaactcopyright'):
                    $this->isForceStdActCopyright = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcestdsincecopyright'):
                    $this->isForceStdSinceCopyright = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcesincecopyrighttotoday'):
                    $this->isForceSinceCopyrightToToday = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcestdlicense'):
                    $this->isForceStdLicense = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcestdauthor'):
                    $this->isForceStdAuthor = $option->value;
                    $isOptionConsumed = true;
                    break;


                case strtolower('isforcepackage'):
                    $this->isForcePackage = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcesubpackage'):
                    $this->isForceSubpackage = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforceactcopyright'):
                    $this->isForceActCopyright = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcesincecopyright'):
                    $this->isForceSinceCopyright = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforceactcopyrighttotoday'):
                    $this->isForceActCopyrightToToday = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforcelicense'):
                    $this->isForceLicense = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('isforceauthor'):
                    $this->isForceAuthor = $option->value;
                    $isOptionConsumed = true;
                    break;


                case strtolower('valueforcepackage'):
                    $this->valueForcePackage = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('valueforcesubpackage'):
                    $this->valueForceSubpackage = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('valueforceactcopyright'):
                    $this->valueForceActCopyright = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('valueforcesincecopyright'):
                    $this->valueForceSinceCopyright = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('valueforcecopyright'):
                    $this->valueForceCopyright = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('valueforcelicense'):
                    $this->valueForceLicense = $option->value;
                    $isOptionConsumed = true;
                    break;

                case strtolower('valueforceauthor'):
                    $this->valueForceAuthor = $option->value;
                    $isOptionConsumed = true;
                    break;

            } // switch
        }

        return $isOptionConsumed;
    }

    public function execute(): int
    {
        $hasError = 0;
        $task = $this->task;

        // single lines exchange will write complete header lines

        switch (strtolower($task->name)) {
            case strtolower('upgradeheader'):
                print ('Execute task: ' . $task->name . PHP_EOL);

                $this->upgradeHeader($this->fileName);
                break;

            default:
                print ('!!! Task not executed: ' . $task->name . '!!!' . PHP_EOL);

                break;
        }

        return $hasError;
    }

    public function upgradeHeader(string $srcPathFileName): int
    {
        print('*********************************************************' . PHP_EOL);
        print('upgradeHeader' . PHP_EOL);
        print ("srcPathFileName: " . $srcPathFileName . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        $hasError = 0;

        // read header
        $this->importFileData($srcPathFileName);

        // exchange user replacements

        $this->replaceStandardHeaderLines();
        $this->replaceForcedHeaderLines();

        // compare new against file lines

        $isChanged = $this->compareHeaderLines();

        // second line should be a blank line
        $count = empty($this->preFileLines) ? 0 : count($this->preFileLines);
        // second line is missing
        if ($count == 1)
        {
            $isChanged = true;
        }
        else
        {
            // second line to be checked
            if ($count > 1)
            {
                if (trim($this->preFileLines[1]) != '')
                {
                    $isChanged = true;
                }
            }
        }

        //  write back if changed
        if ($isChanged && $this->isValid) {

            $hasError = $this->writeFileByHeader($srcPathFileName);

        }

        return $hasError;
    }

    function importFileData(string $fileName = ""): int
    {
        $hasError = 0;
        $isValid = false;
        $this->isValid = false;

        try {
            print('*********************************************************' . PHP_EOL);
            print('importFileData' . PHP_EOL);
            print ("FileName in: " . $fileName . PHP_EOL);
            print('---------------------------------------------------------' . PHP_EOL);

            // separate lines to section header-, pre-, post-lines
            $this->importLines($fileName);

            $headerCount = count($this->fileHeaderLines);

            if (0 < $headerCount && $headerCount < 20) {

                $this->oFileHeader->extractHeaderValuesFromLines($this->fileHeaderLines);
            }

            // Check for ' * @ ....
            $this->isValid = $this->oFileHeader->check4ValidHeaderLines($this->fileHeaderLines);


            // todo: print ("headerLines: " . $headerLines . PHP_EOL);
            // ToDo: print result
        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        print('exit extractFileHeader: ' . $hasError . PHP_EOL);

        return $hasError;
    }

    // ToDo: valid ... ? additional checks ? .....

    /**
     * @param string $fileName
     * @return void
     */
    public function importLines(string $fileName): void
    {
        // ToDo: '/*' may be a comment before the header but it may just start the header => improve by following
        // ToDo: Detect @package as indicator
        // ToDo: Detect max end of header ->
        //          ->namespace in line
        //          ->line starts with use
        //          ->defined in line


        if (!empty ($fileName)) {
            $this->fileName = $fileName;
        } else {
            $fileName = $this->fileName;
        }
        print ("FileName use: " . $fileName . PHP_EOL);

        $lines = file($fileName);

        //--- import and sort lines ----------------------------------------

        $preFileLines = [];
        $postFileLines = [];

        $headerLines = [];
//            $originalLines = [];

        $isHasStart = false;
        $isHasEnd = false;

        foreach ($lines as $line) {

            //--- pre lines ---------------------------

            // pre lines include all lines without "/**" line */
            if ($isHasStart == false) {

                // start comment
                if (!str_starts_with(trim($line), '/**')) {
                    if ($line != '') {
                        // first lines    <php , comments
                        $preFileLines [] = $line;
                    }
                } else {

                    // header lines start line
                    $isHasStart = true;
                    $headerLines [] = $line;

                    $this->preFileLines = $preFileLines;
                }

            } else {

                //--- post lines ---------------------------

                if ($isHasEnd) {
                    $postFileLines [] = $line;

                } else {

                    //--- pure header lines ---------------------------

                    $headerLines [] = $line;

                    // end comment
                    if (str_contains(trim($line), '*/')) {

                        $isHasEnd = true;
                        $this->fileHeaderLines = $headerLines;

                    }
                }
            }

        } // for lines in file

        // keep lines
        $this->postFileLines = $postFileLines;

        return;
    }

    private function replaceStandardHeaderLines(): void
    {
        $standardHeader = fileHeaderDataFactory::oFileHeaderData($this->callerProjectId);

        if ($this->isForceStdPackage) {
            $this->oFileHeader->package = $standardHeader->package;
        }

        if ($this->isForceStdSubpackage) {
            $this->oFileHeader->subpackage = $standardHeader->subpackage;
        }

        if ($this->isForceStdActCopyright) {
            // ToDo: update actual ...
            $this->oFileHeader->copyright->actCopyrightDate = $standardHeader->copyright->actCopyrightDate;
        }

        if ($this->isForceStdSinceCopyright) {
            // ToDo: update actual ...
            $this->oFileHeader->copyright->sinceCopyrightDate = $standardHeader->copyright->sinceCopyrightDate;
        }

        if ($this->isForceStdLicense) {
            $this->oFileHeader->license = $standardHeader->license;
        }

        if ($this->isForceStdAuthor) {
            $this->oFileHeader->author = $standardHeader->author;
        }

    }

    private function replaceForcedHeaderLines(): void
    {
        // see also isForceActCopyrightToToday
        if ($this->isUpdateActCopyrightDate) {
            // $this->copyright->actCopyrightDate = $this->copyright->yearToday;
            $this->oFileHeader->oCopyright->setActCopyright2Today ();
        }


        if ($this->isForcePackage) {
            $this->oFileHeader->package = $this->valueForcePackage;
        }

        if ($this->isForceSubpackage) {
            $this->oFileHeader->subpackage = $this->valueForceSubpackage;
        }

        if ($this->isForceActCopyright) {
            $this->oFileHeader->copyright->actCopyrightDate = $this->valueForceCopyright;
        }

        if ($this->isForceSinceCopyrightToToday) {
            // $this->copyright->sinceCopyrightDate = $this->copyright->yearToday;
            $this->oFileHeader->copyright->setSinceCopyright2Today ();
        }

        if ($this->isForceSinceCopyright) {
            $this->oFileHeader->copyright->sinceCopyrightDate = $this->valueForceCopyright;
        }

        // see also isUpdateCreationDate
        if ($this->isForceActCopyrightToToday) {
            // $this->copyright->actCopyrightDate = $this->copyright->yearToday;
            $this->oFileHeader->copyright->setActCopyright2Today ();
        }

        if ($this->isForceLicense) {
            $this->oFileHeader->license = $this->valueForceLicense;
        }

        if ($this->isForceAuthor) {
            $this->oFileHeader->author = $this->valueForceAuthor;
        }

    }

    protected function compareHeaderLines(): bool
    {
        // create actual header lines
        $this->newHeaderLines = $this->oFileHeader->headerLines();

        $isChanged = $this->newHeaderLines <=> $this->fileHeaderLines;

        return $isChanged;
    }

    private function writeFileByHeader(string $fileName): bool
    {
        $isSaved = false;

        try {
            if (!empty ($fileName)) {
                $this->fileName = $fileName;
            } else {
                $fileName = $this->fileName;
            }
            print ("FileName use: " . $fileName . PHP_EOL);


            $outLines = [];

            //--- pre lines ---------------------------

            // pre lines include all lines until first '/**' indicator is found
            // => keep it but format first three lines

            // One space after "<?php\n"
            $outLines [] = "<?php" . PHP_EOL;
            $outLines [] = PHP_EOL;

            $isFirstNoneBlankLine = false;
            foreach ($this->preFileLines as $idx => $line) {

                // Jump first lines
                if (!$isFirstNoneBlankLine)
                {
                    // One space after "<?php\n"
                    if ($idx > 0)
                    {
                        // take first none empty line
                        if (trim($line) != '')
                        {
                            $isFirstNoneBlankLine = true;
                            $outLines []          = $line;
                        }
                    }
                } else {
                    // Later line
                    $outLines [] = $line;
                }
            }

            //--- changed header lines ---------------------------

            $headerLines = $this->newHeaderLines;
            if (count($headerLines) == 0) {
                $headerLines = $this->oFileHeader->headerText();
            }

            foreach ($headerLines as $line) {
                $outLines [] = $line;
            }

            //--- post lines ---------------------------

            foreach ($this->postFileLines as $line) {
                $outLines [] = $line;
            }

            $isSaved = file_put_contents($fileName, $outLines);

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $isSaved;
    }

    public function assignOptionCallerProjectId(string $callerProjectId)
    {
        $this->callerProjectId = $callerProjectId;

        $this->oFileHeader = fileHeaderDataFactory::oFileHeaderData($callerProjectId);
    }

    public function byFileText()
    {
        $OutTxt = "";
        $OutTxt .= "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- fileHeaderByFile ---" . PHP_EOL;

        $OutTxt .= ">>> --- result ----------------" . PHP_EOL;

        $OutTxt .= $this->oFileHeader->text() . PHP_EOL;

        $OutTxt .= ">>> --- file data ----------------" . PHP_EOL;

        $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;

        $OutTxt .= ">>> --- file lines ----------------" . PHP_EOL;

        $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;

        return $OutTxt;
    }

} // fileHeaderByFile

