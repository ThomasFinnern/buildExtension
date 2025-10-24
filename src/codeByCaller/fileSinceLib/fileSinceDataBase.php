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
    protected int $identSize = 4;

    // protected string $commentSpaces=4;

    public function __construct()
    {
        // $this->useLines = [];

        $this->init();
    }

    public function init(): void
    {
        $this->isChanged = false;
        $this->identSize = 4;
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
                          int    $lineNbr = 1,
                          string $prevAtLine = "",
                          bool   $isTabFound = false): string
    {
        $isChanged = false;
        $exchangedLine = $line;

        $sinceLine = $this->createSinceLine($alignIdx, $versionId, $isTabFound);

        if (str_contains($line, "@since")) {

            if ($isForceVersion) {

                if ($exchangedLine != $sinceLine) {
                    $exchangedLine = $sinceLine;
                    $isChanged = true;
                }

            } else {
                if (str_contains($line, "__BUMP_VERSION__")) {
                    $exchangedLine = $sinceLine;
                    $isChanged = true;

                } else {
                    if (str_contains($line, "version")) {
                        $exchangedLine = $sinceLine;
                        $isChanged = true;
                    } else {

                        /*
                         * code for align existing definition is postponed
                         */
                        // $actAlignIdx = yyyy
                        // check actual align length

                    }

                }
            }

            if ($isChanged) {

                if ($isLogOnly) {
                    print ("--- log only ------------------------------" . PHP_EOL);
                }

                print ("@since diff line: " . $lineNbr . PHP_EOL);
                print ("original: '" . rtrim($line) . "'" . PHP_EOL);
                print ("improved: '" . rtrim($exchangedLine) . "'" . PHP_EOL);
                print ("align   : '" . rtrim($prevAtLine) . "'" . PHP_EOL);

                if (!$isLogOnly) {

                    $this->isChanged = true;
                } else {
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
    function createSinceLine(int $startIdx, string $versionId, bool $isTabFound = false): string
    {
        if ($isTabFound) {
            $sinceStart = "\t" . " * @since ";
        } else {
            $sinceStart = str_repeat(' ', $this->identSize) . " * @since ";
        }

        $sinceLine = str_pad($sinceStart, $startIdx) . $versionId . PHP_EOL;

        return $sinceLine;
    }

}
