<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Memcache extends \app\Instantiatable implements \mjolnir\types\Cache
{
	use \app\Trait_Cache;

	/**
	 * @var \Memcache
	 */
	private $memcache;

	/**
	 * @return static
	 */
	static function instance()
	{
		if ( ! \app\CFS::config('mjolnir/base')['caching'])
		{
			return \app\Stash_Null::instance();
		}

		if ( ! \class_exists('Memcache'))
		{
			throw new \app\Exception('memcache extention not loaded.');
		}

		$instance = parent::instance();

		$memcache_config = \app\CFS::config('mjolnir/cache')['Memcache'];

		$instance->memcache = new \Memcache;
		$instance->memcache->connect($memcache_config['host'], $memcache_config['port']);

		return $instance;
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	function set($key, $data, $expires = null)
	{
		$key = static::safe_key($key);
		$config = \app\CFS::config('mjolnir/cache')['Memcache'];
		if ($expires === null)
		{
			$expires = $config['lifetime.default'];
		}

		$this->memcache->set($key, \serialize($data), MEMCACHE_COMPRESSED, $expires);
	}

	/**
	 * Retrieves data from $key
	 *
	 * @return mixed data or default
	 */
	function get($key, $default = null)
	{
		$key = static::safe_key($key);
		$value = $this->memcache->get($key);

		if ($value !== false)
		{
			return \unserialize($value);
		}
		else # failed to get key
		{
			return $default;
		}
	}

	/**
	 * Deletes $key
	 */
	function delete($key)
	{
		$key = static::safe_key($key);
		$this->memcache->delete($key);
	}

	/**
	 * Wipe cache.
	 */
	function flush()
	{
		$this->memcache->flush();
	}

} # class
