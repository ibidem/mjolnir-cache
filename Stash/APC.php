<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_APC extends \app\Stash_Base
	implements 
		\ibidem\types\Stash, 
		\ibidem\types\TaggedStash
{
	use \app\Trait_TaggedStash;

	/**
	 * @var array of scheduled cache entries
	 */
	private static $caches = [];
	
	/**
	 * @var \app\Stash_APC
	 */
	protected static $instance;
	
	/**
	 * @return \app\Stash_Memcached
	 * @throws \app\Exception_NotApplicable
	 */
	static function instance()
	{
		if (static::$instance)
		{
			return static::$instance;
		}
		else # uninitialized
		{
			if ( ! \extension_loaded('apc'))
			{
				throw new \app\Exception_NotApplicable('APC extention not loaded.');
			}
			
			static::$instance = parent::instance();

			return static::$instance;
		}
	}
	
	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		$cache = \app\CFS::config('ibidem/cache')['APC'];
		if ($expires === null)
		{
			$expires = $cache['lifetime.default'];
		}

		$key = static::safe_key($key);
		
		if ( ! \apc_store($key, $data, $expires))
		{
			// failed to store data
			\app\Log::message
				(
					'Bug', 
					'APC store failed for key "'.$key.'" and value \''.\serialize($data).'\'. '
					.'This can be caused by repeated stores with "apc.slam_defense = 1" in your configuration.', 
					'bugs'.DIRECTORY_SEPARATOR
				);
		}
	}

	/**
	 * Retrieves data from $key
	 * 
	 * @return mixed data or default
	 */
	static function get($key, $default = null)
	{
		if ( ! \app\CFS::config('ibidem/base')['caching']) 
		{
			return $default;
		}
		
		$key = static::safe_key($key);
		
		$data = \apc_fetch($key, $success);
		
		return $success ? \unserialize($data) : $default;
	}

	/**
	 * Deletes $key
	 */
	static function delete($key)
	{
		$key = static::safe_key($key);
		
		\apc_delete($key);
	}

} # class
