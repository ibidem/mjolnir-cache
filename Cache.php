<?php namespace ibidem\cache;

/**
 * This is the default cache. It will initialize to whatever is set as the 
 * default caching mechanism.
 * 
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cache extends \app\Instantiatable
	implements \ibidem\types\Cache
{
	/**
	 * @var \ibidem\types\Cache
	 */
	private $default_cache;
	
	/**
	 * @return \ibidem\cache\Cache 
	 */
	public static function instance()
	{
		$cache_config = \app\CFS::config('cache');
		$this->default_cache = '\app\Cache_'.$cache_config['default.cache'];
		$this->default_cache = $default_cache::instance();
	}
	
	/**
	 * @param string key
	 * @param mixed default
	 * @return mixed
	 */
	public function fetch($key, $default = null)
	{
		return $this->default_cache->fetch($key, $default);
	}
	
	/**
	 * @param string key
	 * @return \ibidem\cache\Cache $this
	 */
	public function delete($key)
	{
		return $this->default_cache->delete($key);
	}
	
	/**
	 * @param string key
	 * @param mixed data
	 * @param integer time
	 * @return \ibidem\cache\Cache $this
	 */
	public function store($key, $data, $lifetime = null)
	{
		return $this->default_cache->store($key, $data, $lifetime);
	}

} # class
