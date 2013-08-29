<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\Stash_TempMemory;

class Stash_TempMemoryTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\Stash_TempMemory'));
	}

	// @todo tests for \mjolnir\cache\Stash_TempMemory

} # test
