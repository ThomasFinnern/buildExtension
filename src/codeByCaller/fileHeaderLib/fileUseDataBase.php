<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileHeaderLib;

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
class fileUseDataBase implements fileUseDataInterface
{
    protected bool $isSortByLength;

    // Add backslash like "use \Joomla\CMS\Language\Text;"
    protected bool $isPrependBackSlash;
    // Remove backslash from "use \Joomla\CMS\Language\Text;"
    protected bool $isRemoveBackSlash;

    public array $preLines = [];
    public array $useLines = [];
    public array $useLinesSorted = [];
    public array $postLines = [];

    public function __construct()
    {
        $this->isSortByLength = false;
        $this->isPrependBackSlash = false;
        $this->isRemoveBackSlash = false;

        $this->init();
    }

    public function init(): void
    {

        $this->preLines = [];
        $this->useLines = [];
        $this->useLinesSorted = [];
        $this->postLines = [];

    }

    function extractUseLines(array $lines = []): void
    {
        // Reset all kept lines
        $this->init();

        $isInPreLines = true;
        $isInUseLines = false;
        // $isInPostLines = false;

        foreach ($lines as $line) {

            if ($isInPreLines) {

                //--- pre lines ------------------------------------

                $is_a_preLine = $this->is_a_preLine($line);
                if ($is_a_preLine) {

                    $this->preLines[] = $line;
                } else {
                    $this->useLines[] = $line;

                    $isInPreLines = false;
                    $isInUseLines = true;
                }

            } else {
                if ($isInUseLines) {

                    //--- use lines ------------------------------------

                    $is_a_postLine = $this->is_a_postLine($line);
                    if (!$is_a_postLine) {
                        // no space line, no comments
                        $isValidUseLine = $this->isValidUseLine($line);
                        if ($isValidUseLine) {
                            $this->useLines[] = $line;
                        }
                    } else {
                        $this->postLines[] = $line;

                        $isInUseLines = false;
                        //$isInPostLines = true;
                    }
                } else {
                    //--- post lines ------------------------------------

                    $this->postLines[] = $line;
                }
            }
        }


    }


    public static function sortUseLines(array $lines = [], bool $isSortByLength = false): array
    {
        if ($isSortByLength) {

            usort($lines, function($a, $b) {
                return strlen($b) <=> strlen($a);
            });
        } else {

            sort($lines);
        }

        return $lines;
    }

    function useLinesSorted(): array
    {
        if (empty($this->useLinesSorted)) {
            $this->useLinesSorted = self::sortUseLines($this->useLines, $this->isSortByLength);
        }

        return $this->useLinesSorted;
    }

    function fileLines(): array
    {
        $fileLines = [];

        try {

            if (empty($this->useLinesSorted)) {
                $this->useLinesSorted = self::sortUseLines($this->useLines, $this->isSortByLength);
            }

            $useLinesSorted = $this->applyBackslashType($this->useLinesSorted, $this->isPrependBackSlash, $this->isRemoveBackSlash);

            $fileLines = array_merge($this->preLines, $useLinesSorted, $this->postLines);

        } catch (\Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . "\r\n";
        }

        return $fileLines;
    }

    function isChanged(): bool
    {
        $isChanged = false;

        // TODO: Implement isChanged() method.
        $linesSorted = $this->useLinesSorted();

//        $array_diff = array_diff($linesSorted, $this->useLines);
//        if (count($array_diff) > 0) {
//            $isChanged = true;
//        }

        // $arraysAreEqual = ($linesSorted == $this->useLines); // TRUE if $a and $b have the same key/value pairs.
        $arraysAreEqual = ($linesSorted === $this->useLines); // TRUE if $a and $b have the same key/value pairs in the same order and of the same types.
        if (!$arraysAreEqual) {
            $isChanged = true;
        }
        return $isChanged;
    }

