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

        print('------------------------------------------' . "\r\n");
        print ('Command line: ');

        // caller
        if (count($inArgs) > 0) {
            print ($inArgs[0] . "\r\n" . " ");
        }

        // option '/name '
        for ($i = 1; $i < count($options); $i++) {
            echo ($options[$i] . " ");
        }

        // attributes 'name '
        for ($i = 1; $i < count($inArgs); $i++) {
            print ($inArgs[$i] . " ");
        }

        print('' . "\r\n");

        print('Start time:   ' . $start->format('Y-m-d H:i:s') . "\r\n");
        print('------------------------------------------' . "\r\n");

        return $start;
    }

    /*--------------------------------------------------------------------
    print_inArgs
    --------------------------------------------------------------------*/

    // read arguments direct
    public static function print_inArgs()
    {
        global $argc, $argv;

        print('------------------------------------------' . "\r\n");
        print ('Direct Command line: ');

        for ($i = 1; $i < $argc; $i++) {
            echo ($argv[$i]) . " ";
        }
        print('' . "\r\n");
    }

    /*--------------------------------------------------------------------
    commandLineLib::print_end
    --------------------------------------------------------------------*/

    public static function print_end(DateTime $start)
    {
        $now = new DateTime ();
        print('' . "\r\n");
        print('End time:               ' . $now->format('Y-m-d H:i:s') . "\r\n");
        $difference = $start->diff($now);
        print('Time of run:            ' . $difference->format("%H:%I:%S") . "\r\n");
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
                print ("--- argv ---" . "\r\n");
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
                    print ("--- inArgs ---" . "\r\n");
                    var_dump($inArgs);
                }
            }

            //--- extract options ---------------------------------

            $options = getopt($optDefinition, []);

            if ($isPrintArguments) {
                if (!empty ($inArgs)) {
                    print ("--- in options ---" . "\r\n");
                    var_dump($options);
                }
            }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return [$inArgs, $options];
    }

}