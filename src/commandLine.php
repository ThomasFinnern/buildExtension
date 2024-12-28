<?php

namespace commandLine;

use DateTime;

/*--------------------------------------------------------------------
print_header
--------------------------------------------------------------------*/

function print_header($options, $inArgs): DateTime
{
    global $argc, $argv;

    $start = new DateTime();

    print('------------------------------------------' . "\r\n");
    print ('Command line: ');

    for ($i = 1; $i < $argc; $i++) {
        echo ($argv[$i]) . " ";
    }

    print('' . "\r\n");
    print('Start time:   ' . $start->format('Y-m-d H:i:s') . "\r\n");
    print('------------------------------------------' . "\r\n");

    return $start;
}

/*--------------------------------------------------------------------
print_end
--------------------------------------------------------------------*/

function print_end(DateTime $start)
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
function argsAndOptions($argv, string $optDefinition, bool $isPrintArguments): array
{
    $options = [];
    $inArgs = [];

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

    return [$inArgs, $options];
}


