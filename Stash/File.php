<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_File extends \app\Stash_Base
	implements
		\mjolnir\types\Stash,
		\mjolnir\types\TaggedStash
{
	use \app\Trait_TaggedStash;

	const EXT = '.cache'; # extention for cache files

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		$key = static::safe_key($key);
		$cache = \app\CFS::config('mjolnir/cache')['File'];

		if ($expires === null)
		{
			$expires = $cache['lifetime.default'];
		}

		$dir = $cache['cache.dir'];
		$file = $key;
		\file_exists($dir) or \mkdir($dir, 0777, true);

		try
		{
			// store the data
			\file_put_contents
				(
					$dir.$file.static::EXT,
					\serialize
						(
							[
								'expires' => \time() + $expires,
								'data' => $data
							]
						)
				);
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
	static function get($key, $default = null)
	{
		if ( ! \app\CFS::config('mjolnir/base')['caching'])
		{
			return $default;
		}

		$key = static::safe_key($key);
		$cache = \app\CFS::config('mjolnir/cache')['File'];
		$cache_file = $cache['cache.dir'].$key.static::EXT;

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
		$cache = \app\CFS::config('mjolnir/cache')['File'];
		$cache_file = $cache['cache.dir'].$key.static::EXT;

		if (\file_exists($cache_file))
		{
			@\unlink($cache_file);
		}
	}

} # class
