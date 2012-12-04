<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Cache
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash
{
	/**
	 * @var \mjolnir\types\Cache
	 */
	static protected $instance = null;
	
	/**
	 * 
	 */
	static function init()
	{
		$class = '\app\Stash_'.\app\CFS::config('mjolnir/cache')['default.cache'];
		static::$instance = $class::instance();
	}
	
	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		static::$instance or static::init();
		static::$instance->set($key, $data, $expires);
	}

	/**
	 * Retrieves data from $key
	 * 
	 * @return mixed data or default
	 */
	static function get($key, $default = null)
	{
		static::$instance or static::init();
		return static::$instance->get($key, $default);
	}

	/**
	 * Deletes $key
	 */
	static function delete($key)
	{
		static::$instance or static::init();
		static::$instance->delete($key);
	}
	
	/**
	 * Wipe cache.
	 */
	static function flush()
	{
		static::$instance or static::init();
		static::$instance->flush();
	}
	
	/**
	 * Stores data in $key, tagged with tags.
	 */
	static function store($key, $data, array $tags = [], $expires = null)
	{
		static::$instance or static::init();
		static::$instance->store($key, $data, $tags, $expires);
	}

	/**
	 * Deletes all caches tagged with $tags
	 */
	static function purge(array $tags)
	{
		static::$instance or static::init();
		static::$instance->purge($tags);
	}
	
	/**
	 * When timers is not provided the timers are retrieved automatically from 
	 * the model. (timers act as invalidators for the stash)
	 * 
	 * Model must be provided in proper case. No prefix.
	 * 
	 * @return array tags for given parameters
	 */
	static function tags($model, array $timers = null)
	{
		// clean model; remove Model_ prefix if present
		$model = \preg_replace
			(
				'#^Model_#', '', 
				\join('', \array_slice(\explode('\\', $model), -1))
			);
		
		if ($timers === null)
		{
			$class = '\app\Model_'.$model;
			$timers = $class::timers();
		}
		
		$tags = [];
		foreach ($timers as $timer)
		{
			$tags[] = $model.'__'.$timer;
		}
		
		return $tags;
	}

} # class
