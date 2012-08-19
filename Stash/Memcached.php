<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Memcached extends \app\Stash_Base
	implements 
		\ibidem\types\Stash, 
		\ibidem\types\TaggedStash
{
	use \app\Trait_TaggedStash;
	
	/**
	 * @var \app\Stash_Memecached
	 */
	private static $instance;
	
	/**
	 * @var \Memcached
	 */
	private $memcached;
	
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
			if ( ! \extension_loaded('memcached'))
			{
				throw new \app\Exception_NotApplicable('memcached extention not loaded.');
			}
			
			self::$instance = parent::instance();
			
			$memcached_config = \app\CFS::config('ibidem\cache')['Memcached'];
			
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
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		$key = static::safe_key($key);
		$config = \app\CFS::config('ibidem/cache');
		if ($expires === null)
		{
			$expires = $config['Memcached']['lifetime.default'];
		}
		
		static::instance()->memcached->set($key, \serialize($data), $expires);
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
		$memcached = static::instance()->memcached;
		$result = \unserialize($memcached->get($key));
		if (\Memcached::RES_SUCCESS === $memcached->getResultCode())
		{
			return $result;
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
		static::instance()->memcached->delete($key, 0);
	}

} # class
