<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_APC extends \app\Stash_Base implements \mjolnir\types\Cache
{
	use \app\Trait_TaggedStash;

	/**
	 * @return \mjolnir\types\Stash
	 */
	static function instance()
	{
		if ( ! \app\CFS::config('mjolnir/base')['caching'])
		{
			return \app\Stash_Null::instance();
		}

		if ( ! \extension_loaded('apc'))
		{
			throw new \app\Exception('APC extention not loaded.');
		}

		return parent::instance();	
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	function set($key, $data, $expires = null)
	{
		if ($expires === null)
		{
			$expires = \app\CFS::config('mjolnir/cache')['APC']['lifetime.default'];
		}

		$key = static::safe_key($key);

		if ( ! \apc_store($key, $data, $expires))
		{
			$error_diagnostic 
				= 'APC store failed for key "'.$key.'" and value \''.\serialize($data).'\'. '
				.'This can be caused by repeated stores with "apc.slam_defense = 1" in your configuration.'
				;
			
			// failed to store data
			\mjolnir\log('Bug', $error_diagnostic, 'bugs'.DIRECTORY_SEPARATOR);
		}
	}

	/**
	 * Retrieves data from $key
	 *
	 * @return mixed data or default
	 */
	function get($key, $default = null)
	{
		$key = static::safe_key($key);

		$data = \apc_fetch($key, $success);

		return $success ? \unserialize($data) : $default;
	}

	/**
	 * Deletes $key
	 */
	function delete($key)
	{
		$key = static::safe_key($key);

		\apc_delete($key);
	}
	
	/**
	 * Wipes the cache.
	 */
	function flush()
	{
		\apc_clear_cache();
		\apc_clear_cache('user');
		\apc_clear_cache('opcode');
	}

} # class
