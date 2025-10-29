<?php

namespace Finnern\BuildExtension\src\codeScanner;

/**
 * Scans code given line by line.
 *
 * It keeps several states
 *  - bracket depth
 *  - inside function
 *  - inside comment
 *  - inside pre function comments
 *
 * It does no interpret then though
 */
class codeScannerByLine
{
	public bool $isInCommentSection = false; // Section -> /*...*/
	public bool $isInPreFunctionComment = false; // -> /**...*/ .. function
	public bool $isInsideFunction = false;

	public bool $isFunctionStartLine = false;
	public bool $isFunctionEndLine = false;
	public bool $isFunctionReturnLine = false;
	public bool $isPreFuncCommentStartLine = false;
	public bool $isPreFuncCommentEndLine = false;

	// ToDo: public bool $isAfterNamespace = false;
	// ToDo: public bool $isAfterUse = false;
	public bool $isInClass = false;

	public int $lineNumber = 0; // 1...
	public int $depthCount = 0; // 0... depth count
	public int $functionDepth = 0; // 0:without/outside class 1:within class

	// public string $bracketStack ='';
	public bool $isBracketLevelChanged = false;

	public function __construct()
	{
		print ("codeScanner __construct: " . PHP_EOL);
		$this->init();
	}

	protected function init()
	{
		$this->isInCommentSection        = false; // Section -> /*...*/
		$this->isInPreFunctionComment    = false; // -> /**...*/ .. function
		$this->isInsideFunction          = false;
		$this->isInClass                 = false;

		$this->clearLineFlags ();

		$this->lineNumber = 0; // 1...
		$this->depthCount = 0; // 0... depth count

	}

	/**
	 * Init single line flags
	 * @return void
	 */
	protected function clearLineFlags()
	{
		$this->isFunctionStartLine       = false;
		$this->isFunctionEndLine         = false;
		$this->isFunctionReturnLine      = false;
		$this->isPreFuncCommentStartLine = false;
		$this->isPreFuncCommentEndLine   = false;
	}

	public function nextLine($line): string
	{
		$this->lineNumber++;

		//--- reset single line states -------------------------------------------------

		$this->clearLineFlags ();

		//--- remove comments -------------------------------------------------

		$bareLine = $this->removeCommentPHP($line, $this->isInCommentSection);

		if (!$this->isInCommentSection)
		{
			//--- check inside a function states  -------------------------------------------------

			$this->checkInsideFunction($bareLine);

			if (!$this->isInClass)
			{
				$this->isInClass = $this->isInClass($bareLine);

				if ($this->isInClass)
				{
					$this->functionDepth = 1;
				}
			}
		}

		//--- check pre function comment  -------------------------------------------------

		if ($this->depthCount == $this->functionDepth)
		{
			$this->checkInPreFunctionComment($line);
		}

		$this->isBracketLevelChanged = $this->checkBracketLevel($bareLine); // $depthCount

		return $bareLine;
	}

	/**
	 * Removes comment part of the line
	 * a) Only '//...' then delete the rest of the line
	 *    This will only be checked if not inside lines comment '/* ...'
	 * b) On '/*' check the rest of the line
	 *    => recursive call with the following characters
	 *    => add call result to start of line
	 * c) On '...* /' (end of lines comment) check the rest of the line
	 *    => recursive call with the following characters
	 *    => add call result to start of line
	 *
	 * @param $line
	 * @param $isInComment
	 *
	 * @return false|mixed|string
	 *
	 * @since version
	 */
	// ToDo: &$isInComment => use local class variable
	public function removeCommentPHP($line, &$isInComment)
	{
		$bareLine = $line;

		try
		{
			// Not inside a '/*' comment
			if (!$isInComment)
			{

				//--- check for comment positions ---------------------------------------

				$doubleSlashIdx   = strpos($line, '//');
				$slashAsteriskIdx = strpos($line, '/*');

				// One or both comment types are present
				if ($doubleSlashIdx !== false || $slashAsteriskIdx !== false)
				{

					// both in one line => set later one to false
					if ($doubleSlashIdx !== false && $slashAsteriskIdx !== false)
					{

						if ($doubleSlashIdx < $slashAsteriskIdx)
						{
							// open first
							$slashAsteriskIdx = false;
						}
						else
						{
							// close first
							$doubleSlashIdx = false;
						}
					}

					// double slash '//'
					if ($doubleSlashIdx !== false)
					{
						$bareLine = substr($line, 0, $doubleSlashIdx);
					}
					else
					{
						// lines comment found '/*'
						if ($doubleSlashIdx === false && $slashAsteriskIdx !== false)
						{

							$isInComment = true;

							$bareLine   = substr($line, 0, $slashAsteriskIdx);
							$behindLine = substr($line, $slashAsteriskIdx + 2);
							$bareLine   .= $this->removeCommentPHP($behindLine, $isInComment);
						}
					}
				}

			}
			else
			{
				//--- Inside a '/*' comment

				$bareLine = '';

				$asteriskSlash    = '*/';
				$asteriskSlashIdx = strpos($line, $asteriskSlash);

				// end found ?
				if ($asteriskSlashIdx !== false)
				{

					$isInComment = false;

					// Keep end of string for further checks
					$behindLine = substr($line, $asteriskSlashIdx + 2);
					$bareLine   .= $this->removeCommentPHP($behindLine, $isInComment);
				}
			}
		}
		catch (\RuntimeException $e)
		{
			$OutTxt = '';
			$OutTxt .= 'Error executing removeCommentPHP: "' . '<br>';
			$OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

			print ($OutTxt);
		}

		return $bareLine;
	}

