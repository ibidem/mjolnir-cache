<?php namespace ibidem\cache;

/**
 * Base class for stash files.
 * 
 * @package    ibidem
 * @category   Stash
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Stash_Base extends \app\Instantiatable
{
	/**
	 * Given a key, removes all special characters.
	 * 
	 * @return string sanitized key
	 */
	protected static function safe_key($key)
	{
		// remove namespace delcaration
		$key = \join('', \array_slice(\explode('\\', $key), -1));
		// convert :: to double dash
		$key = \str_replace('::', '__', $key);
		// remove special characters
		$key = \preg_replace('#[^a-zA-Z0-9_]#', '', $key);
		
		return $key;
	}

} # class
