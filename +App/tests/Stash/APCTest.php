<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\Stash_APC;

class Stash_APCTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\Stash_APC'));
	}

	// @todo tests for \mjolnir\cache\Stash_APC

} # test
