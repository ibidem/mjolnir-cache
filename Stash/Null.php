<?php namespace mjolnir\cache;

/**
 * The purpose of the Null instance is to handle caching operations when
 * caching is disabled. As it's name implied, nothing will happen,
 * regardless of operation.
 *
 * @package    mjolnir
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Null extends \app\Instantiatable implements \mjolnir\types\Cache
{
	use \app\Trait_Cache;

	/**
	 * Store a value under a key for a certain number of seconds.
	 *
	 * @return static $this
	 */
	function set($key, $data, $expires = null)
	{
		return $this;
	}

	/**
	 * Retrieves data from $key
	 *
	 * @return mixed data or default
	 */
	function get($key, $default = null)
	{
		return $default;
	}

	/**
	 * Deletes $key
	 *
	 * @return static $this
	 */
	function delete($key)
	{
		return $this;
	}

	/**
	 * Wipe cache.
	 *
	 * @return static $this
	 */
	function flush()
	{
		return $this;
	}

	/**
	 * Tags the data key for reference.
	 *
	 * @return static $this
	 */
	function store($key, $data, array $tags = [], $expires = null)
	{
		return $this;
	}

	/**
	 * Removes cache entries tagged with specified tags.
	 *
	 * @return static $this
	 */
	function purge(array $tags)
	{
		return $this;
	}

	/**
	 * @return array tags
	 */
	function tags($model, array $timers = null)
	{
		// we still return the default in case it is used in other computations
		return \app\Stash::tags($model, $timers);
	}

} # class
