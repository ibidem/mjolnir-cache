<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Cache_Memcached extends \app\Instantiatable
	implements \ibidem\types\Cache
{
	/**
	 * @var \ibidem\cache\Cache_Memecached
	 */
	private static $instance;
	
	private $memcached;
	
	/**
	 * @return \ibidem\cache\Cache_Memecached
	 */
	public static function instance()
	{
		if (self::$instance)
		{
			return self::$instance;
		}
		else # uninitialized
		{
			if ( ! \extension_loaded('memcached'))
			{
				throw new \app\Exception_NotApplicable('memcached extention not loaded.');
			}
			
			self::$instance = parent::instance();
			
			$memcached_config = \app\CFS::config('ibidem\cache');
			$memcached_config = $memcached_config['Memcached'];
			
			if ($memcached_config['persistent_id'])
			{
				$memcached = self::$instance->memcached = new \Memcached($memcached_config['persistent_id']);
			}
			else
			{
				$memcached = self::$instance->memcached = new \Memcached;
			}
			
			$servers = $memcached->getServerList();
			if (empty($servers))
			{
				$memcached->setOption(\Memcached::OPT_RECV_TIMEOUT, $memcached_config['timeout.recv']);
			    $memcached->setOption(\Memcached::OPT_SEND_TIMEOUT, $memcached_config['timeout.send']);
				$memcached->setOption(\Memcached::OPT_TCP_NODELAY, $memcached_config['tcp.nodelay']);
				$memcached->setOption(\Memcached::OPT_PREFIX_KEY, $memcached_config['prefix']);
				
				foreach ($memcached_config['servers'] as $server)
				{
					$memcached->addServer($server['host'], $server['port'], $server['weight']);
				}
			}

			return self::$instance;
		}
	}
	
	/**
	 * @param string key
	 * @param mixed default
	 * @return mixed
	 */
	public function fetch($tag, $key, $default = null)
	{
		$key .= $tag.'/'.$key;
		$result = $this->memcached->get($key);
		if (\Memcached::RES_SUCCESS === $this->memcached->getResultCode())
		{
			return $result;
		}
		else # failed to get key
		{
			return $default;
		}
	}
	
	/**
	 * @param string key
	 * @return \ibidem\cache\Cache_Memecached $this
	 */
	public function delete($tag, $key)
	{
		$key .= $tag.'/'.$key;
		$this->memcached->delete($key, 0);
		return $this;
	}
	
	/**
	 * @param string key
	 * @param mixed data
	 * @param integer lifetime (seconds)
	 * @return \ibidem\cache\Cache_Memecached $this
	 */
	public function store($tag, $key, $data, $lifetime_seconds = null)
	{
		$key .= $tag.'/'.$key;
		if ($lifetime_seconds === null)
		{
			$lifetime_seconds = $cache['Memcached']['lifetime.default'];
		}
		
		$this->memcached->set($key, $data, $lifetime_seconds);
		
		return $this;
	}

} # class
