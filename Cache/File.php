<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cache_File extends \app\Instantiatable
	implements \ibidem\types\Cache
{
	/**
	 * @param string key
	 * @param mixed default
	 * @return mixed
	 */
	public function fetch($key, $default = null)
	{
		$key = \str_replace('\\', '/', $key);
		$cache = \app\CFS::config('ibidem\cache');
		$cache_file = $cache['File']['cache.dir'].$key;
		
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
	 * @param string key
	 * @return \ibidem\cache\Cache_File $this
	 */
	public function delete($key)
	{
		$key = \str_replace('\\', '/', $key);
		$cache = \app\CFS::config('ibidem\cache');
		$cache_file = $cache['File']['cache.dir'].$key;
		
		if (\file_exists($cache_file))
		{
			\unlink($cache_file);
		}
		
		return $this;
	}
	
	/**
	 * @param string key
	 * @param mixed data
	 * @param integer lifetime (seconds)
	 * @return \ibidem\cache\Cache_File $this
	 */
	public function store($key, $data, $lifetime_seconds = null)
	{
		$key = \str_replace('\\', '/', $key);
		$cache = \app\CFS::config('ibidem\cache');
		
		if ($lifetime_seconds === null)
		{
			$lifetime_seconds = $cache['File']['lifetime.default'];
		}
		
		\preg_match('#^(.*/)?([^/]+)$#', $key, $matches);
		$dir = $cache['File']['cache.dir'].$matches[1];
		$file = $matches[2];
		\file_exists($dir) or \mkdir($dir, 0777, true);
		
		// store the data
		\file_put_contents
			(
				$dir.$file, 
				\serialize
					(
						array
						(
							'expires' => \time() + $lifetime_seconds,
							'data' => $data
						)
					)
			);
	}

} # class
