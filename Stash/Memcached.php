<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Memcached extends \app\Stash_Base
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
	private $memcached;

	/**
	 * @return \app\Stash_Memcached
	 */
	static function instance()
	{
		if (self::$instance)
		{
			return self::$instance;
		}
		else # uninitialized
		{
			if ( ! \class_exists('Memcached'))
			{
				throw new \app\Exception('memcached extention not loaded.');
			}

			self::$instance = parent::instance();

			$memcache_config = \app\CFS::config('mjolnir/cache')['Memcached'];

			if ($memcache_config['persistent_id'])
			{
				$memcache = self::$instance->memcached = new \Memcached($memcache_config['persistent_id']);
			}
			else
			{
				$memcache = self::$instance->memcached = new \Memcached;
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

			return self::$instance;
		}
	}

	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		$key = static::safe_key($key);
		$config = \app\CFS::config('mjolnir/cache')['Memcached'];
		if ($expires === null)
		{
			$expires = $config['lifetime.default'];
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
		if ( ! \app\CFS::config('mjolnir/base')['caching'])
		{
			return $default;
		}

		$key = static::safe_key($key);
		$memcache = static::instance()->memcached;
		$result = \unserialize($memcache->get($key));
		if (\Memcached::RES_SUCCESS === $memcache->getResultCode())
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
