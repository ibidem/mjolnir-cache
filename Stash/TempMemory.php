<?php namespace mjolnir\cache;

/**
 * This stash class stores the cache value for the duration of the request and
 * drops it imediatly after the request is over.
 *
 * @package    mjolnir
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_TempMemory extends \app\Instantiatable implements \mjolnir\types\Cache
{
	use \app\Trait_Cache;

	/**
	 * @var array
	 */
	protected static $memory = [];

	/**
	 * @return static
	 */
	static function instance($contextual = true)
	{
		static $instance = null;

		if (($contextual && ! \app\CFS::config('mjolnir/base')['caching']) || ! \app\CFS::config('mjolnir/cache')['TempMemory']['enabled'])
		{
			return \app\Stash_Null::instance();
		}

		if ($instance === null)
		{
			$instance = parent::instance();
		}

		return $instance;
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	function set($key, $data, $expires = null)
	{
		$key = static::safe_key($key);
		$cache = \app\CFS::config('mjolnir/cache')['TempMemory'];

		if ($expires === null)
		{
			$expires = $cache['lifetime.default'];
		}

		static::$memory[$key] = array
			(
				'expires' => \time() + $expires,
				'data' => $data
			);
	}

	/**
	 * Retrieves data from $key
	 *
	 * @return mixed data or default
	 */
	function get($key, $default = null)
	{
		$key = static::safe_key($key);

		if (isset(static::$memory[$key]))
		{
			$cache_info = static::$memory[$key];
			if ($cache_info['expires'] > \time())
			{
				return $cache_info['data'];
			}
			else # cache expired
			{
				$this->delete($key);
				return $default;
			}
		}
		else # no cache file
		{
			return $default;
		}
	}

	/**
	 * Deletes $key
	 */
	function delete($key)
	{
		unset(static::$memory[$key]);
	}

	/**
	 * Wipes cache.
	 */
	function flush()
	{
		static::$memory = [];
	}

} # class
