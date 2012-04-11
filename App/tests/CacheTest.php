<?php namespace ibidem\cache;

require_once 'Cache/template.Cache'.EXT;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class CacheTest extends CacheTester
{
	function setUp()
	{
		static::$instance = \app\Cache_APC::instance();
	}

} # class
