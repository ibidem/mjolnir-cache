<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\ViewStash;

class ViewStashTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\ViewStash'));
	}

	// @todo tests for \mjolnir\cache\ViewStash

} # test
