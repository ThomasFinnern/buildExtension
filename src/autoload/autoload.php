<?php
/**
 * @package         buildExtension
 * @subpackage      buildExtension
 * @author          Thomas Finnern <InsideTheMachine.de>
 * @copyright  (c)  2025-2025 Finnern
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

// namespace Finnern\BuildExtension\autoload;

/**
 * An example of a project-specific implementation.
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
function build_autoloader(string $class) {

    $vendorProject = 'Finnern\BuildExtension';

    // Remove vendor/project Finnern\BuildExtension\
    $classPath = substr($class, strlen($vendorProject));


    // replace namespace separators with directory separators in the relative 
    // class name, append with .php
    $class_path = str_replace('\\', '/', $classPath);
    
    $file =  __DIR__ . '/../..' . $class_path . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
}

spl_autoload_register( 'build_autoloader' );

