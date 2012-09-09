<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Cache
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash extends \app\Instantiatable
{
	/**
	 * Store a value under a key for a certain number of seconds.
	 */
	static function set($key, $data, $expires = null)
	{
		$class = '\app\Stash_'.\app\CFS::config('ibidem/cache')['default.cache'];
		$class::set($key, $data, $expires);
	}

	/**
	 * Retrieves data from $key
	 * 
	 * @return mixed data or default
	 */
	static function get($key, $default = null)
	{
		$class = '\app\Stash_'.\app\CFS::config('ibidem/cache')['default.cache'];
		return $class::get($key, $default);
	}

	/**
	 * Deletes $key
	 */
	static function delete($key)
	{
		$class = '\app\Stash_'.\app\CFS::config('ibidem/cache')['default.cache'];
		$class::delete($key);
	}
	
	/**
	 * Stores data in $key, tagged with tags.
	 */
	static function store($key, $data, array $tags = [], $expires = null)
	{
		$class = '\app\Stash_'.\app\CFS::config('ibidem/cache')['default.cache'];
		$class::store($key, $data, $tags, $expires);
	}

	/**
	 * Deletes all caches tagged with $tags
	 */
	static function purge(array $tags)
	{
		$class = '\app\Stash_'.\app\CFS::config('ibidem/cache')['default.cache'];
		$class::purge($tags);
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
		// clean model; remove Model_ prefix is present
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
