<?php
/**
 * @package         LangMan4Dev
 * @subpackage      com_lang4dev
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2019-2025 RSGallery2 Team
 * @license         GNU General Public License version 2 or later
 */

namespace Finnern\BuildExtension\src\fileManifestLib;

use DOMDocument;
use Exception;
use SimpleXMLElement;

// https://www.php.net/manual/de/simplexml.examples-basic.php

/**
 * Container for manifest xml elements
 * On creation the manifest XML data will be read if file path is given
 * getter/setter functions will change the loaded xml elements.
 * On change the xml may be written back 
 *
 * @package
 * @since       version
 */
class manifestXml
{
    /**
     * @var string
     * @since version
     */
    public $prjXmlFilePath = '';
    public $prjXmlPathFilename = '';

    /** @var bool */
    public bool $isXmlLoaded = false;
    public bool $isXmlChanged = false;


    public false|SimpleXMLElement $manifestXml = false; // XML: false or SimpleXMLElement

    /**
     * @param $prjXmlPathFilename
     * @throws Exception
     */
    public function __construct($prjXmlPathFilename = '')
    {

        // filename given
        if ($prjXmlPathFilename != '') {
            $this->prjXmlPathFilename = $prjXmlPathFilename;
            $this->prjXmlFilePath = dirname($prjXmlPathFilename);
            
            $this->readManifestXml();
        } else {
            $this->prjXmlPathFilename = "";
            $this->prjXmlFilePath     = "";
        }
    }

    /**
     * @param $prjXmlPathFilename
     *
     * @return bool
     *
     * @throws Exception
     * @since version
     */
    public function readManifestXml($prjXmlPathFilename = '') : bool
    {
        $this->isXmlLoaded = false;
        $this->isXmlChanged = false;

        try {
            //--- name from class or function  ---------------------------------------

            // use new file
            if ($prjXmlPathFilename != '') {
                $this->prjXmlPathFilename = $prjXmlPathFilename;
                $this->prjXmlFilePath     = dirname($prjXmlPathFilename);
                // ToDo: clear old data
            } else {
                // use given path name
                $prjXmlPathFilename = $this->prjXmlPathFilename;
            }

            //--- read XML -----------------------------------------------------------

            // file exists
            if (is_file($prjXmlPathFilename)) {

                // Read the file to see if it's a valid component XML file
                $xml = simplexml_load_file($prjXmlPathFilename);
                $this->manifestXml = $xml;

                // error reading ?
                if (!empty($xml)) {
                    $this->isXmlLoaded = true;
                } else {
                    $OutTxt = 'Error executing readManifestXml: manifest file is not an xml document: "' . $prjXmlPathFilename . '"';
                    print $OutTxt;
                }

            } else {
                $OutTxt = 'Error executing readManifestXml: manifest file does not exist: "' . $prjXmlPathFilename . '"';
                print $OutTxt;
            }
        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing readManifestXml: "' . $prjXmlPathFilename . '"<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
            print $OutTxt;
        }

        return $this->isXmlLoaded;
    }

    /**
     * @param $prjXmlPathFilename
     * @return bool is saved
     */
    public function writeManifestXml_OnChange(string $prjXmlPathFilename = '') : bool
    {
        if ($this->isXmlChanged) {
            return $this->writeManifestXml($prjXmlPathFilename);
        }

        return false;
    }

    /**
     * @param $prjXmlPathFilename
     * @return bool is saved
     */
    public function writeManifestXml(string $prjXmlPathFilename = '') : bool
    {
        //--- name from class or function  ---------------------------------------

        // use new file
        if ($prjXmlPathFilename != '') {
            $this->prjXmlPathFilename = $prjXmlPathFilename;
            $this->prjXmlFilePath     = dirname($prjXmlPathFilename);
            // ToDo: clear old data
        } else {
            // use given path name
            $prjXmlPathFilename = $this->prjXmlPathFilename;
        }

        //--- save XML -----------------------------------------------------------

        // XML data must be present
        if ($this->isXmlLoaded) {
//            $this->manifestXml->asXml($prjXmlPathFilename);
            // $isSaved = true;

            $domxml = new DOMDocument('1.0');
            $domxml->preserveWhiteSpace = false;
            $domxml->formatOutput = true;
            /* @var $xml SimpleXMLElement */
            $domxml->loadXML($this->manifestXml->asXML());
            $domxml->save($prjXmlPathFilename);

            $this->isXmlChanged = false;
        }

        return ! $this->isXmlChanged;
    }
    // info cast to string / int .. when using it (otherwise array is returned)

