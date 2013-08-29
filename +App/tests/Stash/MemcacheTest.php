<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\Stash_Memcache;

class Stash_MemcacheTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\Stash_Memcache'));
	}

	// @todo tests for \mjolnir\cache\Stash_Memcache

} # test
