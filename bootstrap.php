<?php
/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * !!!!!!!!!!!!!! This is old-way of including library, use composer instead for all your libraries !!!!!!!!!!!!!!
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 *
 * With composer you don't need to handle each library autoload separately, composer make it for you and
 * as free bonus you get actual versions of all libraries every times - good deal, isn't?
 *
 * @example add "jiririedl/php-sendy" : ">=1.0.0" to your composer.json for obtaining last stable release
 * @link https://getcomposer.org/doc/00-intro.md#declaring-dependencies
 * @link https://packagist.org/packages/jiririedl/php-sendy
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SendyPHP
 */

require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'autoload.php');

\spl_autoload_register('\\SendyPHP\\autoload');