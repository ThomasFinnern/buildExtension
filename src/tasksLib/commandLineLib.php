<?php

namespace Finnern\BuildExtension\src\tasksLib;

use DateTime;
use Exception;


class commandLineLib
{
    // Extracts the elements of the command line like in python


    /*--------------------------------------------------------------------
    print_header
    --------------------------------------------------------------------*/

    public static function print_header($options, $inArgs): DateTime
    {
        $start = new DateTime();

        print('------------------------------------------' . PHP_EOL);
        print ('PHP: Command line extracted: ' . PHP_EOL);

        // caller
        if (count($inArgs) > 0) {
//            print ('Args:' . PHP_EOL);
//            print ($inArgs[0] . " " . PHP_EOL);
            print ($inArgs[0] . " ");
        }

        if (count($options) > 0) {
            foreach ($options as $idx => $option)
            {
                print ('-' . $idx . " '" . $option . "' ");
            }
        }

        // attributes 'name '
        for ($i = 1; $i < count($inArgs); $i++) {
            print ($inArgs[$i] . " ");
        }

        print('' . PHP_EOL);

        print('Start time:   ' . $start->format('Y-m-d H:i:s') . PHP_EOL);
        print('------------------------------------------' . PHP_EOL);

        return $start;
    }

    /*--------------------------------------------------------------------
    print_inArgs
    --------------------------------------------------------------------*/

    // read arguments direct
    public static function print_inArgs()
    {
        global $argc, $argv;

        print('------------------------------------------' . PHP_EOL);
        print ('Direct Command line: ');

        for ($i = 1; $i < $argc; $i++) {
            echo ($argv[$i]) . " ";
        }
        print('' . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    commandLineLib::print_end
    --------------------------------------------------------------------*/

    public static function print_end(DateTime $start)
    {
        $now = new DateTime ();
        print('' . PHP_EOL);
        print('End time:               ' . $now->format('Y-m-d H:i:s') . PHP_EOL);
        $difference = $start->diff($now);
        print('Time of run:            ' . $difference->format("%H:%I:%S") . PHP_EOL);
    }

    /**
     * @return array
     */
    public static function argsAndOptions($argv, string $optDefinition, bool $isPrintArguments = false): array
    {
        $options = [];
        $inArgs = [];

        try {

            //--- argv ---------------------------------

            $inArgs = [];

            $isOption = false;

            foreach ($argv as $inArg) {

                // value after '-?' option
                if ($isOption) {
                    $isOption = false;
                    continue;
                }

                if (!str_starts_with($inArg, '-')) {
                    $inArgs[] = $inArg;
                } else {
                    $optChar = $inArg[1];

                    // is option with value ? 'x:' => -X test=yyyyy
                    $isOption = strpos($optDefinition , $optChar . ':'  ? true : false);
                }
            }

            if ($isPrintArguments) {
                if (!empty ($inArgs)) {
                    print ("--- inArgs ---" . PHP_EOL);
                    var_dump($inArgs);
                }
            }

            //--- extract options ---------------------------------

            $options = getopt($optDefinition, []);

            if ($isPrintArguments) {
                if (!empty ($options)) {
                    print ("--- in options ---" . PHP_EOL);
                    var_dump($options);
                }
            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return [$inArgs, $options];
    }

}