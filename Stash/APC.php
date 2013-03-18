<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_APC extends \app\Instantiatable implements \mjolnir\types\Cache
{
	use \app\Trait_Cache;

	/**
	 * @return static
	 */
	static function instance($contextual = true)
	{
		if (($contextual && ! \app\CFS::config('mjolnir/base')['caching']) || ! \app\CFS::config('mjolnir/cache')['APC']['enabled'])
		{
			return \app\Stash_Null::instance();
		}

		if ( ! \function_exists('apc_store'))
		{
			return \app\Stash_Null::instance();
		}

		return parent::instance();
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	function set($key, $data, $expires = null)
	{
		$cache = \app\CFS::config('mjolnir/cache')['APC'];

		if ($expires === null)
		{
			$expires = $cache['lifetime.default'];
		}

		try
		{
			\apc_store($key, $data, $expires);
		}
		catch (\Exception $e)
		{
			\mjolnir\log_exception($e);
		}
	}

	/**
	 * Retrieves data from $key
	 *
	 * @return mixed data or default
	 */
	function get($key, $default = null)
	{
		$success = false;
		$variable = \apc_fetch($key, $success);
		return $success ? $variable : $default;
	}

	/**
	 * Deletes a cache key
	 */
	function delete($key)
	{
		\apc_delete($key);
	}

	/**
	 * Wipes cache.
	 */
	function flush()
	{
		\apc_clear_cache();
	}

} # class
