<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ViewStash implements \mjolnir\types\ViewStash
{
	use \app\Trait_ViewStash;

	/**
	 * @var array
	 */
	protected static $stash_keys = null;

	/**
	 * @return bool
 	 */
	static function load($key, array $tags = null)
	{
		$tags !== null or $tags = [];

		$stash_key = '_ViewStash_'.\app\Lang::targetlang().'__'.$key;
		$view = \app\Stash::get($stash_key, null);

		if ($view !== null)
		{
			echo $view;

			return true;
		}
		else # stash is empty
		{
			static::$stash_keys[] = [$stash_key, $tags];

			\ob_start(); # start output buffering

			return false;
		}
	}

	/**
	 * ...
	 */
	static function save()
	{
		$definition = \array_pop(static::$stash_keys);

		$buffer = \ob_get_flush();

		\app\Stash::store($definition[0], $buffer, $definition[1]);
	}

} # class