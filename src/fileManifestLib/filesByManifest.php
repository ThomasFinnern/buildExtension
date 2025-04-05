<?php

namespace Finnern\BuildExtension\src\fileManifestLib;

use Exception;
use Finnern\BuildExtension\src\fileManifestLib\manifestXml;
use Finnern\BuildExtension\src\tasksLib\baseExecuteTasks;
use Finnern\BuildExtension\src\tasksLib\executeTasksInterface;
use Finnern\BuildExtension\src\tasksLib\task;
use SimpleXMLElement;

//use Finnern\BuildExtension\src\versionLib\versionId;

/*================================================================================
Class filesByManifest
================================================================================*/

/*
 * It collects all files and folders from the manifest  
 * It regards admin or standard section and files path in the attribute 
 * of the section
 * It cares for folder language and media ...
 * 
 * ToDo: better examples (more on files and folders)
 * plugin example
    <extension type="plugin" group="webservices" method="upgrade">
		<name>plg_webservices_content</name>
		<author>Joomla! Project</author>
		<version>4.0.0</version>
		<description>PLG_WEBSERVICES_CONTENT_XML_DESCRIPTION</description>
		<namespace path="src">Joomla\Plugin\WebServices\Content</namespace>
		<files>
			<folder plugin="content">services</folder>
			<folder>src</folder>
		</files>
		<languages>
			<language tag="en-GB">language/en-GB/plg_webservices_content.ini</language>
			<language tag="en-GB">language/en-GB/plg_webservices_content.sys.ini</language>
		</languages>
	</extension>

 * component example	
	<extension type="component" method="upgrade">
	  <name>com_rsgallery2</name>
	  <creationDate>2024.09.13</creationDate>
	  <author>RSGallery2 Team</author>
	...
	<scriptfile>install_rsg2.php</scriptfile>

	<install>
		<sql>
			<file driver="mysql"
				  charset="utf8">sql/install.mysql.utf8.sql
			</file>
		</sql>
	</install>
	<uninstall>
		<sql>
			<file driver="mysql"
				  charset="utf8">sql/uninstall.mysql.utf8.sql
			</file>
		</sql>
	</uninstall>
	<update>
		<schemas>
			<schemapath type="mysql">sql/updates/mysql</schemapath>
		</schemas>
	</update>

	<administration>
        <files folder="administrator/components/com_rsgallery2/">
            <filename>access.xml</filename>
            <filename>changelog.xml</filename>
			...
            <folder>sql</folder>
            <folder>src</folder>
            <folder>tmpl</folder>
        </files>
    </administration>
	
    <!-- Front-end files -->
    <files folder="components/com_rsgallery2">
        <!--folder>forms</folder-->
        <folder>language</folder>
        <folder>layouts</folder>
        <folder>src</folder>
        <folder>tmpl</folder>
    </files>

    <!-- css, js, images files, .... -->
    <media folder="media/com_rsgallery2"
           destination="com_rsgallery2">
        <filename>joomla.asset.json</filename>
        <folder>css</folder>
        <folder>images</folder>
        <folder>js</folder>
    </media>

	
	</extension>
 *
 */

