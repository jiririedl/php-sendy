<?php
namespace SendyPHP;

/**
 * Autoload for SendyPHP client
 *
 * Loads projects classes. Other class names are ignored. You can simply use this function with spl_autoload_register.
 *
 * @example \spl_autoload_register('\\SendyPHP\\autoload'); This code registers spl_autoload function and all classes will be included automatically
 * @param string $className
 * @return bool
 */
function autoload($className)
{
    if(substr($className,0,strlen(__NAMESPACE__)) == __NAMESPACE__) // only SendyPHP classes are handled
    {
        $className = substr($className,strlen(__NAMESPACE__)+1); // cuts SendyPHP namespace
        $className = ltrim($className, '\\');
        $fileName  = __DIR__.DIRECTORY_SEPARATOR;

        if ($lastNsPos = strripos($className, '\\'))
        {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= $className.'.php';

        require_once($fileName);
    }
    else
        return false;
}