	/**
	 *
	 * Attention brackets in text PHP/C++/Python not supported yet. Example:
	 *    $logOptions['format']    = '{DATE}\t{TIME}\t{LEVEL}\t{CODE}\t{MESSAGE}';
	 *
	 * @param   string  $inLine
	 *
	 * @return bool
	 */
	private function checkBracketLevel(string $inLine): bool
	{
		$isChanged = false;

		$line = trim($inLine);

		//--- check for bracket positions ---------------------------------------

		$openIdx  = strpos($line, '{');
		$closeIdx = strpos($line, '}');

		// One or both comment types are present
		if ($openIdx !== false || $closeIdx !== false)
		{

			$isChanged = true;

			// both in one line => set later one to false
			if ($openIdx !== false && $closeIdx !== false)
			{
//				// => set later one to false
//				if ($openIdx < $closeIdx)
//				{
//					// open first
//					$closeIdx = false;
//				}
//				else
//				{
//					// close first
//					$openIdx = false;
//				}

				// Attention more than 3 open/close can't be detected actually
				// depth should be the same
				$openIdx = false;
				$closeIdx = false;
			}

			// open '{'
			if ($openIdx !== false)
			{
				$this->depthCount++;

				$bareLine   = substr($line, 0, $openIdx);
				$behindLine = substr($line, $openIdx + 1);
				$this->checkBracketLevel($behindLine);
			}

			// close '}'
			if ($closeIdx !== false)
			{
				$this->depthCount--;

				$bareLine   = substr($line, 0, $closeIdx);
				$behindLine = substr($line, $closeIdx + 1);
				$this->checkBracketLevel($behindLine);
			}

			if ($this->depthCount < 0)
			{

				print (" !!! ==> negative bracket '0' count in Line: " . $this->lineNumber . " !!!" . PHP_EOL);
			}
		}

		return $isChanged;
	}

	private function checkInsideFunction(string $inLine)
	{
		$line = trim($inLine);

		// ToDo:  class active -> depth +1 ? ==> not active ?
		if ($this->depthCount == $this->functionDepth)
		{
			//--- start of function --------------------------------

			$isFunctionStartLine = $this->isFunctionStart($inLine);

			if ($isFunctionStartLine)
			{
				$this->isInsideFunction = true;
			}

			$this->isFunctionStartLine = $isFunctionStartLine;
		}

		// Debug
		$isCloseBracket = strpos($line, '}') !== false;
		if ($isCloseBracket)
		{
			$isCloseBracket = $isCloseBracket;
		}

		// End with fall back (lower)  (pre depth check. therefor -1
		if ($this->depthCount-1 <= $this->functionDepth)
		{
			//--- end of function --------------------------------

			$isCloseBracket = strpos($line, '}') !== false;

			// already back to base level on last bracket level check
			if ($isCloseBracket && $this->depthCount-1 == $this->functionDepth)
			{
				$this->isInsideFunction = false;
				$this->isFunctionEndLine = true;
			}
		}

		// inside function
		if ($this->isInsideFunction)
		{
			if ($this->depthCount == ($this->functionDepth + 1))
			{
				$this->isFunctionReturnLine = $this->isFunctionReturn($inLine);
			}
		}
	}

	private function checkInPreFunctionComment(string $inLine)
	{

		$line = trim($inLine);

		if (!$this->isInPreFunctionComment)
		{
			//--- Start of pre function comment --------------------------------

			$isOpenPreComment = strpos($line, '/**') !== false;;

			// ToDO:  class active -> depth +1 ? ==> not active ?
			if ($isOpenPreComment !== false && $this->depthCount == $this->functionDepth)
			{
				$this->isInPreFunctionComment    = true;
				$this->isPreFuncCommentStartLine = true;
			}
			else
			{
				$this->isPreFuncCommentStartLine = false;
			}

		}
		else
		{
			//--- End of pre function comment --------------------------------

			$isCloseComment = strpos($line, '*/') !== false;

			// does even isCloseComment line: /**/
			if ($isCloseComment !== false)
			{
				$this->isInPreFunctionComment  = false;
				$this->isPreFuncCommentEndLine = true;
			}
		}

	}

	private function isFunctionStart(string $bareLine)
	{
		$isFunctionStartLine = false;

//		// public / protected / ... ???
		if (str_contains($bareLine, 'function'))
		{
//			$isFunctionStartLine = true;

			$exp = "/function .*\(/i";

			$isFunctionStartLine = (bool) preg_match($exp, $bareLine);
			// $isFunctionStartLine = $isFunctionStartLine;
		}

		return $isFunctionStartLine;
	}

	private function isFunctionReturn(string $inLine)
	{
		$isFunctionReturnLine = false;

		if (str_contains($inLine, 'return'))
		{
//		// public / protected / ... ???
			if (str_starts_with(trim($inLine), 'return'))
			{
				$isFunctionReturnLine = true;
			}
		}

		return $isFunctionReturnLine;
	}

	private function isInClass(string $inLine)
	{
		$isInClass = false;

		if (str_starts_with(trim($inLine), 'class'))
		{
			$isInClass = true;
		}

		return $isInClass;
	}


}