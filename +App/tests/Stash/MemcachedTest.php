<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\Stash_Memcached;

class Stash_MemcachedTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\Stash_Memcached'));
	}

	// @todo tests for \mjolnir\cache\Stash_Memcached

} # test
