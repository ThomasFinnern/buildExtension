<?php

namespace Finnern\BuildExtension\src\fileMissingPreFuncComment;

use Exception;
use Finnern\BuildExtension\src\tasksLib\option;
use Finnern\BuildExtension\src\tasksLib\task;

//use Finnern\BuildExtension\src\codeByCaller\fileHeaderLib\fileSinceDataFactory;
//use Finnern\BuildExtension\src\codeByCaller\fileSinceLib\fileSinceDataBase;

/*================================================================================
Class exchangeSinceLinesFile
================================================================================*/

class indicateAll_MissPreCommentInFile
{
	public string $fileName;

	public task $task;
	public readonly string $name;

//    protected fileSinceDataBase|null $oSinceFileData;

	// just an indicator can be removed later
//    private string $callerProjectId = "";

	public bool $isLogOnly = false;
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
				case strtolower('isLogOnly'):
					print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
					$this->isLogOnly  = (bool) $option->value;
					$isOptionConsumed = true;
					break;
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
	public function indicateMissPreHeaderInLines(string $fileName): int
	{

		$hasError   = 0;
		$prevAtLine = '';

		try
		{
			print('*********************************************************' . PHP_EOL);
			print('indicateMissPreHeaderInLines' . PHP_EOL);
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

			$oScan4Missing = new scanMissingPreFuncComment();

			/* wait for 'function' declaration. Then check if precomment '/**' was found
			   Attention: may not detect all situations
			*/

			$isPreCommentFound       = false;
			$isInsidePreCommentFound = false;
			$lastPreCommentLineNbr   = 0;
			$lastReturnLineNbr       = 0;
			$lastFunctionEndLineNbr  = 0;

			$outLines = [];
			foreach ($inLines as $line)
			{

				$nextLine = $line;

				// keep state of brackets and comments and remove comment lines
				$oScan4Missing->nextLine($line);

				// pre comment may be found
				if ($oScan4Missing->isInPreFunctionComment)
				{
					if ($oScan4Missing->isPreFuncCommentStartLine)
					{
						$isPreCommentFound = true;
						$isInsidePreCommentFound = true;

						if ($this->isLogDev)
						{
							print ("Pre function comment start in line: " . $oScan4Missing->lineNumber . PHP_EOL);
						}
					}

					$lastPreCommentLineNbr = $oScan4Missing->lineNumber;
				}

				if ($isInsidePreCommentFound)
				{
					if ($oScan4Missing->isPreFuncCommentEndLine)
					{
						$isInsidePreCommentFound = false;

						if ($this->isLogDev)
						{
							print ("Pre function comment end in line: " . $oScan4Missing->lineNumber . PHP_EOL);
						}
					}

				}

				// Debug
				// end of 'function' near => return ?
				if ($oScan4Missing->isFunctionReturnLine)
				{
					if ($this->isLogDev)
					{
						print ("Function return in line: " . $oScan4Missing->lineNumber . PHP_EOL);
					}

					// Debug purposes
					$lastReturnLineNbr = $oScan4Missing->lineNumber;
				}

				// Debug
				// end of 'function' near => return ?
				if ($oScan4Missing->isFunctionEndLine)
				{
					if ($this->isLogDev)
					{
						print ("Function end in line: " . $oScan4Missing->lineNumber . PHP_EOL);
					}

					// Debug purposes
//					$lastEndLineNbr = $oScan4Missing->lineNumber;
                    $lastFunctionEndLineNbr = $oScan4Missing->lineNumber;
				}

				// start of 'function' detected ?
				if ($oScan4Missing->isFunctionStartLine)
				{

					if ($this->isLogDev)
					{
						print ("Function start in line: " . $oScan4Missing->lineNumber . PHP_EOL);
					}

					if (!$isPreCommentFound)
					{

						$indicator  = "/** ToDo: Add function comment here */" . PHP_EOL;
						$outLines[] = $indicator;

						print (">>> Missing pre function comment in line: " . $oScan4Missing->lineNumber
                            . " (" . $lastFunctionEndLineNbr . '/' . $lastPreCommentLineNbr . ")" . " !!!" . PHP_EOL);

						$this->isChanged = true;
					}

					$isPreCommentFound = false;
				}

				$outLines[] = $nextLine;
			}

			// on change write to file
			if ($this->isChanged && !$this->isLogOnly)
			{

				$isSaved = file_put_contents($fileName, $outLines);

				print (">> Changed FileName: " . $fileName . PHP_EOL);
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
