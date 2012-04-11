<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cache_APC extends \app\Instantiatable
	implements \ibidem\types\Cache
{
	/**
	 * @var \ibidem\cache\Cache_APC
	 */
	private static $instance;
	
	/**
	 * @return \ibidem\cache\Cache_APC 
	 */
	public static function instance()
	{
		if (self::$instance)
		{
			return self::$instance;
		}
		else # uninitialized
		{
			if ( ! \extension_loaded('APC'))
			{
				throw new \app\Exception_NotApplicable('APC extention not loaded.');
			}

			return self::$instance = parent::instance();
		}
	}
	
	/**
	 * @param string key
	 * @param mixed default
	 * @return mixed
	 */
	public function fetch($key, $default = null)
	{
		$data = \apc_fetch(\str_replace(array('/', '\\', ' '), '_', $key), $success);

		return $success ? $data : $default;
	}
	
	/**
	 * @param string key
	 * @return \ibidem\cache\Cache_APC $this
	 */
	public function delete($key)
	{
		\apc_delete(\str_replace(array('/', '\\', ' '), '_', $key));
		
		return $this;
	}
	
	/**
	 * @param string key
	 * @param mixed data
	 * @param integer lifetime (seconds)
	 * @return \ibidem\cache\Cache_APC $this
	 */
	public function store($key, $data, $lifetime_seconds = null)
	{
		if ($lifetime_seconds === null)
		{
			$lifetime_seconds = $cache['APC']['lifetime.default'];
		}
		
		\apc_store(\str_replace(array('/', '\\', ' '), '_', $key), $data, $lifetime_seconds);
		
		return $this;
	}

} # class
