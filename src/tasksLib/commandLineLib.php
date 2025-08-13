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
        print ('Command line: ');

        // caller
        if (count($inArgs) > 0) {
            print ($inArgs[0] . PHP_EOL . " ");
        }

        // option '/name '
        for ($i = 1; $i < count($options); $i++) {
            echo ($options[$i] . " ");
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
    public static function argsAndOptions($argv, string $optDefinition, bool $isPrintArguments): array
    {
        $options = [];
        $inArgs = [];

        try {

            //--- argv ---------------------------------

            if ($isPrintArguments) {
                print ("--- argv ---" . PHP_EOL);
                var_dump($argv);
            }

            $inArgs = [];
            foreach ($argv as $inArg) {
                if (!str_starts_with($inArg, '-')) {
                    $inArgs[] = $inArg;
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
                if (!empty ($inArgs)) {
                    print ("--- in options ---" . PHP_EOL);
                    var_dump($options);
                }
            }

        } catch (Exception $e) {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return [$inArgs, $options];
    }

}