<?php

/**
******************************************************************************************
**   @package    com_joomgallery                                                        **
**   @author     JoomGallery::ProjectTeam <team@joomgalleryfriends.net>                 **
**   @copyright  2003 - 2025  JoomGallery::ProjectTeam                                  **
**   @license    GNU General Public License version 3 or later                          **
*****************************************************************************************/
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScript;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\Filesystem\Folder;

//use Joomla\CMS\File;
//use Joomla\CMS\Folder;

/**
 * Script (install file of Rsgallery2 Component)
 *
 * @since 5.0.0
 *
 */
class Com_Rsgallery2InstallerScript extends InstallerScript
{
    protected $newRelease;
    protected $oldRelease;


	/**
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function register(Container $container)
	{
		$container->registerServiceProvider(new MVCFactory('\\Joomgallery\\Component\\Joomgallery'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomgallery\\Component\\Joomgallery'));
		$container->registerServiceProvider(new RouterFactory('\\Joomgallery\\Component\\Joomgallery'));

    // Create the component class
		$container->set(
			ComponentInterface::class,
			function (Container $container)
			{
				$component = new JoomgalleryComponent($container->get(ComponentDispatcherFactoryInterface::class));

				$component->setRegistry($container->get(Registry::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);

		return;
	}
	
    /**
     *
     * @param $langPath
     *
     * @since version
     */
    protected function removeLangFilesInSubPaths(string $langPath): bool
    {
        $isOneFileDeleted = false;

        try {
            Log::add(Text::_('start: removeLangFilesInSubPaths: ') . $langPath, Log::INFO, 'rsg2');

            //--- All matching files in actual folder -------------------

            $files = array_diff(array_filter(glob($langPath . '/*'), 'is_file'), ['.', '..']);

            foreach ($files as $fileName) {
                // A matching lang name ...
                if (str_contains($fileName, 'com_rsgallery2')) {
                    // ... will be deleted
                    if (file_exists($fileName)) {
                        Log::add(Text::_('unlink: ') . $fileName, Log::INFO, 'rsg2');

                        unlink($fileName);
                        $isOneFileDeleted = true;
                    }
                }
            }
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception in removeLangFilesInSubPaths (1): ') . $e->getMessage()
                . ' \n' . $langPath,
                Log::INFO,
                'rsg2',
            );
        }

        try {
            #--- Search in each sub folder -------------------------------------

            // don't search, there is no sub folder
            if (!$isOneFileDeleted) {
                // base folder may contain lang ID folders en-GB, de-DE

                $folders = array_diff(array_filter(glob($langPath . '/*'), 'is_dir'), ['.', '..']);

                foreach ($folders as $folderName) {
// 				echo ('folder name: ' . $folderName . '<br>');

                    // $subFolder = $langPath . "/" . $folderName;
                    //$isOneFileDeleted = removeLangFilesInSubPaths($subFolder);

                    $isOneFileDeleted = $this->removeLangFilesInSubPaths($folderName);
                }
            }
        } catch (RuntimeException $e) {
            Log::add(
                Text::_('Exception in removeLangFilesInSubPaths (2): ') . $e->getMessage()
                . ' \n' . $langPath,
                Log::INFO,
                'rsg2',
            );
        }

        return $isOneFileDeleted;
    }

    /**
     * Remove old component files of j3x start with clean directories
     *
     * @since version
     */
     protected function removeJ3xComponentFiles(): void
    {
        try {
            Log::add(Text::_('start: removeJ3xComponentFiles: '), Log::INFO, 'rsg2');

            //--- administrator\language path ---------------------------------

            $adminRSG2_Path = JPATH_ROOT . '/administrator/components/' . 'com_rsgallery2';

            Log::add(Text::_('upd (50.1) '), Log::INFO, 'rsg2');

            if (is_dir($adminRSG2_Path)) {
                Log::add(Text::_('upd (50.2) '), Log::INFO, 'rsg2');
                Log::add(Text::_('del Folder: ') . $adminRSG2_Path, Log::INFO, 'rsg2');

                $isOk = Folder::delete($adminRSG2_Path);

                if (!$isOk) {
                    Log::add(Text::_('upd (50.3) RSG2 admin not deleted'), Log::INFO, 'rsg2');
                }

                Log::add(Text::_('upd (50.4) '), Log::INFO, 'rsg2');
            }

            //--- site\language path ---------------------------------

            $componentRSG2_Path = JPATH_ROOT . '/components/' . 'com_rsgallery2';

            Log::add(Text::_('upd (50.11) '), Log::INFO, 'rsg2');

            if (is_dir($componentRSG2_Path)) {
                Log::add(Text::_('upd (50.12) '), Log::INFO, 'rsg2');
                Log::add(Text::_('del Folder: ') . $componentRSG2_Path, Log::INFO, 'rsg2');

                $isOk = Folder::delete($componentRSG2_Path);

                if (!$isOk) {
                    Log::add(Text::_('upd (50.12) RSG2 component not deleted'), Log::INFO, 'rsg2');
                }

                Log::add(Text::_('upd (50.13) '), Log::INFO, 'rsg2');
            }

        } catch (RuntimeException $e) {
            Log::add(
                Text::_('\n>> Exception: removeJ3xComponentFiles: ') . $e->getMessage(),
                Log::INFO,
                'rsg2',
            );
        }

        return;
    }

} // class
