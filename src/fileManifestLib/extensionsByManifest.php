<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

use Exception;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\tasksLib\task;
use SimpleXMLElement;

// use Finnern\BuildExtension\src\fileManifestLib\extensionOfManifest;

//use Finnern\BuildExtension\src\semVersionLib\semVersionId;

/*================================================================================
Class extensionsByManifest
================================================================================*/
/*
<?xml version="1.0" encoding="UTF-8"?>
<extension type="package" version="1.0" method="upgrade">
  <name>PKG_RSGALLERY2</name>
  <packagename>Rsgallery2</packagename>
  <packager>RSGallery2 Team</packager>
  <packagerurl>https://www.rsgallery2.org</packagerurl>
  <creationDate>2026.03.16</creationDate>
  <copyright>(c) 2003-2026 RSGallery2 Team</copyright>
  <author>RSGallery2 Team</author>
  <authorEmail>team2@rsgallery2.org</authorEmail>
  <authorUrl>https://www.rsgallery2.org</authorUrl>
  <version>5.0.0.6</version>
  <license>GNU General Public License version 2 or later; see LICENSE.txt</license>
  <description>PKG_RSGALLERY2_XML_DESCRIPTION</description>
  <scriptfile>install_pkg.php</scriptfile>
  <files>
    <!-- The id for each extension is the element stored in the DB -->
	  <file id="com_rsgallery" type="component">com_rsgallery.zip</file>
	  <file id="plg_console_rsg2_console" type="plugin" group="console">plg_console_rsg2_console.zip</file>
	  <file id="plg_webservices_rsgallery2" type="plugin" group="webservices">plg_webservices_rsgallery2.zip</file>
	  <file id="plg_content_rsg2_gallery" type="plugin" group="content">plg_content_rsg2_gallery.zip</file>

<!--	  <file id="plg_content_rsg2_galleries" type="plugin" group="console">plg_content_rsg2_galleries.zip</file>-->

	  <file type="module" id="mod_rsg2_gallery.xml" client="site">mod_rsg2_gallery.zip</file>
	  <file type="module" id="mod_rsg2_slideshow.xml" client="site">mod_rsg2_slideshow.zip</file>
  </files>
  <updateservers>
    <server type="extension" name="Foo Updates">https://raw.githubusercontent.com/YYYastridx/boilerplate/tutorial/foo_update.xml</server>
  </updateservers>
</extension>
*/

// ToDo: !!! read xml instead of lines !!!
// ToDo: include version class handling better into manifest
class extensionsByManifest extends baseExecuteTasks implements executeTasksInterface
{
    public string $manifestPathFileName = '';

    // internal
    public false|SimpleXMLElement $manifestXml = false; // XML: false or SimpleXMLElement

    public bool $isHasComponent = false;
    public bool $isHasPlugin = false;
    public bool $isHasModule = false;

    //--- manifest variables ---------------------------------------

    public array $oExtensions;

    /*====================================================
    class constructor
    ====================================================*/
    public function __construct($srcRoot = "", $manifestPathFileName = '')
    {
        try
        {
            parent::__construct($srcRoot, false);

            // $this->manifestXml = new SimpleXMLElement(???);
            $this->manifestPathFileName = $manifestPathFileName;

            $this->oExtensions = [];

//            if (is_file($manifestPathFileName)) {
//
//                // does read xml immediately
//                $this->readFile($manifestPathFileName);
//            } else {
//                // ToDo: error message or create file ?
//                $this->manifestXml = new manifestXml($manifestPathFileName);
//            }


        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
        }
        // print('exit __construct: ' . $hasError . PHP_EOL);
    }


