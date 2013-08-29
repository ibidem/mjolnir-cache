<?php namespace mjolnir\cache\tests;

use \mjolnir\cache\Stash_File;

class Stash_FileTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\cache\Stash_File'));
	}

	// @todo tests for \mjolnir\cache\Stash_File

} # test
