<?php namespace ibidem\cache;

require_once 'template.CacheTester'.EXT;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cache_MemcachedTest extends CacheTester
{	
	function setUp()
	{
		if ( ! \extension_loaded('memcached'))
		{
			static::$instance = \app\Cache_File::instance();
		}
		else
		{
			static::$instance = \app\Cache_Memcached::instance();
		}
	}

} # class
