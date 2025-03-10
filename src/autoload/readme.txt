

-------------------------

https://opensource.com/article/23/4/autoloading-namespaces-php

https://www.daggerhartlab.com/autoloading-namespaces-in-php/


-------------------------------


https://stackoverflow.com/questions/7651509/what-is-autoloading-how-do-you-use-spl-autoload-autoload-and-spl-autoload-re


google spl_autoload_register namespaces 

https://stackoverflow.com/questions/48723309/default-spl-autoload-register-namespace-behavior-with-index-php-outside-root

https://stackoverflow.com/questions/22494980/php-namespacing-and-spl-autoload-register

https://stackoverflow.com/questions/7651509/what-is-autoloading-how-do-you-use-spl-autoload-autoload-and-spl-autoload-re

https://stackoverflow.com/questions/67901706/using-namespaces-with-spl-autoload-register

spl_autoload_register('MyAutoloader::ClassLoader');
spl_autoload_register('MyAutoloader::LibraryLoader');
spl_autoload_register('MyAutoloader::HelperLoader');
spl_autoload_register('MyAutoloader::DatabaseLoader');

class MyAutoloader
{
    public static function ClassLoader($className)
    {
         //your loading logic here
    }


    public static function LibraryLoader($className)
    {
         //your loading logic here
    }
	
	
	


