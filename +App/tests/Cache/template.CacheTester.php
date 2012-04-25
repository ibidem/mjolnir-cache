<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class CacheTester extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var \ibidem\types\Cache
	 */
	protected static $instance;
	
	function setUp()
	{
		static::$instance = null;
	}
	
	/**
	 * @test
	 */
	function fetch()
	{
		$cache = static::$instance;
		$cache->store('unittest/fetch', 'test:ok', 10000);
		$this->assertEquals('test:ok', $cache->fetch('unittest/fetch', null));
	}
	
	/**
	 * @test
	 */
	function delete()
	{
		$cache = static::$instance;
		$cache->store('unittest/delete', 'test:ok', 10000);
		$cache->delete('unittest/delete');
		$this->assertEquals('test:failed', $cache->fetch('unittest/delete', 'test:failed'));
	}
	
	/**
	 * @test
	 */
	function store()
	{
		// @see fetch
	}

} # class

