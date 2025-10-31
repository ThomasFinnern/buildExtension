<?php

namespace Finnern\BuildExtension\src\codeScanner;

use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\tasksLib\option;

/**
 * Scans code given line by line.
 * The class prints a log line on each changed  bracket level '{' '}'
 *
 * It keeps state
 *  - last bracket depth
 *
 * ToDo: Actually defined for one file but may be extended to more files ?
 * ToDo: Collect prints and write on demand and/or return log lines
 *
 *
 */
class codeScanner_logIndent extends baseExecuteTasks
	implements executeTasksInterface
{

	private bool $isDummy = false; // testflag may be changed
	private codeScannerByLine $scanCodeLines;
	private string $fileName;
	private int $lastDepthCount = 0;

	public function __construct()
	{
		parent::__construct();
		print ("codeScanner_logIdent __construct: " . PHP_EOL);

		$this->init();
	}

	protected function init()
	{
		$this->fileNamesList = new fileNamesList();
	}

	/**
	 * @param   option  $option
	 * * @return bool
	 */
	public function assignOption(option $option): bool
	{
		$isOptionConsumed = parent::assignOption($option);

		if (!$isOptionConsumed)
		{

//            $isOptionConsumed = $this->exchangeSinceLinesFile->assignOption($option);
		}

		if (!$isOptionConsumed)
		{
			switch (strtolower($option->name))
			{

				case strtolower('isDummy'):
					print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
					$this->isDummy    = (bool) $option->value;
					$isOptionConsumed = true;
					break;

//                ==>    $this->fileNamesList->srcRoot
//                case strtolower('srcRoot'):
//                    print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
//                    $this->srcRoot = (string)$option->value;
//                    $isOptionConsumed = true;
//                    break;

				case strtolower('fileName'):
					print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
					$this->fileName   = (string) $option->value;
					$isOptionConsumed = true;
					break;

			} // switch
		}

		return $isOptionConsumed;
	}

	public function nextLine($line)
	{

		$lastDepthCount = $this->lastDepthCount;

		$this->scanCodeLines->nextLine($line);

		$isChanged     = $this->scanCodeLines->isBracketLevelChanged;
		$newDepthCount = $this->scanCodeLines->depthCount;
		$actLineNumber = $this->scanCodeLines->lineNumber;

		if ($isChanged)
		{
			if ($lastDepthCount != $newDepthCount)
			{

				// next level
				if ($lastDepthCount < $newDepthCount)
				{
					if ($lastDepthCount > -1)
					{
						print (str_repeat(' ', 3 * $lastDepthCount) . " { "
							. $actLineNumber . '/' . $newDepthCount . PHP_EOL);
					}
					else
					{
						print ($lastDepthCount . ":{ "
							. $actLineNumber . '/' . $newDepthCount . PHP_EOL);
					}
				}
				else
				{
					if ($newDepthCount > -1)
					{
						print (str_repeat(' ', 3 * $newDepthCount) . " } "
							. $actLineNumber . '/' . $newDepthCount . PHP_EOL);
					}
					else
					{
						print ($newDepthCount . ":{ "
							. $actLineNumber . '/' . $newDepthCount . PHP_EOL);
					}
				}

				$this->lastDepthCount = $this->scanCodeLines->depthCount;
			}
			else
			{
				if ($newDepthCount > -1)
				{
					print (str_repeat(' ', 3 * $newDepthCount) . " {...}/}...{ "
						. $actLineNumber . '/' . $newDepthCount . PHP_EOL);
				}
				else
				{
					print ($newDepthCount . ":{ "
						. $actLineNumber . '/' . $newDepthCount . PHP_EOL);
				}
			}
		}
	}

	public function execute(): int
	{
		//--- collect files ---------------------------------------

//        // files not set already
//        if (count($this->fileNamesList->fileNames) == 0) {
//            $fileNamesList = new fileNamesList ($this->srcRoot, 'php',
//                '', $this->isNoRecursion);
//            $this->fileNamesList = $fileNamesList;
//
//            $fileNamesList->scan4Filenames();
//        }

//        // collect file list if not existing
//        if (count($this->fileNamesList->fileNames) == 0) {
//            $this->fileNamesList->execute();
//
//            if (count($this->fileNamesList->fileNames) == 0) {
//
//                echo '%%% Attention: No files retrieved from: "' . $this->fileNamesList->srcRoot . '"    %%%' . PHP_EOL;
//                return -975;
//            }
//        }
//
//        // tell factory to use classes
//        $this->exchangeSinceLinesFile->assignOptionCallerProjectId($this->callerProjectId);
//
//        $this->exchangeSinceLinesFile->assignOptions($this->isForceOverwrite,
//            $this->isForceVersion, $this->isLogOnly, $this->versionId);
//
//        //--- iterate over all files -------------------------------------
//
//        foreach ($this->fileNamesList->fileNames as $fileName) {
//
//            print('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%' . PHP_EOL);
//
//            $this->exchangeSinceLinesFile->exchangeSinceLines($fileName->srcPathFileName, $this->versionId);
//        }

		$filePathName = $this->fileNamesList->srcRoot . '/' . $this->fileName;

		if (is_file($filePathName))
		{

			$inLines = file($filePathName);

			$this->scanCodeLines = new codeScannerByLine();

			foreach ($inLines as $line)
			{

				$this->nextLine($line);

			}
		}
		else
		{
			print ('!!! File for ident log not found: "' . $filePathName . '" !!!' . PHP_EOL);
		}

		return 0;
	}

	public function text(): string
	{
		$OutTxt = "------------------------------------------" . PHP_EOL;
		$OutTxt .= "--- codeScanner_logIndent ---" . PHP_EOL;


		$OutTxt .= "Not defined yet " . PHP_EOL;

		/**
		 * $OutTxt .= "fileName: " . $this->fileName . PHP_EOL;
		 * $OutTxt .= "fileExtension: " . $this->fileExtension . PHP_EOL;
		 * $OutTxt .= "fileBaseName: " . $this->fileBaseName . PHP_EOL;
		 * $OutTxt .= "filePath: " . $this->filePath . PHP_EOL;
		 * $OutTxt .= "srcPathFileName: " . $this->srcPathFileName . PHP_EOL;
		 * /**/

		return $OutTxt;
	}

} // class
