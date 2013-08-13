<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\cache'


/**
 * @method \app\Stash_APC set($key, $data, $expires = null)
 * @method \app\Stash_APC delete($key)
 * @method \app\Stash_APC flush()
 * @method \app\Stash_APC store($key, $data, array $tags = array (
), $expires = null)
 * @method \app\Stash_APC purge(array $tags)
 */
class Stash_APC extends \mjolnir\cache\Stash_APC
{
	/** @return \app\Stash_APC */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

/**
 * @method \app\Stash_File set($key, $data, $expires = null)
 * @method \app\Stash_File delete($key)
 * @method \app\Stash_File flush()
 * @method \app\Stash_File store($key, $data, array $tags = array (
), $expires = null)
 * @method \app\Stash_File purge(array $tags)
 */
class Stash_File extends \mjolnir\cache\Stash_File
{
	/** @return \app\Stash_File */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

/**
 * @method \app\Stash_Memcache set($key, $data, $expires = null)
 * @method \app\Stash_Memcache delete($key)
 * @method \app\Stash_Memcache flush()
 * @method \app\Stash_Memcache store($key, $data, array $tags = array (
), $expires = null)
 * @method \app\Stash_Memcache purge(array $tags)
 */
class Stash_Memcache extends \mjolnir\cache\Stash_Memcache
{
	/** @return \app\Stash_Memcache */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

/**
 * @method \app\Stash_Memcached set($key, $data, $expires = null)
 * @method \app\Stash_Memcached delete($key)
 * @method \app\Stash_Memcached flush()
 * @method \app\Stash_Memcached store($key, $data, array $tags = array (
), $expires = null)
 * @method \app\Stash_Memcached purge(array $tags)
 */
class Stash_Memcached extends \mjolnir\cache\Stash_Memcached
{
	/** @return \app\Stash_Memcached */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

/**
 * @method \app\Stash_Null set($key, $data, $expires = null)
 * @method \app\Stash_Null delete($key)
 * @method \app\Stash_Null flush()
 * @method \app\Stash_Null store($key, $data, array $tags = array (
), $expires = null)
 * @method \app\Stash_Null purge(array $tags)
 */
class Stash_Null extends \mjolnir\cache\Stash_Null
{
	/** @return \app\Stash_Null */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Stash_TempMemory set($key, $data, $expires = null)
 * @method \app\Stash_TempMemory delete($key)
 * @method \app\Stash_TempMemory flush()
 * @method \app\Stash_TempMemory store($key, $data, array $tags = array (
), $expires = null)
 * @method \app\Stash_TempMemory purge(array $tags)
 */
class Stash_TempMemory extends \mjolnir\cache\Stash_TempMemory
{
	/** @return \app\Stash_TempMemory */
	static function instance($contextual = true) { return parent::instance($contextual); }
}

class Stash extends \mjolnir\cache\Stash
{
	/** @return \app\Cache */
	static function instance() { return parent::instance(); }
}

class ViewStash extends \mjolnir\cache\ViewStash
{
}
