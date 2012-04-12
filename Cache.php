<?php namespace ibidem\cache;

/**
 * This is the default cache. It will initialize to whatever is set as the 
 * default caching mechanism.
 * 
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cache extends \app\Instantiatable
{
	/**
	 * @return \ibidem\types\Cache 
	 */
	public static function instance()
	{
		$cache_config = \app\CFS::config('ibidem/cache');
		$default_cache = '\app\Cache_'.$cache_config['default.cache'];
		
		return $default_cache::instance();
	}

} # class
