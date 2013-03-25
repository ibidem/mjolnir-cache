<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\cache'


class Stash_APC extends \mjolnir\cache\Stash_APC
{
	/** @return \app\Stash_APC */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

class Stash_File extends \mjolnir\cache\Stash_File
{
	/** @return \app\Stash_File */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

class Stash_Memcached extends \mjolnir\cache\Stash_Memcached
{
	/** @return \app\Stash_Memcached */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

class Stash_Memcache extends \mjolnir\cache\Stash_Memcache
{
	/** @return \app\Stash_Memcache */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

class Stash_Null extends \mjolnir\cache\Stash_Null
{
	/** @return \app\Stash_Null */
	static function instance() { return parent::instance(); }
}

class Stash extends \mjolnir\cache\Stash
{
}

class ViewStash extends \mjolnir\cache\ViewStash
{
}
