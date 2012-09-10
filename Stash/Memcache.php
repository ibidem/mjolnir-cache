<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Memcache extends \app\Stash_Base
	implements 
		\mjolnir\types\Stash, 
		\mjolnir\types\TaggedStash
{
	use \app\Trait_TaggedStash;
	
	/**
	 * @var \app\Stash_Memecached
	 */
	private static $instance;
	
	/**
	 * @var \Memcache
	 */
	private $memcache;
	
	/**
	 * @return \app\Stash_Memcached
	 * @throws \app\Exception_NotApplicable
	 */
	static function instance()
	{
		if (self::$instance)
		{
			return self::$instance;
		}
		else # uninitialized
		{
			if ( ! \class_exists('Memcache'))
			{
				throw new \app\Exception_NotApplicable('memcache extention not loaded.');
			}
			
			self::$instance = parent::instance();
			
			$memcache_config = \app\CFS::config('mjolnir/cache')['Memcache'];
			
			self::$instance->memcache = new \Memcache;
			self::$instance->memcache->connect($memcache_config['host'], $memcache_config['port']);
						
			return self::$instance;
		}
	}
	
	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		$key = static::safe_key($key);
		$config = \app\CFS::config('mjolnir/cache')['Memcache'];
		if ($expires === null)
		{
			$expires = $config['lifetime.default'];
		}
		
		static::instance()->memcache->set($key, \serialize($data), MEMCACHE_COMPRESSED, $expires);
	}

	/**
	 * Retrieves data from $key
	 * 
	 * @return mixed data or default
	 */
	static function get($key, $default = null)
	{
		if ( ! \app\CFS::config('mjolnir/base')['caching']) 
		{
			return $default;
		}
		
		$key = static::safe_key($key);
		$value = static::instance()->memcache->get($key);
		
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
	static function delete($key)
	{
		$key = static::safe_key($key);
		static::instance()->memcache->delete($key);
	}

} # class
