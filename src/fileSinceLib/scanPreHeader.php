<?php

namespace Finnern\BuildExtension\src\fileSinceLib;

use Finnern\BuildExtension\src\codeScanner\codeScannerByLine;

/**
 * Not standard scan part: detect pre header and @since line
 * Align with previous line indent after @
 */
class scanPreHeader extends codeScannerByLine
{
    public int $alignIdx = 0; // number of integer where all variables shoud start
    public bool $isTabFound = false;
    public bool $isAtSinceLine = false;
    public int $prevAlignIdx = 0;
    public string $prevAtLine = '';
    private string $actAtLine = '';

    public function __construct()
    {
        parent::__construct();

        print ("scanPreHeader __construct: " . PHP_EOL);
        $this->init();
    }

    protected function init()
    {
        $this->alignIdx = 0;
        $this->isTabFound = false;

        $this->prevAlignIdx = 0;
        $this->isAtSinceLine = false;
        $this->prevAtLine = '';
        $this->actAtLine = '';
    }

    public function nextLine($line): string
    {
        $bareLine = parent::nextLine($line);

        if ($this->isInPreFunctionComment) {

            $bareLine = $this->removeCommentLine($line);

            if (str_contains($line, '@')) {

                // keep track of last @ format
                $this->prevAlignIdx = $this->alignIdx;
                $this->prevAtLine = $this->actAtLine;

                // align index  behind first empty space
                $this->alignIdx = $this->findAlignIdx($bareLine, $this->alignIdx);
                $this->actAtLine = $bareLine;

                // Tab before '@' set ?
                if (!$this->isTabFound) {
                    $this->check4TabUse($line);
                }

                // indicate since
                $this->isAtSinceLine = str_contains($bareLine, '@since');
            }
        }

        return $bareLine;
    }

    /**
     * Align version text (or other) in @Since with formatting in lines above
     * Here the line above is checked for column index
     *
     * @param $line
     * @param int $inAlignIdx
     * @return int Index to first non ' ' character after @... indicator
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
        $atIdx = strpos($line, '@');

        if ($atIdx !== false) {
            $lastBlankIdx = strpos($line, ' ', $atIdx);

            if ($lastBlankIdx !== false) {
                while ($line[$lastBlankIdx] === ' ') {
                    $lastBlankIdx++;
                }

                $AlignIdx = $lastBlankIdx;
            }
        }

        return $AlignIdx;
    }

    private function check4TabUse($line)
    {
        // line with '@'
        $atIdx = strpos($line, '@');

        if ($atIdx !== false) {

            $preAt = substr($line, 0, $atIdx);
            if (str_contains($preAt, "\t")) {
                $this->isTabFound = true;
            }

        }
    }

    private function removeCommentLine(mixed $line)
    {
        $bareLine = $line;

        try {

            $doubleSlashIdx = strpos($line, '//');

            // double slash '//'
            if ($doubleSlashIdx !== false) {
                $bareLine = substr($line, 0, $doubleSlashIdx);
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing removeCommentPHP: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            print ($OutTxt);
        }

        return $bareLine;
    }

} // class