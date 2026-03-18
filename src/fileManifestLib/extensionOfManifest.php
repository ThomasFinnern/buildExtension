<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

// use DateTime;


/* ToDo: functions
hasExtension
nameMatchesRegEx
pathMatches regex

text ();
/**/


/*================================================================================
Class extensionOfManifest
================================================================================*/

use Exception;
use SimpleXMLElement;

class extensionOfManifest
{

    /* component, plugin,module , (package) */
    public string $extType = "";

    // id: com_rsgallery2
    public string $id = "";

    // plugin group
    public string $group = "";

    // client: site, administrator
    public string $client = "";

    // zip name expected
    public string $zipName = "";

    public bool $isComponent = false;
    public bool $isPlugin = false;
    public bool $isModule = false;

    public extensionsByManifest|null $parent;

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/

    public function __construct(extensionsByManifest|null $parent = null)
    {
        $hasError = 0;
        try
        {
//            print('*********************************************************' . PHP_EOL);
//            print ("srcFolder: " . $srcFolder . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);

            $this->parent = $parent;
        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }

    /*--------------------------------------------------------------------
    assignLine
    --------------------------------------------------------------------*/

    function assignXmlFileItem(simpleXMLElement|null $item = null)
    {
        $hasError = -99;

        try
        {
//            print('*********************************************************' . PHP_EOL);
//            print('assignLine' . PHP_EOL);
//            print("srcSpecifiedName: " . $srcFolder . PHP_EOL);
//            print('---------------------------------------------------------' . PHP_EOL);
//            print("Collect folder: " . $srcFolder . PHP_EOL);

            $this->clear();

            if ($item instanceof SimpleXMLElement)
            {

                $hasError = -80;

                $this->zipName = (string) $item;
//                $zipName = (string) $item->zipName;

                foreach ($item->attributes() as $name => $value)
                {
                    echo $name, '="', $value, "\"\n";

                    switch ($name)
                    {
                        case 'id':
                            $this->id = (string) $value;
                            break;

                        case 'type':
                            $this->extType = (string) $value;
                            $this->assignTypeFlags($this->extType);
                            break;

                        case 'group':
                            $this->group = (string) $value;
                            break;

                        case 'client':
                            $this->client = (string) $value;
                            break;

                        default:
                            print ('%%% collectExtensionsOfManifest: neither "fileName" nor "folder" element found: "' . (string) $name . '"->"' . (string) $item . '"' . PHP_EOL);
                            $hasError = -60;
                            break;
                    }


                }

                $hasError = 0;

            }


        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception in assignXmlFileItem: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

//        print('exit assignLine: ' . $hasError . PHP_EOL);
        return $hasError;
    }

    /*--------------------------------------------------------------------
    clear: init to empty
    --------------------------------------------------------------------*/

    function clear()
    {
        $this->extType     = "";
        $this->id          = "";
        $this->group       = "";
        $this->client      = "";
        $this->zipName     = "";
        $this->isComponent = false;
        $this->isPlugin    = false;
        $this->isModule    = false;
    }

    public function text(): string
    {
        $OutTxt = "";
        // $OutTxt .= "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- extensionOfManifest ---" . PHP_EOL;

        $OutTxt .= "id: " . $this->id . PHP_EOL;
        $OutTxt .= "extType : " . $this->extType . PHP_EOL;
        if ($this->isPlugin)
        {
            $OutTxt .= "group: " . $this->group . PHP_EOL;
        }
        if ($this->isModule)
        {
            $OutTxt .= "client: " . $this->client . PHP_EOL;
        }
        $OutTxt .= "zipName: " . $this->zipName . PHP_EOL;

        switch ($this->extType)
        {
            case 'plugin':
                $OutTxt .= "isPlugin: " . $this->isPlugin . PHP_EOL;
                break;
            case 'module':
                $OutTxt .= "isModule: " . $this->isModule . PHP_EOL;
                break;
            case 'component':
                $OutTxt .= "isComponent: " . $this->isComponent . PHP_EOL;
                break;

            default:
                break;
        }

        return $OutTxt;
    }

    /**
     * local and parent flags
     *
     * @param   string  $extType
     *
     * @return void
     */
    private function assignTypeFlags(string $extType)
    {
        switch ($extType)
        {

            case 'plugin':
                $this->isPlugin            = true;
                $this->parent->isHasPlugin = true;
                break;

            case 'module':
                $this->isModule            = true;
                $this->parent->isHasModule = true;
                break;

            case 'component':
                $this->isComponent            = true;
                $this->parent->isHasComponent = true;
                break;

            default:
                break;
        }

    }

} // extensionOfManifest
