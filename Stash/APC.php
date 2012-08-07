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
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		\apc_store(static::safe_key($key), \serialize($data), $expires);
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
		\apc_delete(static::safe_key($key));
	}

} # class
