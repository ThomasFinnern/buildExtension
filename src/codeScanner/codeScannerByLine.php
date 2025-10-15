<?php

namespace Finnern\BuildExtension\src\codeScanner;

/**
 * Scans code given line by line. It keeps several states
 *  - bracket depth
 *  - inside function
 *  - inside comment
 *  - inside pre function comments
 *
 * ToDo:
 *  - tell mismatch of brackets with line logs l114: } (4) -> Type and actual level (python: buildTree)
 *  - codeScanner command file: using codeScanner from command line
 *  - Python see remove comments Python.zip: \ifdef_endif\ifdef_endif.py
 *  - see LangMan4Dev: searchTransStrings function searchTransStrings_in_PHP_file ....
 *    removeCommentPHP
 */
class codeScannerByLine
{
    protected bool $isInCommentSection = false; // Section -> /*...*/
    public bool $isInPreFunctionComment = false; // -> /**...*/ .. function
    protected bool $isInsideFunction = false;

    public int $lineNumber = 0; // 1...
    public int $depthCount = 0; // 0... depth count

    // public string $bracketStack ='';

    public function __construct()
    {

        print ("codeScanner __construct: " . PHP_EOL);
        $this->init();

    }

    private function init()
    {
        $this->isInCommentSection = false; // Section -> /*...*/
        $this->isInPreFunctionComment = false; // -> /**...*/ .. function
        $this->isInsideFunction = false;

        $this->lineNumber = 0; // 1...
        $this->depthCount = 0; // 0... depth count


    }

    public function nextLine($line) {

        $this->lineNumber++;

        // WIP: actual pre comment and just inside function , more to code ;-)

        //--- remove comments --------------

        $bareLine = $this->removeCommentPHP($line, $this->isInCommentSection);

        $this->checkBracketlLevel ($bareLine); // $depthCount

        $this->checkInsideFunction ($bareLine); // $depthCount

        $this->checkPreFunctionComment ($line); // $depthCount


    }

    /**
     * @param $line
     * @param $isInComment
     *
     * @return false|mixed|string
     *
     * @throws \Exception
     * @since version
     */
    public function removeCommentPHP($line, &$isInComment)
    {
        $bareLine = $line;

        try {
            // No inside a '/*' comment
            if (!$isInComment) {
                //--- check for comments ---------------------------------------

                $doubleSlash   = '//';
                $slashAsterisk = '/*';

                $doubleSlashIdx   = strpos($line, $doubleSlash);
                $slashAsteriskIdx = strpos($line, $slashAsterisk);

                // comment exists, keep start of string
                if ($doubleSlashIdx != false || $slashAsteriskIdx != false) {
                    if ($doubleSlashIdx != false && $slashAsteriskIdx == false) {
                        $bareLine = strstr($line, $doubleSlash, true);
                    } else {
                        if ($doubleSlashIdx == false && $slashAsteriskIdx != false) {
                            $bareLine    = strstr($line, $slashAsterisk, true);
                            $isInComment = true;
                        } else {
                            //--- both found ---------------------------------

                            // which one is first
                            if ($doubleSlashIdx < $slashAsteriskIdx) {
                                $bareLine = strstr($line, $doubleSlash, true);
                            } else {
                                $bareLine    = strstr($line, $slashAsterisk, true);
                                $isInComment = true;
                            }
                        }
                    }
                } // No comment indicator

            } else {
                //--- Inside a '/*' comment

                $bareLine = '';

                $asteriskSlash    = '*/';
                $asteriskSlashIdx = strpos($line, $asteriskSlash);

                // end found ?
                if ($asteriskSlashIdx != false) {
                    // Keep end of string
                    $bareLine = strstr($line, $asteriskSlash);

                    // handle rest of string
                    $isInComment = false;
                    $bareLine    = $this->removeCommentPHP($bareLine, $isInComment);
                }
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing removeCommentPHP: "' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';

            print ($OutTxt);
        }

        return $bareLine;
    }

    private function checkBracketlLevel(string $inLine)
    {
        $line = trim($inLine);

        $open = strpos($line, '{');
        $close = strpos($line, '}');

        if($open !== false) {
            $this->depthCount++;
            // ToDo: 'push' open line number  '{':114
            // ToDo: write tree file function from 'pushed' ..
            // ToDO: ...
            // ToDO:  class active -> depth +1 ?
        }

        if($close !== false) {
            $this->depthCount--;

            if ($this->depthCount < 0) {

                print (" !!! ==> negative bracket '0' count !!!");
            }

            // ToDo: 'push' close line number '}':114
        }
    }

    private function checkInsideFunction(string $inLine)
    {

        $line = trim($inLine);

        $isFunction = strpos($line, ' function ');
        $close = strpos($line, '}');

        // already back to base level on last bracket level check
        if($close !== false && $this->depthCount == 1) {
            $this->isInsideFunction = false;
        }

        // ToDO:  class active -> depth +1 ? ==> not active ?
        if ($isFunction !== false && $this->depthCount == 1) {
            $this->isInsideFunction = false;
        }
    }

    private function checkPreFunctionComment(string $inLine)
    {

        $line = trim($inLine);

        $open = strpos($line, '/**');
        $close = strpos($line, '*/');

        // ToDO:  class active -> depth +1 ? ==> not active ?
        if($open !== false && $this->depthCount == 1) {
            $this->isInPreFunctionComment = true;
        }

        if($close !== false) {
            $this->isInPreFunctionComment = false;
        }
    }


}