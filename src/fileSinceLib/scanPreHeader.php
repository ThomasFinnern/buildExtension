<?php

namespace Finnern\BuildExtension\src\fileSinceLib;

use Finnern\BuildExtension\src\codeScanner\codeScannerByLine;

class scanPreHeader extends codeScannerByLine
{
    public int $alignIdx = 0; // number of integer where all variables shoud start

    public function __construct()
    {
        parent::__construct();

        print ("scanPreHeader __construct: " . PHP_EOL);
        $this->init();
    }

    protected function init()
    {
        $alignIdx = 0;
    }

    public function nextLine($line)
    {

        parent::nextLine($line);

        if ($this->isInPreFunctionComment) {

            $this->alignIdx = $this->findAlignIdx($line, $this->alignIdx);

        }

    }

    /**
     * Align version text (or other) in @Since with formatting in lines above
     * Here the line above is checked for column index
     *
     * @param $line
     * @param int $inAlignIdx
     * @return int Index to first non ' ' chanracter after @... indicator
     */
    private function findAlignIdx($line, int $inAlignIdx)
    {
        $AlignIdx = $inAlignIdx;

        // 	 * @param   string  $id  A prefix for the store id.
        //	 *
        //	 * @return  string A store id.
        //	 *
        //	 * @since   4.0.0

        // line with '@'
        $atIdx=strpos($line, '@');

        if ($atIdx !== false) {
            $lastBlankIdx = strpos($line, ' ', $atIdx);

            if($lastBlankIdx !== false) {
                while($line[$lastBlankIdx] === ' ') {
                    $lastBlankIdx++;
                }

                $AlignIdx = $lastBlankIdx -1;
            }
        }

        return $AlignIdx;
    }

}