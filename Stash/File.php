<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_File extends \app\Stash_Base
	implements 
		\ibidem\types\Stash, 
		\ibidem\types\TaggedStash
{
	use \app\Trait_TaggedStash;
	
	const EXT = '.cache'; # extention for cache files
	
	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{		
		$key = static::safe_key($key);
		$cache = \app\CFS::config('ibidem\cache');
		
		if ($expires === null)
		{
			$expires = $cache['File']['lifetime.default'];
		}
		
		if ($key == 'Model_User') throw new \Exception;
		
		$dir = $cache['File']['cache.dir'];
		$file = $key;
		\file_exists($dir) or \mkdir($dir, 0777, true);
		
		// store the data
		\file_put_contents
			(
				$dir.$file.static::EXT, 
				\serialize
					(
						array
						(
							'expires' => \time() + $expires,
							'data' => $data
						)
					)
			);
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
		$cache = \app\CFS::config('ibidem\cache');
		$cache_file = $cache['File']['cache.dir'].$key.static::EXT;
		
		if (\file_exists($cache_file))
		{
			$cache_info = \unserialize(\file_get_contents($cache_file));
			if ($cache_info['expires'] > \time())
			{
				return $cache_info['data'];
			}
			else # cache expired
			{
				static::delete($key);
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
	static function delete($key)
	{
		$key = static::safe_key($key);
		$cache = \app\CFS::config('ibidem\cache');
		$cache_file = $cache['File']['cache.dir'].$key.static::EXT;
		
		if (\file_exists($cache_file))
		{
			\unlink($cache_file);
		}
	}
	
} # class
