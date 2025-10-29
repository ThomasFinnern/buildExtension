<?php

namespace Finnern\BuildExtension\src\codeScanner;

use Finnern\BuildExtension\src\fileNamesLib\fileNamesList;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\tasksLib\option;

/**
 * Scans code given line by line.
 *
 *
 * !!! Attention code not ready as first intention is actually unknown . !!
 * "" class is kept as a further code task construct / base !!!
 * Intention: print a log lfor each comment ?
 *
 * See working example in class codeScanner_logIndent
 *
 * ToDo: Actually defined for one file but may be extended to more files ?
 *
 *
 *
 *
 */
class codeScanner_logComment extends baseExecuteTasks
	implements executeTasksInterface
{

	private bool $isDummy;
	// private codeScannerByLine $scanCodeLines;
	private string $fileName;
	private int $lastDepthCount = 0;

	public function __construct()
	{
		parent::__construct();

		print ("codeScanner_logComment __construct: " . PHP_EOL);
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

		// See working example in class codeScanner_logIndent

		//        $this->lineNumber++;
		//
		//        // WIP: actual pre comment and just inside function , more to code ;-)
		//
		//        //--- remove comments --------------
		//
		//        $bareLine = $this->removeCommentPHP($line, $this->isInCommentSection);
		//
		//        $this->checkBracketlLevel ($bareLine); // $depthCount
		//
		//        $this->checkInsideFunction ($bareLine);
		//
		//        $this->checkPreFunctionComment ($line);

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
		$OutTxt .= "--- codeScanner_logComment ---" . PHP_EOL;


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


}