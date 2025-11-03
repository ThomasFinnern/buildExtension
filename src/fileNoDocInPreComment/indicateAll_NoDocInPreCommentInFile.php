<?php

namespace Finnern\BuildExtension\src\fileNoDocInPreComment;

use Exception;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\task;

//use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileSinceDataFactory;
//use Finnern\BuildExtension\src\codeByCaller\fileSinceLib\fileSinceDataBase;

/*================================================================================
Class exchangeSinceLinesFile
================================================================================*/

class indicateAll_NoDocInPreCommentInFile
{
	public string $fileName;

	public task $task;
	public readonly string $name;

//    protected fileSinceDataBase|null $oSinceFileData;

	// just an indicator can be removed later
//    private string $callerProjectId = "";

//	public bool $isLogOnly = false;
	public bool $isLogDev = false;

	public bool $isChanged = false;


	/*--------------------------------------------------------------------
	construction
	--------------------------------------------------------------------*/

	public function __construct($srcFile = "")
	{
//        parent::__construct();

//        $this->oSinceFileData = null; // assign on need

		$this->fileName = $srcFile;

		$this->isChanged = false;
	}


	/*--------------------------------------------------------------------
	assignTask
	--------------------------------------------------------------------*/

	public function assignTask(task $task): int
	{
		$hasError = 0;

		$this->task = $task;

		// $this->taskName = $task->name;

		$options = $task->options;

		// ToDo: Extract assignOption on all assignTask
		foreach ($options->options as $option)
		{

//            $isBaseOption = $this->assignBaseOption($option);
//            if (!$isBaseOption) {
			$this->assignOption($option);//, $task->name);
//            }
		}

		return $hasError;
	}

	/**
	 * @param   option  $option
	 *
	 * @return bool
	 */
	// ToDo: Extract assignOption on all assignTask
	public function assignOption(option $option): bool
	{
		$isOptionConsumed = false;
//        $isOptionConsumed = parent::assignOption($option);

		if (!$isOptionConsumed)
		{
			switch (strtolower($option->name))
			{
//				case strtolower('isLogOnly'):
//					print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//					$this->isLogOnly  = (bool) $option->value;
//					$isOptionConsumed = true;
//					break;
				case strtolower('isLogDev'):
					print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
					$this->isLogDev   = (bool) $option->value;
					$isOptionConsumed = true;
					break;

				case strtolower('filename'):
					print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
					$this->fileName   = $option->value;
					$isOptionConsumed = true;
					break;

			} // switch
		}

		return $isOptionConsumed;
	}

//    public function assignOptionCallerProjectId(string $callerProjectId): void
//    {
//        $this->callerProjectId = $callerProjectId;
//
//        $this->oSinceFileData = fileSinceDataFactory::oSinceFileData($callerProjectId);
//    }

	// ToDo: force overwrite
	public function indicateNoDocInPreHeaderInLines(string $fileName): int
	{

		$hasError   = 0;
		$prevAtLine = '';

		try
		{
			print('*********************************************************' . PHP_EOL);
			print('indicateNoDocInPreHeaderInLines' . PHP_EOL);
			print ("FileName in: " . $fileName . PHP_EOL);
			print('---------------------------------------------------------' . PHP_EOL);

			if (!empty ($fileName))
			{
				$this->fileName = $fileName;
			}
			else
			{
				$fileName = $this->fileName;
			}

			print ("FileName use: " . $fileName . PHP_EOL);

			$inLines = file($fileName);

			$oScan4Missing = new scanNoDocInPreFuncComment();

			/* wait for 'function' declaration. Then check if precomment '/**' was found
			   Attention: may not detect all situations
			*/

			$isPreCommentFound      = false;
			$isInsidePreCommentText = false;
            $isUserCommentFound     = false;
            $isUserCommentInvalid   = false;
            $preLineIdx = 0;

            $lastPreCommentLineNbr   = 0;
			$lastReturnLineNbr       = 0;
			$lastFunctionEndLineNbr  = 0;


			$outLines = [];
			foreach ($inLines as $line)
            {
                // keep state of brackets and comments and remove comment lines
                $oScan4Missing->nextLine($line);

                if ($oScan4Missing->isScanFileEnabled)
                {

                    $bareLine = trim($line);

                    // pre comment may be found
                    if ($oScan4Missing->isInPreFunctionComment)
                    {
                        if ($oScan4Missing->isPreFuncCommentStartLine)
                        {
                            $isPreCommentFound  = true;
                            $isUserCommentFound = false;

                            // pre comment line index
                            $preLineIdx = 0;

                            if ($this->isLogDev)
                            {
                                print ("Pre function comment start in line: " . $oScan4Missing->lineNumber . PHP_EOL);
                            }

                            continue;
                        }
                        else
                        {
                            // further lines in comment
                            $preLineIdx++;

                            if (!$isUserCommentFound && !$isUserCommentInvalid)
                            {
                                // valid comment start
                                if (str_starts_with($bareLine, '*'))
                                {

                                    if (strlen($bareLine) > 1)
                                    {
                                        $commentText = trim(substr($bareLine, 2));

                                        // First no description variable comment
                                        if ($commentText[0] == '@')
                                        {

                                            // No user documentation in pre function header
                                            if (!$isUserCommentFound)
                                            {
                                                $isUserCommentInvalid = true;
                                                print (">>> Missing user text in pre function comment in line: " . $oScan4Missing->lineNumber . " (" . $lastFunctionEndLineNbr . '/' . $lastPreCommentLineNbr . ")" . " !!!" . PHP_EOL);

                                            }

                                        }
                                        else
                                        {
                                            // valid user documentation text
                                            if (strlen($commentText[0]) > 0)
                                            {
                                                $isUserCommentFound = true;

                                                continue;
                                            }

                                        }

                                    }
                                }
                            }

                        }
                    }
                }

                if ($isPreCommentFound)
                {
                    if ($oScan4Missing->isPreFuncCommentEndLine)
                    {
                        $isPreCommentFound = false;

                        $isUserCommentFound   = false;
                        $isUserCommentInvalid = false;

                        if ($this->isLogDev)
                        {
                            print ("Pre function comment end in line: " . $oScan4Missing->lineNumber . PHP_EOL);
                        }
                    }

                }
            }

		}
		catch (Exception $e)
		{
			echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
			$hasError = -101;
		}

		print('exit exchangeSinceLines: ' . $hasError . PHP_EOL);

		return $hasError;
	}

	public function assignOptions(bool $isLogOnly, bool $isLogDev)
	{
		// direct local assignment above
//		$this->isLogOnly = $isLogOnly;
//		$this->isLogDev = $isLogDev;
	}

}
