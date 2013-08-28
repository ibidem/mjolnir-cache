<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\Stash;

class StashTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\Stash'));
	}

	// @todo tests for \mjolnir\cache\Stash

} # test
