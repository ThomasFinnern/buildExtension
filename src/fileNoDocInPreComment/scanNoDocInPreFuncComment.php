<?php

namespace Finnern\BuildExtension\src\fileNoDocInPreComment;

use Finnern\BuildExtension\src\codeScanner\codeScannerByLine;

/**
 * Not standard scan part: isFunctionStart
 */
class scanNoDocInPreFuncComment extends codeScannerByLine
{

//    private bool $isClassStartLine;
    public bool $isScanFileEnabled = false;

    public function __construct()
	{
		parent::__construct();

		print ("scanMissingPreHeader __construct: " . PHP_EOL);
		$this->init();
	}

	protected function init()
	{
//		$this->isClassStartLine = false;
		$this->isScanFileEnabled = false;
	}

	public function nextLine($line): string
	{
		$bareLine = parent::nextLine($line);

        if ( ! $this->isScanFileEnabled)
        {
            if (str_starts_with($bareLine, 'namespace '))
            {
                $this->isScanFileEnabled = true;
            }

            if (str_starts_with($bareLine, 'use '))
            {
                $this->isScanFileEnabled = true;
            }

            if (str_starts_with($bareLine, 'class '))
            {
//            $this->isClassStartLine = true;
                $this->isScanFileEnabled = true;
            }

        }

		return $bareLine;
	}


} // class
