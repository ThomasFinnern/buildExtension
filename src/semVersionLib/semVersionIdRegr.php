<?php

/*================================================================================
regression for semVersionId class
================================================================================*/

namespace Finnern\BuildExtension\src\semVersionLib;

use Finnern\BuildExtension\src\tasksLib\options;

require_once '../autoload/autoload.php';

$HELP_MSG = <<<EOT
    >>>
    tests for semVersionId class

    <<<
    EOT;


function doTest(string $description, string $inVersionId, string $expectedVersionId, options $options): bool
{
    $hasError = false;

    print ("\n-------------------------------------" . "\n");
    print ($description . "\n");
    print ('inVersionId: "' . $inVersionId . '"' . "\n");
    print ('options: "' . $options . '"' . "\n");
    print ('expectedVersionId: "' . $expectedVersionId . '"' . "\n");

    //--- create class -----------------------------------------

    $versionId = new semVersionId($inVersionId);

    //--- assign options -----------------------------------------

    foreach ($options->options as $option)
    {
        $isVersionOption = $versionId->assignVersionOption($option);
        if (!$isVersionOption)
        {
            print ('!!! Error: option is not a valid version option: "' . $option . '" !!!' . "\n");
        }
    }

    //--- execute update by options ---------------------------------

    $outVersionId = $versionId->update();

    //--- check result ---------------------------------

    print ("--- checks ----------------------------------" . "\n");

    print ('\$outVersionId: "' . $outVersionId . '"' . "\n");

    if ($outVersionId != $expectedVersionId)
    {
        print ('!!! Error: result does not match expected. see above !!!' . "\n");
        $hasError = true;
    }

    return $hasError;
}

function appendPreReleases($versions = [], $preTypes = [])
{
    $preVersions = [];

    foreach ($versions as $version => $expectedVersion)
    {
        foreach ($preTypes as $type => $expectedType)
        {
            $preVersion  = $version . $type;
            $preExpected = $expectedVersion . $expectedType;

            $preVersions [$preVersion] = $preExpected;

            // ToDo:  remove
            //break;
        }

        // ToDo:  remove
        //break;
    }

    return $preVersions;
}


function doTestList($collection = [], $optionsLine = '', $description = '')
{
    $errCount = 0;

// Outer list
    foreach ($collection as $versions)
    {
        // real items
        foreach ($versions as $version => $expected)
        {
            $inVersionId       = $version; // "1.0.0";
            $expectedVersionId = $expected;

            $options = new options()->extractOptionsFromString($optionsLine);

            $hasError = doTest($description, $inVersionId, $expectedVersionId, $options);
            if ($hasError)
            {
                $errCount++;
            }
        }
    }


    return $errCount;
}


/*==============================================================================
main tests
==============================================================================*/

/*------------------------------------------------------------------------------
Test: No change options
------------------------------------------------------------------------------*/

$verMajor = ['1' => '1.0.0', '1.0' => '1.0.0', '1.1' => '1.1.0', '1.0.0' => '1.0.0', '1.0.1' => '1.0.1', '1.0.0.0' => '1.0.0', '1.0.0.1' => '1.0.0.1'];
$verMinor = ['1.1' => '1.1.0', '1.1.0' => '1.1.0', '1.1.1' => '1.1.1', '1.1.0.0' => '1.1.0', '1.1.0.1' => '1.1.0.1'];
$verPatch = ['1.1.1' => '1.1.1', '1.1.1.0' => '1.1.1', '1.1.0.1' => '1.1.0.1'];
$verBuild = ['1.1.1.1' => '1.1.1.1', '1.1.1.2' => '1.1.1.2'];

//--- Pre releases ------------------------------------------------

// append prerelease text to each version
$preRelaseTypes = ['-beta1' => '-beta1', '-alpha1' => '-alpha1', '-rc1' => '-rc1', '-rc2' => '-rc2'];
$verMajorPre    = appendPreReleases($verMajor, $preRelaseTypes);
$verMinorPre    = appendPreReleases($verMinor, $preRelaseTypes);
$verPatchPre    = appendPreReleases($verPatch, $preRelaseTypes);
$verBuildPre    = appendPreReleases($verMajor, $preRelaseTypes);

$collection = [$verMajor, $verMinor, $verPatch, $verBuild, $verMajorPre, $verMinorPre, $verPatchPre, $verBuildPre];
// $collection = [$verMajorPre]; // Test pre ids (beta, alpha, rc ..
// 6.1.0-alpha4-dev
// 6.0.0-beta3 to 6.0.0-rc1

$errCount = 0;

$optionsLine = "/isBeautify";
$description = "No change options";

$errCount += doTestList($collection, $optionsLine, $description);

/*------------------------------------------------------------------------------
Test: increase main
------------------------------------------------------------------------------*/

$verMajor = ['1' => '2.0.0', '1.0' => '2.0.0', '1.1' => '2.0.0', '1.0.0' => '2.0.0', '1.0.1' => '2.0.0', '1.0.0.0' => '2.0.0', '1.0.0.1' => '2.0.0'];
$verMinor = ['1.1' => '2.0.0', '1.1.0' => '2.0.0', '1.1.1' => '2.0.0', '1.1.0.0' => '2.0.0', '1.1.0.1' => '2.0.0'];
$verPatch = ['1.1.1' => '2.0.0', '1.1.1.0' => '2.0.0', '1.1.0.1' => '2.0.0'];
$verBuild = ['1.1.1.1' => '2.0.0', '1.1.1.2' => '2.0.0'];

//--- Pre releases ------------------------------------------------

// append prerelease text to each version
$preRelaseTypes = ['-beta1' => '', '-alpha1' => '', '-rc1' => '', '-rc2' => ''];
$verMajorPre    = appendPreReleases($verMajor, $preRelaseTypes);
$verMinorPre    = appendPreReleases($verMinor, $preRelaseTypes);
$verPatchPre    = appendPreReleases($verPatch, $preRelaseTypes);
$verBuildPre    = appendPreReleases($verMajor, $preRelaseTypes);

$collection = [$verMajor, $verMinor, $verPatch, $verBuild, $verMajorPre, $verMinorPre, $verPatchPre, $verBuildPre];
// $collection = [$verMajorPre]; // Test pre ids (beta, alpha, rc ..
// 6.1.0-alpha4-dev
// 6.0.0-beta3 to 6.0.0-rc1

$errCount = 0;

$optionsLine = "/isIncreaseMajor";
$description = "No change options";

$errCount += doTestList($collection, $optionsLine, $description);


/*------------------------------------------------------------------------------
Test: increase ????
------------------------------------------------------------------------------*/


/*------------------------------------------------------------------------------
=> done
------------------------------------------------------------------------------*/

if ($errCount > 0)
{
    print ("----------------------------------------------------------" . "\n");
    print ('!!! Errors found: ' . $errCount . '. See above !!!' . "\n");
    print ("----------------------------------------------------------" . "\n");
}

print ('<<< done ========================================');

