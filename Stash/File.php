<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_File extends \app\Instantiatable implements \mjolnir\types\Cache
{
	use \app\Trait_Cache;

	/**
	 * @var string
	 */
	const EXT = '.cache'; # extention for cache files

	/**
	 * @return static
	 */
	static function instance($contextual = true)
	{
		if ($contextual && ! \app\CFS::config('mjolnir/base')['caching'])
		{
			return \app\Stash_Null::instance();
		}

		$cache_dir = \app\CFS::config('mjolnir/cache')['File']['cache.dir'];

		if (\file_exists($cache_dir))
		{
			\app\Filesystem::makedir($cache_dir);
		}

		return parent::instance();
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	function set($key, $data, $expires = null)
	{
		$key = static::safe_key($key);
		$cache = \app\CFS::config('mjolnir/cache')['File'];

		if ($expires === null)
		{
			$expires = $cache['lifetime.default'];
		}

		$dir = $cache['cache.dir'];
		$file = $key;

		try
		{
			\app\Filesystem::puts
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
	function get($key, $default = null)
	{
		$key = static::safe_key($key);
		$cache_file = \app\CFS::config('mjolnir/cache')['File']['cache.dir'].$key.static::EXT;

		if (\file_exists($cache_file))
		{
			$cache_info = \unserialize(\file_get_contents($cache_file));
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
		$cache = \app\CFS::config('mjolnir/cache')['File'];
		$cache_file = $cache['cache.dir'].static::safe_key($key).static::EXT;

		if (\file_exists($cache_file))
		{
			if ($cache['trucking'])
			{
				@\unlink($cache_file);

				if (\file_exists($cache_file))
				{
					// try adjusting permissions
					\chmod($cache_file, 0700);
					@\unlink($cache_file);

					if (\file_exists($cache_file))
					{
						\mjolnir\log('Error', 'Failed to delete '.$cache_file);
					}
					else # we failed initially, but managed to eventually
					{
						\mjolnir\log('Error', 'Bad permissions on cache file ['.$cache_file.']; has been mitigated via permission auto-adjust. Potential configuration mistake.');
					}
				}
			}
			else # no trucking; accept system failures
			{
				@\unlink($cache_file);

				if (\file_exists($cache_file))
				{
					// try adjusting permissions
					\chmod($cache_file, 0700);
					\unlink($cache_file); # intentionally ommited @
				}
			}
		}
	}

	/**
	 * Wipes cache.
	 */
	function flush()
	{
		$cachedir = \app\CFS::config('mjolnir/cache')['File']['cache.dir'];

		if (\file_exists($cachedir))
		{
			\app\Filesystem::purge($cachedir);
		}
	}

} # class
