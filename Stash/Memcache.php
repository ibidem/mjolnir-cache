<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Memcache extends \app\Instantiatable implements \mjolnir\types\Cache
{
	use \app\Trait_Cache;

	/**
	 * @var \Memcache
	 */
	protected $memcache;

	/**
	 * @return static
	 */
	static function instance($contextual = true)
	{
		if (($contextual && ! \app\CFS::config('mjolnir/base')['caching']) || ! \app\CFS::config('mjolnir/cache')['Memcache']['enabled'])
		{
			return \app\Stash_Null::instance();
		}

		if ( ! \class_exists('Memcache'))
		{
			return \app\Stash_Null::instance();
		}

		$instance = parent::instance();

		$memcache_config = \app\CFS::config('mjolnir/cache')['Memcache'];

		$instance->memcache = new \Memcache;
		$instance->memcache->connect($memcache_config['host'], $memcache_config['port']);

		return $instance;
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 *
	 * @return static $this
	 */
	function set($key, $data, $expires = null)
	{
		$key = $this->generate_key($key);
		$config = \app\CFS::config('mjolnir/cache')['Memcache'];
		if ($expires === null)
		{
			$expires = $config['lifetime.default'];
		}

		$this->memcache->set($key, \serialize($data), MEMCACHE_COMPRESSED, $expires);

		return $this;
	}

	/**
	 * Retrieves data from $key
	 *
	 * @return mixed data or default
	 */
	function get($key, $default = null)
	{
		$key = $this->generate_key($key);
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
	 *
	 * @return static $this
	 */
	function delete($key)
	{
		$key = $this->generate_key($key);
		$this->memcache->delete($key);

		return $this;
	}

	/**
	 * Wipe cache.
	 *
	 * @return static $this
	 */
	function flush()
	{
		$this->memcache->flush();

		return $this;
	}

} # class