// ToDo: !!! read xml instead of lines !!!
// ToDo: include version class handling better into manifest
class filesByManifest extends baseExecuteTasks
    implements executeTasksInterface
{
    public string $manifestPathFileName = '';

    // internal
    public false|SimpleXMLElement $manifestXml = false; // XML: false or SimpleXMLElement

    public bool $isChanged = false;

    //--- manifest variables ---------------------------------------

    public array $files;
    public array $folders;


    /*====================================================
    class constructor
    ====================================================*/
    public function __construct(
        $srcRoot = "",
        $manifestPathFileName = ''
    ) {
        try {
            parent::__construct($srcRoot, false);

            // $this->manifestXml = new SimpleXMLElement(???);
            $this->manifestPathFileName = $manifestPathFileName;

            $this->files = [];
            $this->folders = [];

//            if (is_file($manifestPathFileName)) {
//
//                // does read xml immediately
//                $this->readFile($manifestPathFileName);
//            } else {
//                // ToDo: error message or create file ?
//                $this->manifestXml = new manifestXml($manifestPathFileName);
//            }


        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
        }
        // print('exit __construct: ' . $hasError . "\r\n");
    }



    // Task name with options
    public function assignTask(task $task): int
    {
        $this->taskName = $task->name;

        $options = $task->options;

        foreach ($options->options as $option) {

            $isBaseOption = $this->assignBaseOption($option);

            // version: ToDo: create on construct and use flags on execute
//            // base options are already handled
//            if (!$isBaseOption) {
//                $isVersionOption = $this->versionId->assignVersionOption($option);
//            }

//            // base options are already handled
//            if (!$isBaseOption && !$isVersionOption) {
            if (!$isBaseOption) {

                $this->assignManifestOption($option);
                // $OutTxt .= $task->text() . "\r\n";
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

//        $isVersionOption = $this->versionId->assignVersionOption($option);

//        if ( ! $isVersionOption) {

                switch (strtolower($option->name)) {
                    // filesByManifest
                    case strtolower('manifestFile'):
                        print ('     option ' . $option->name . ': "' . $option->value . '"' . "\r\n");
                        $this->manifestPathFileName = $option->value;
                        $isManifestOption = true;
                        break;


                } // switch

//        }

//        return $isManifestOption ||  $isVersionOption;
        return $isManifestOption;
    }

    public function execute(): int // $hasError
    {
        print('*********************************************************' . "\r\n");
        print ("Execute filesByManifest: " . "\r\n");
        print('---------------------------------------------------------' . "\r\n");

        $hasError = 0;

        $this->collectFilesAndFolders();

        return $hasError;
    }

    private function extractFilesFolderFromSection(SimpleXMLElement $xmlPath)
    {
        if (isset($xmlPath)) {

            $baseFolder = (string) $xmlPath['folder'];

            foreach($xmlPath->children() as $name => $item)
            {
//                echo (string)$name;
//                echo (string)$item;

                switch (strtolower($name)) {
                    case 'filename':
                        $this->files [] = $baseFolder . '/' . (string)$item;
                        break;

                    case 'folder':
                        $this->folders [] = $baseFolder . '/' . (string)$item;
                        break;

                    default:
                        print ('%%% extractFilesFolderFromSection: neither "fileName" nor "folder" element found: "' . (string)$name . '"->"' . (string)$item . '"' . "\r\n");
                        break;
                }
            }
        }
    }

    private function extractLanguageFilesFromSection(SimpleXMLElement $xmlPath)
    {
        if (isset($xmlPath)) {

            $baseFolder = (string) $xmlPath['folder'];

            foreach($xmlPath->children() as $name => $item)
            {
//                echo (string)$name;
//                echo (string)$item;

                switch (strtolower($name)) {
                    case 'language':
                        $this->files [] = $baseFolder . '/' . (string)$item;
                        break;

                    default:
                        print ('%%% extractLanguageFilesFromSection: "language" element not found: "' . (string)$name . '"->"' . (string)$item . '"' . "\r\n");
                        break;
                }
            }

        }
    }

    private function extractScriptFile(SimpleXMLElement $xmlPath) :void
    {
        if (isset($xmlPath)) {

            $baseFolder = (string) $xmlPath['folder'];

            $scriptName = (string) $xmlPath;

//            if (empty($baseFolder) {
//                $this->files [] = $scriptName;
//            } else {
//                $this->files [] = $baseFolder . '/' . $scriptName;
//            }
            // script file expected on root
            $this->files [] = $scriptName;

        }
    }


    public function executeFile(string $filePathName): int
    {
        // TODO: Implement executeFile() method.
        return 0;
    }

    /**
     * @return void
     */
    public function collectFilesAndFolders(): void
    {
        $this->files = [];
        $this->folders = [];

        // Manifest file must be loaded
        if (!empty ($this->manifestXml)) {

            //--- script file -------------------------------------------

            // element<scriptfile>install_rsg2.php</scriptfile>
            if (isset($this->manifestXml->scriptfile)) {
                $this->extractScriptFile($this->manifestXml->scriptfile);
            }

            //--- default files -------------------------------------------

            // site / module / plugin

            if (isset($this->manifestXml->files)) {

                $this->extractFilesFolderFromSection($this->manifestXml->files);
            }

            //--- administration -------------------------------------------

            if (isset($this->manifestXml->administration)) {
                if (isset($this->manifestXml->administration->files)) {

                    $this->extractFilesFolderFromSection($this->manifestXml->administration->files);
                }
            }

            //--- media -------------------------------------------

            if (isset($this->manifestXml->media)) {

                $this->extractFilesFolderFromSection($this->manifestXml->media);
            }


            //--- api -------------------------------------------

            if (isset($this->manifestXml->api)) {
                if (isset($this->manifestXml->api->files)) {

                    $this->extractFilesFolderFromSection($this->manifestXml->api->files);
                }
            }


            //--- language -------------------------------------------

            // is included by folder in sie/administration section
            if (isset($this->manifestXml->languages)) {
                $this->extractLanguageFilesFromSection($this->manifestXml->languages);
            }

            $test = 'debug dummy';
        }
    }


    private function requestVariables() : bool
    {
        $isChanged = false;

        try {

            $manifestXml = $this->manifestXml;

            // foreach ($this->requests as $requestName => $requestValue) {
                // switch (strtolower($requestName)) {

                    // case strtolower('componentName'):
                        // // component / module / plugin

                        // // component name like com_rsgallery2
                        // // direct assignment to XML element
                        // print ('     request: ' . $requestName . ' ' . $requestValue . "\r\n");
                        // $this->manifestXml->setByXml($requestName, $requestValue);

                        // $isChanged = true;
                        // break;

                // }
            // }

        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage() . "\r\n";
            $hasError = -101;
        }

        return $isChanged;
    }


    public function text(): string
    {
        $OutTxt = "------------------------------------------" . "\r\n";
        $OutTxt .= "--- filesByManifest ---" . "\r\n";

        $OutTxt .= "manifestPathFileName: " . $this->manifestPathFileName . "\r\n";

        $OutTxt .= "[files] ("  . count($this->files) . ')' . "\r\n";
        //$OutTxt .= "   " . "files count: " . count($this->files) . "\r\n";

        foreach ($this->files as $file) {
            $OutTxt .= "   * " . $file . "\r\n";
        }

        $OutTxt .= "[folders] (" . count($this->folders) . ')' . "\r\n";
        //$OutTxt .= "   " . "folders count: " . count($this->folders) . "\r\n";

        foreach ($this->folders as $folder) {
            $OutTxt .= "   * " . $folder . "\r\n";
        }

        return $OutTxt;
    }

}
