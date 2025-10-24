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
	public function indicateMissPreHeaderInLines(string $fileName, string $versionId): int
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

			$isPreCommentFound = false;
			$lastPreCommentLineNbr = 0;
			$lastReturnLineNbr = 0;

			$outLines = [];
			foreach ($inLines as $line)
			{

				$nextLine = $line;

				// keep state of brackets and comments and remove comment lines
				$oScan4Missing->nextLine($line);

				// pre comment may be found
				if (!$isPreCommentFound)
				{
					if ($oScan4Missing->isInPreFunctionComment)
					{
						print ("Pre function comment in line: " . $oScan4Missing->lineNumber . PHP_EOL);
						$lastPreCommentLineNbr = $oScan4Missing->lineNumber;

						$isPreCommentFound = true;
					}
				}

				// Debug
				// end of 'function' near => return ?
				if ($oScan4Missing->isFunctionReturnLine)
				{
					print ("Function return in line: " . $oScan4Missing->lineNumber . PHP_EOL);
					// Debug purposes
					$lastReturnLineNbr = $oScan4Missing->lineNumber;
				}

				// start of 'function' detected ?
				if ($oScan4Missing->isFunctionStartLine)
				{

					if (!$isPreCommentFound)
					{

						$indicator  = "/** ToDo: Add function comment here */" . PHP_EOL;
						$outLines[] = $indicator;

						print (">>> Missing function comment in line: " . $oScan4Missing->lineNumber . " (" . $lastPreCommentLineNbr . ")". " !!!" . PHP_EOL);

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

	public function assignOptions(bool $isForceOverwrite, bool $isForceVersion, bool $isLogOnly, string $versionId)
	{
		$this->isLogOnly = $isLogOnly;
	}

}