    /**
     * Take values direct from Xml of manifest
     *
     * @param $name
     * @param $default
     *
     * @return mixed
     *
     * @since version
     */
    public function getByXml($name, $default)
    {
        $result = $default;

        try {
            if (isset($this->manifestXml->$name)) {

                $result = (string) $this->manifestXml->$name;
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing getByXml: "' . $prjXmlPathFilename . '"<br>';
            $OutTxt .= 'name: "' . $name . '"' . '<br>';
            $OutTxt .= 'default: "' . $default . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
            print $OutTxt;

            // $hasError = -987;
        }

        return $result;
    }

    /**
     * Take values direct from Xml of manifest
     *
     * @param $name
     * @param $default
     *
     * @return mixed
     *
     * @since version
     */
    public function getAttributeByXml(string $elementName, string $elementAttributeName, $default) : string
    {
        $result = $default;

        try {
            if (isset($this->manifestXml->$elementName)) {
                $element = $this->manifestXml->$elementName;
                $result = (string) $element[$elementAttributeName];
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing getAttributeByXml: "' . $prjXmlPathFilename . '"<br>';
            $OutTxt .= 'name: "' . $name . '"' . '<br>';
            $OutTxt .= 'default: "' . $default . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
            print $OutTxt;

            // $hasError = -987;
        }

        // return isset($this->manifestXml->$name) ? $this->manifestXml->$name : $default;
        return $result;
    }

    /**
     * Take values direct from Xml of manifest
     *
     * @param $name
     * @param $default
     *
     * @return mixed
     *
     * @since version
     */
    public function setByXml(string $name, string $value) : int
    {
//		return isset($this->manifestXml->$name) ? $this->manifestXml->$name : $default;
//        $hasError = $this->manifestXml->$name;
        $hasError = 0;

        try {
            // if (isset($this->manifestXml->$name)) {
                $found = (string) $this->manifestXml->$name;

                if ($found != $value) {
                    $this->manifestXml->$name = $value;
                    $this->isXmlChanged = true;
                }
            // }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing setByXml: "' . $prjXmlPathFilename . '"<br>';
            $OutTxt .= 'name: "' . $name . '"' . '<br>';
            $OutTxt .= 'value: "' . $value . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
            print $OutTxt;

            $hasError = -987;
        }

        // return isset($this->manifestXml->$name) ? $this->manifestXml->$name : $default;
        return $hasError;
    }

    /**
     * Take values direct from Xml of manifest
     *
     * @param $name
     * @param $default
     *
     * @return mixed
     *
     * @since version
     */
    public function setAttributeByXml(string $elementName, string $elementAttributeName, string $value) : int
    {
        $hasError = 0;

        try {
            if (isset($this->manifestXml->$elementName))
            {
                $element = $this->manifestXml->$elementName;
                $found = $element[$elementAttributeName];

                if ($found != $value) {

                    $element[$elementAttributeName] = $value;
                    $this->isXmlChanged = true;
                }
            } else {
                // base element extension
                if ($elementName == 'extension') {

                    $found = (string) $this->manifestXml[$elementAttributeName];

                    if ($found != $value) {

                        $this->manifestXml[$elementAttributeName] = $value;
                        $this->isXmlChanged = true;
                    }
                }
            }

        } catch (\RuntimeException $e) {
            $OutTxt = '';
            $OutTxt .= 'Error executing setAttributeByXml: "' . $prjXmlPathFilename . '"<br>';
            $OutTxt .= 'name: "' . $name . '"' . '<br>';
            $OutTxt .= 'value: "' . $value . '"' . '<br>';
            $OutTxt .= 'Error: "' . $e->getMessage() . '"' . '<br>';
            print $OutTxt;

            $hasError = -987;
        }

        // return isset($this->manifestXml->$name) ? $this->manifestXml->$name : $default;
        return $hasError;
    }


//    /**
//     * @param $names
//     * @param $default
//     *
//     * @return bool|mixed
//     *
//     * @since version
//     */
//    public function getByXPath($names, $default)
//    {
//        // return null on wrong path
//        $result = $default;
//
//        if (!is_array($names)) {
//            $name = array($names);
//        }
//
//        $base = $this->manifestXml;
//        foreach ($names as $name) {
//            $base = isset($this->manifestXml->$name) ? $this->manifestXml->$name : null;
//
//            if ($base == null) {
//                break;
//            }
//        }
//
//        if ($base != null) {
//            $result = $base;
//        }
//
//        return $result;
//    }

//    /**
//     *
//     * @return string
//     *
//     * @since version
//     */
//    public function getSriptFile()
//    {
//        return (string)$this->getByXml('scriptfile', '');
//    }
//
//    /**
//     *
//     * @return string
//     *
//     * @since version
//     */
//    public function getName()
//    {
//        return (string)$this->getByXml('name', '');
//    }
//
    // info cast to string / int .. when using it (otherwise array is returned)

    /**
     * @param $name
     *
     * @return null
     *
     * @since version
     */
    public function getXml($name)
    {
        return isset($this->manifestXml->$name) ? $this->manifestXml->$name : null;
    }



//	protected function loadManifestFromData(\SimpleXMLElement $xml)
//	{
//		$test              = new stdClass();
//		$test->name        = (string) $xml->name;
//		$test->packagename = (string) $xml->packagename;
//		$test->update      = (string) $xml->update;
//		$test->authorurl   = (string) $xml->authorUrl;
//		$test->author      = (string) $xml->author;
//		$test->authoremail = (string) $xml->authorEmail;
//		$test->description = (string) $xml->description;
//		$test->packager    = (string) $xml->packager;
//		$test->packagerurl = (string) $xml->packagerurl;
//		$test->scriptfile  = (string) $xml->scriptfile;
//		$test->version     = (string) $xml->version;
//
////		if (isset($xml->files->file) && \count($xml->files->file)) {
////			foreach ($xml->files->file as $file) {}
////		}
//
////		// Handle cases where package contains folders
////		if (isset($xml->files->folder) && \count($xml->files->folder))
////		{
////			foreach ($xml->files->folder as $folder) {}
////		}
//	}
//
//	/**
//	 * Apply manifest data from a \SimpleXMLElement to the object.
//	 *
//	 * @param   \SimpleXMLElement  $xml  Data to load
//	 *
//	 * @return  void
//	 *
//	 * @since   3.1
//	 */
//	protected function loadManifestFromData2(\SimpleXMLElement $xml)
//	{
//		$test               = new stdClass();
//		$test->name         = (string) $xml->name;
//		$test->libraryname  = (string) $xml->libraryname;
//		$test->version      = (string) $xml->version;
//		$test->description  = (string) $xml->description;
//		$test->creationdate = (string) $xml->creationDate;
//		$test->author       = (string) $xml->author;
//		$test->authoremail  = (string) $xml->authorEmail;
//		$test->authorurl    = (string) $xml->authorUrl;
//		$test->packager     = (string) $xml->packager;
//		$test->packagerurl  = (string) $xml->packagerurl;
//		$test->update       = (string) $xml->update;
//
//		if (isset($xml->files) && isset($xml->files->file) && \count($xml->files->file))
//		{
//			foreach ($xml->files->file as $file)
//			{
//				$test->filelist[] = (string) $file;
//			}
//		}
//	}


    public function __toTextItem($name = '')
    {
//        $value = $this->getByXml($name, '') ;
//        if ($value == null) {
//            $value = "%null%"
//        }

        $value = $this->getByXml($name, '')  ?? "%null%";
        return $name . '="' . $value . '"';
    }

    public function __toString()
    {
        $OutTxt = 'manifestXml' . "\r\n";
        $OutTxt .= implode("\r\n", $this->__toDataLines());

        return $OutTxt;
    }
        /**
     *
     * @return array
     *
     * @since version
     */
    public function __toDataLines()
    {
        $lines = [];

        $lines[] = '--- manifest file ---------------------------';

        if ($this->isXmlLoaded) {
//        $lines[] = $this->__toTextItem('name');
//
//        //$test->name         = (string) $xml->name;
//
//        $lines[] = $this->__toTextItem('author');
//        $lines[] = $this->__toTextItem('authorEmail');
//        $lines[] = $this->__toTextItem('authorUrl');
//        $lines[] = $this->__toTextItem('creationDate');
//        $lines[] = $this->__toTextItem('description');
//        $lines[] = $this->__toTextItem('libraryname');
//        $lines[] = $this->__toTextItem('packagename');
//        $lines[] = $this->__toTextItem('packager');
//        $lines[] = $this->__toTextItem('packagerurl');
//        $lines[] = $this->__toTextItem('scriptfile');
//        $lines[] = $this->__toTextItem('update');
            $lines[] = $this->__toTextItem('version');
//
//        $lines[] = '';
//        if ($this->isInstalled) {
//            $lines[] = '( Manifest is within joomla ) ';
//        } else {
//            $lines[] = '( Manifest on development path ) ';
//        }
//
//        $lines[] = 'default (site) path: ' . $this->prjDefaultPathRelative;
//        $lines[] = 'admin path: ' . $this->prjAdminPathRelative;
//
//        $lines[] = 'defaultLangPathRelative (site): ' . $this->defaultLangPathRelative;
//        $lines[] = 'adminLangPathRelative: ' . $this->adminLangPathRelative;
//
//        $lines[] = '';
        }

        return $lines;
    }

} // class




