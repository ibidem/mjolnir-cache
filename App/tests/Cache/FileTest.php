<?php namespace ibidem\cache;

require_once 'template.Cache'.EXT;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cache_FileTest extends CacheTester
{
	function setUp()
	{
		static::$instance = \app\Cache_File::instance();
	}

} # class
