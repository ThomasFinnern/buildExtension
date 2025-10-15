<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileSinceLib;

// use Finnern\BuildExtension\src\fileManifestLib\copyrightText;

/*================================================================================
Class fileHeader data
================================================================================*/

/**
 * Keeps all lines of a PHP files as preLines,useLines and postLines
 * The “Use” lines are as in the following line
 *    use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
 *
 * !!! comments above use line will be ignored and deleted
 */
class fileSinceDataBase implements fileSinceDataInterface
{
    protected bool $isChanged;

    // protected string $commentSpaces=4;

    public function __construct()
    {
        // $this->useLines = [];

        $this->init();
    }

    public function init(): void
    {
        $this->isChanged = false;

    }


    function checkLine(string $line): bool
    {
        $isToBeChanged = false;

//        $sinceLine = $this->createSinceLine($alignIdx, $versionId);
//
//
//        // TODO: Implement checkLine() method.

        return $isToBeChanged;
    }

    /**
     * @param string $line
     * @param string $versionId
     * @return string
     *
     *
     * @since __BUMP_VERSION__
     * @since version
     * @since version 4.2 will be ignored
     */
    function exchangeLine(string $line = '', string $versionId = 'xx.xx',
                          int    $alignIdx = 0,
                          bool   $isForceVersion = false, bool $isLogOnly = false,
                          int    $lineNbr = 1): string
    {
        $isChanged = false;
        $exchangedLine = $line;

        $sinceLine = $this->createSinceLine($alignIdx, $versionId);

        if (str_contains($line, "@since")) {

            if ($isForceVersion) {
                $exchangedLine = $sinceLine;
                $isChanged = true;
            } else {
                if (str_contains($line, "__BUMP_VERSION__")) {
                    $exchangedLine = $sinceLine;
                    $isChanged = true;

                } else {
                    if (str_contains($line, "version")) {
                        $exchangedLine = $sinceLine;
                        $isChanged = true;
                    } else {

                        // needs codescanner as base class and then derived ... class checking just pre function comments


                        // check length of space after since
                        $test = 5;

                    }

                }
            }

            if ($isChanged) {
                if (!$isLogOnly) {
                    $this->isChanged = true;
                } else {

                    // ToDo: ? line
                    print ("@since diff line: " . $lineNbr . PHP_EOL);
                    print ("original: '" . rtrim($line) . "'" . PHP_EOL);
                    print ("improved: '" . $exchangedLine . "'" . PHP_EOL);

                    $exchangedLine = $line;
                }

            }

        }
        return $exchangedLine;
    }


    function isChanged(): bool
    {
        return $this->isChanged;
    }

    /**
     * @param int $startIdx
     * @param string $versionId
     * @return string
     */
    public
    function createSinceLine(int $startIdx, string $versionId): string
    {
        $sinceLine = str_pad(" * @since ", $startIdx) . $versionId;
        return $sinceLine;
    }

}
