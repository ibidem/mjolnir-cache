<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Memcached extends \app\Instantiatable implements \mjolnir\types\Cache
{
	use \app\Trait_Cache;

	/**
	 * @var \Memcache
	 */
	protected $memcached;

	/**
	 * @return \mjolnir\types\Cache
	 */
	static function instance()
	{
		if ( ! \app\CFS::config('mjolnir/base')['caching'])
		{
			return \app\Stash_Null::instance();
		}

		if ( ! \class_exists('Memcached'))
		{
			throw new \app\Exception('memcached extention not loaded.');
		}

		$instance = parent::instance();

		$memcache_config = \app\CFS::config('mjolnir/cache')['Memcached'];

		if ($memcache_config['persistent_id'])
		{
			$memcache = $instance->memcached = new \Memcached($memcache_config['persistent_id']);
		}
		else # no persistent id
		{
			$memcache = $instance->memcached = new \Memcached;
		}

		$servers = $memcache->getServerList();
		if (empty($servers))
		{
			$memcache->setOption(\Memcached::OPT_RECV_TIMEOUT, $memcache_config['timeout.recv']);
			$memcache->setOption(\Memcached::OPT_SEND_TIMEOUT, $memcache_config['timeout.send']);
			$memcache->setOption(\Memcached::OPT_TCP_NODELAY, $memcache_config['tcp.nodelay']);
			$memcache->setOption(\Memcached::OPT_PREFIX_KEY, $memcache_config['prefix']);

			foreach ($memcache_config['servers'] as $server)
			{
				$memcache->addServer($server['host'], $server['port'], $server['weight']);
			}
		}

		return $instance;
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	function set($key, $data, $expires = null)
	{
		if ($expires === null)
		{
			$config = \app\CFS::config('mjolnir/cache')['Memcached'];
			$expires = $config['lifetime.default'];
		}

		static::instance()->memcached->set
			(
				static::safe_key($key),
				\serialize($data),
				$expires
			);
	}

	/**
	 * Retrieves data from $key
	 *
	 * @return mixed data or default
	 */
	function get($key, $default = null)
	{
		$result = \unserialize($this->memcached->get(static::safe_key($key)));
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
	 * Deletes $key
	 */
	function delete($key)
	{
		$this->memcached->delete(static::safe_key($key), 0);
	}

	/**
	 * Wipe cache.
	 */
	function flush()
	{
		$this->memcached->flush();
	}

} # class
