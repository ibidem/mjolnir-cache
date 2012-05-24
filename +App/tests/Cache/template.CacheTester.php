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
		$tag = 'test';
		$cache = static::$instance;
		$cache->store($tag, 'unittest/fetch', 'test:ok', 10000);
		$this->assertEquals('test:ok', $cache->fetch($tag, 'unittest/fetch', null));
	}
	
	/**
	 * @test
	 */
	function delete()
	{
		$tag = 'test';
		$cache = static::$instance;
		$cache->store($tag, 'unittest/delete', 'test:ok', 10000);
		$cache->delete($tag, 'unittest/delete');
		$this->assertEquals('test:failed', $cache->fetch($tag, 'unittest/delete', 'test:failed'));
	}
	
	/**
	 * @test
	 */
	function store()
	{
		// @see fetch
	}

} # class

