<?php namespace mjolnir\cache;

/**
 * Base class for stash files.
 * 
 * @package    mjolnir
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
		// remove namespace delcaration, special characters, 
		// and convert :: to double underscore
		return \preg_replace
			(
				'#[^a-zA-Z0-9_]#', '', # find & replace
				\str_replace
					(
						'::', '__', # find & replace
						\join('', \array_slice(\explode('\\', $key), -1))
					)
			);
	}

} # class