    // Task name with options
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option)
        {

            $isBaseOption = $this->assignBaseOption($option);

            // version: ToDo: create on construct and use flags on execute
//            // base options are already handled
//            if (!$isBaseOption) {
//                $isVersionOption = $this->semVersionId->assignVersionOption($option);
//            }

//            // base options are already handled
//            if (!$isBaseOption && !$isVersionOption) {
            if (!$isBaseOption)
            {

                $this->assignManifestOption($option);
                // $OutTxt .= $task->text() . PHP_EOL;
            }
        }

        return 0;
    }

    /**
     *
     * @param   mixed  $option
     * @param   task   $task
     *
     * @return bool
     */
    public function assignManifestOption(mixed $option): bool
    {
        // ToDo: on each option assign isForce... , then use it on write
        // Actual assigned options are only used if the value is not set in the manifest file

        $isManifestOption = false;

//        $isVersionOption = $this->semVersionId->assignVersionOption($option);

//        if ( ! $isVersionOption) {

        switch (strtolower($option->name))
        {
            // extensionsByManifest
            case strtolower('manifestFile'):
                print ('     option ' . $option->name . ': "' . $option->value . '"' . PHP_EOL);
                $this->manifestPathFileName = $option->value;
                $isManifestOption           = true;
                break;


        } // switch

//        }

//        return $isManifestOption ||  $isVersionOption;
        return $isManifestOption;
    }

    public function execute(): int // $hasError
    {
        print('---------------------------------------------------------' . PHP_EOL);
        print("Execute extensionsByManifest: " . PHP_EOL);
        print('---------------------------------------------------------' . PHP_EOL);

        $hasError = 0;

        $this->collectExtensionsOfManifest();

        return $hasError;
    }

    /**
     * @return void
     */
    public function collectExtensionsOfManifest(): void
    {
        print (PHP_EOL);
        print ('--- collect files and folders -----------------' . PHP_EOL);

        $this->oExtensions = [];

        // Manifest file must be loaded
        if (!empty ($this->manifestXml))
        {


            //--- default files -------------------------------------------

            // site / module / plugin

            if (isset($this->manifestXml->files))
            {
                $xmlPath = $this->manifestXml->files;
                if (isset($xmlPath))
                {

                    // $baseFolder = (string) $xmlPath['folder'];

                    foreach ($xmlPath->children() as $name => $item)
                    {
//                echo (string)$name;
//                echo (string)$item;

                        switch (strtolower($name))
                        {
                            case strtolower('file'):
                                $oExtension = new extensionOfManifest($this);
                                $hasError   = $oExtension->assignXmlFileItem($item);

                                if (!$hasError)
                                {
                                    $this->oExtensions[] = $oExtension;
                                }
                                break;

                            default:
                                print ('%%% collectExtensionsOfManifest: neither "fileName" nor "folder" element found: "' . (string) $name . '"->"' . (string) $item . '"' . PHP_EOL);
                                break;
                        }
                    }
                }
            }
            else
            {
                print ('%%% collectExtensionsOfManifest: missing files section in manifest file ' . PHP_EOL);
            }


            $test = 'debug dummy';
        }

    }


    public function getComponent(): extensionOfManifest|null
    {
        $isFound = false;
        foreach ($this->oExtensions as $extension)
        {
            if ($extension->isComponent)
            {
                $isFound = true;

                break;
            }
        }

        if ($isFound)
        {
            return $extension;
        }
        else
        {
            return null;
        }
    }

    public function getPlugins() :array
    {
        $extensions = [];

        foreach ($this->oExtensions as $extension)
        {
            if ($extension->isPlugin)
            {
                $extensions[] = $extension;            }
        }

        return $extensions;
    }

    public function getModules()
    {
        $extensions = [];

        foreach ($this->oExtensions as $extension)
        {
            if ($extension->isModule)
            {
                $extensions[] = $extension;            }
        }

        return $extensions;
    }

    private function extractLanguageFilesFromSection(SimpleXMLElement $xmlPath)
    {
        if (isset($xmlPath))
        {

            $baseFolder = (string) $xmlPath['folder'];

            foreach ($xmlPath->children() as $name => $item)
            {
//                echo (string)$name;
//                echo (string)$item;

                switch (strtolower($name))
                {
                    case strtolower('language'):
                        $this->files [] = $baseFolder . '/' . (string) $item;
                        break;

                    default:
                        print ('%%% extractLanguageFilesFromSection: "language" element not found: "' . (string) $name . '"->"' . (string) $item . '"' . PHP_EOL);
                        break;
                }
            }

        }
    }

    private function extractDirectFolderFromSection(SimpleXMLElement $xmlPath)
    {
        if (isset($xmlPath))
        {

            $baseFolder       = (string) $xmlPath->getName();
            $this->folders [] = $baseFolder;

        }
    }

    public function executeFile(string $filePathName): int
    {
        // TODO: Implement executeFile() method.
        return 0;
    }

    public function text(): string
    {
        $OutTxt = "------------------------------------------" . PHP_EOL;
        $OutTxt .= "--- extensionsByManifest ---" . PHP_EOL;

        $OutTxt .= "manifestPathFileName: " . $this->manifestPathFileName . PHP_EOL;

        $OutTxt .= "[extensions] (" . count($this->oExtensions) . ')' . PHP_EOL;
        //$OutTxt .= "   " . "files count: " . count($this->files) . PHP_EOL;
        $OutTxt .= "isHasComponent: " . $this->isHasComponent . PHP_EOL;
        $OutTxt .= "isHasPlugin: " . $this->isHasPlugin . PHP_EOL;
        $OutTxt .= "isHasModule: " . $this->isHasModule . PHP_EOL;

        foreach ($this->oExtensions as $extension)
        {
            $OutTxt .= $extension->text(); // . PHP_EOL;
        }

        return $OutTxt;
    }

    private function requestVariables(): bool
    {
        $isChanged = false;

        try
        {

            $manifestXml = $this->manifestXml;

            // foreach ($this->requests as $requestName => $requestValue) {
            // switch (strtolower($requestName)) {

            // case strtolower('componentName'):
            // // component / module / plugin

            // // component name like com_rsgallery2
            // // direct assignment to XML element
            // print ('     request: ' . $requestName . ' ' . $requestValue . PHP_EOL);
            // $this->manifestXml->setByXml($requestName, $requestValue);

            // $isChanged = true;
            // break;

            // }
            // }

        }
        catch (Exception $e)
        {
            echo '!!! Error: Exception: ' . $e->getMessage() . PHP_EOL;
            $hasError = -101;
        }

        return $isChanged;
    }


}