    private function is_a_preLine(string $line): bool
    {
        $is_a_preLine = false;

        $testLine = trim($line);
        if (!str_starts_with($testLine, "use ")) {
            $is_a_preLine = true;
        }

        return $is_a_preLine;
    }

    /**
     * Detect first not use line
     * Empty lines are ignored
     * Comment lines starting with '/**' accepted
     * Several breaking names like class lead to not use lines
     *
     * @param string $line
     * @return false
     */
    private function is_a_postLine(string $line): bool
    {
        $is_a_postLine = false;
        $testLine = trim($line);

        if (str_starts_with($testLine, "class")) {
            $is_a_postLine = true;
        } elseif (strlen($testLine) == 0) {
            $is_a_postLine = true;
        } elseif (str_starts_with($testLine, "require")) {
            $is_a_postLine = true;
        } elseif (str_starts_with($testLine, "interface")) {
            $is_a_postLine = true;
        } elseif (str_starts_with($testLine, "\$HELP_MSG")) {
            $is_a_postLine = true;
        } elseif (str_starts_with($testLine, "/*==")) {
            $is_a_postLine = true;
        } elseif (str_starts_with($testLine, "/**")) {
            $is_a_postLine = true;
        } elseif (str_starts_with($testLine, "/*--")) {
            $is_a_postLine = true;
        } elseif (str_starts_with($testLine, "")) {
            $is_a_postLine = true;
        }

        //--- detected too late -----------------------------------------

        // Some lines too late , so code must be changed if found


        if (str_starts_with($testLine, "{")) {
            throw new \Exception('!!! Unexpected after "use ..." line: "' . $line . '" !!!' . PHP_EOL
                . "Beginning of the “Post” lines not correctly recognized");
            // $is_a_postLine = true;
        }

        return $is_a_postLine;
    }

    private function isValidUseLine(mixed $line): bool
    {
        $isValidUseLine = false;

        $testLine = trim($line);
        if (str_starts_with($testLine, "use ")) {
            $isValidUseLine = true;
        }

        return $isValidUseLine;
    }

    private function applyBackslashType(array $useLinesIn, bool $isPrependBackSlash, bool $isRemoveBackSlash): array
    {
        $useLines = [];

        if ($isPrependBackSlash) {
            $useLines = $this->prependBackslash($useLinesIn);
        } elseif ($isRemoveBackSlash) {
            $useLines = $this->removeBackSlash($useLinesIn);
        } else {
            foreach ($useLinesIn as $useLine) {
                $useLines[] = $useLine;
            }
        }

        return $useLines;
    }

    private function prependBackslash(array $useLinesIn): array
    {
        $useLines = [];

        foreach ($useLinesIn as $useLine) {

            $idxUse = strpos($useLine, "use ");
            if ($idxUse !== false) {
                $idxNameSpace = $idxUse + 4;
                $namespace = trim(substr($useLine, $idxNameSpace));

                //--- add backslash ---------------------------------

                if ($namespace[0] != '\\') {
                    $namespace = '\\' . $namespace;
                    $useLine = substr($useLine, 0, $idxNameSpace) . ' ' . $namespace;

                    $useLines[] = $useLine;
                } else {

                    $useLines[] = $useLinesIn;
                }
            }

            $useLines[] = $useLine;
        }

        return $useLines;
    }

    private function removeBackSlash(array $useLinesIn): array
    {
        $useLines = [];

        foreach ($useLinesIn as $useLine) {

            $idxUse = strpos($useLine, "use ");
            if ($idxUse !== false) {
                $idxNameSpace = $idxUse + 4;
                $namespace = trim(substr($useLine, $idxNameSpace));

                //--- remove backslash ---------------------------------

                if ($namespace[0] == '\\') {
                    $namespace = substr($namespace, 1);
                    $useLine = substr($useLine, 0, $idxNameSpace) . ' ' . $namespace;

                    $useLines[] = $useLine;
                } else {

                    $useLines[] = $useLinesIn;
                }
            }

            $useLines[] = $useLine;
        }

        return $useLines;
    }

}
