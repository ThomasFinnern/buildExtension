<?php

namespace Finnern\BuildExtension\src\codeScanner;

/**
 * Scans code given line by line. It keeps several states
 *  - bracket depth
 *  - inside function
 *  - inside comment
 *  - inside pre function comments
 *
 */
class codeScanner_logComment extends codeScannerByLine
{
    protected bool $isInCommentSection = false; // Section -> /*...*/
    public bool $isInPreFunctionComment = false; // -> /**...*/ .. function
    protected bool $isInsideFunction = false;

    public int $lineNumber = 0; // 1...
    public int $depthCount = 0; // 0... depth count

    // public string $bracketStack ='';

    public function __construct()
    {
        parent::__construct();

        print ("codeScanner_logIdent __construct: " . PHP_EOL);
    }

    protected function init()
    {
        parent::init();
    }

    public function nextLine($line) {

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


}