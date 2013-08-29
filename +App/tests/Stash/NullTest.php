<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\Stash_Null;

class Stash_NullTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\Stash_Null'));
	}

	// @todo tests for \mjolnir\cache\Stash_Null

} # test
