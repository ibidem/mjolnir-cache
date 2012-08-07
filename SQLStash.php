<?php namespace ibidem\cache;

/**
 * @package    ibidem
 * @category   Cache
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class SQLStash extends \app\Instantiatable
{
	protected $sql;
	protected $identifier;
	protected $tags = [];
	protected $identity;
	protected $table;

	protected $mass_sets = [];
	protected $int_sets = [];

	/**
	 * @return \app\SQLCache
	 */
	static function prepare($identifier, $sql)
	{
		$instance = static::instance();
		$instance->sql = $sql;
		$instance->identifier = $identifier;

		return $instance;
	}

	/**
	 * Are methods that applied to a table, invalidate any cached queries to 
	 * said table. We store which timers affect the table then react to them.
	 * So once a timer is called the cache resets itself.
	 */
	function tags(array $tags)
	{
		$this->tags = $tags;

		return $this;
	}

	/**
	 * Sets the identity of the operation; to be used when processing cache 
	 * effects
	 *
	 * @return \app\SQLCache $this
	 */
	function is($identity)
	{
		$this->identity = $identity;

		return $this;
	}

	/**
	 * @return \app\SQLCache $this
	 */
	function table($table)
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * @return \app\SQLCache $this
	 */
	function set_int($label, $value)
	{
		$this->int_sets[$label] = $value;

		return $this;
	}

	/**
	 * @return \app\SQLCache $this
	 */
	function mass_set(array & $fields, array & $keys)
	{
		$this->mass_sets[] = [$fields, $keys];

		return $this;
	}

	/**
	 * Executes the given query, and processes cache consequences.
	 */
	function run()
	{
		$this->sql = \strtr($this->sql, [':table' => '`'.$this->table.'`']);
		
		$statement = \app\SQL::prepare($this->identifier, $this->sql);
		
		foreach ($this->mass_sets as $mass_set)
		{
			$statement->mass_set($mass_set[1], $mass_set[0]);
		}
		
		foreach ($this->int_sets as $label => $value)
		{
			$statement->set_int($label, $value);
		}
		
		$statement->execute();
		
		// invalidte tags
		\app\Stash::purge($this->tags);
	}

	/**
	 * Excutes and caches, or just returns from cache if present.
	 *
	 * @return array rows
	 */
	function all()
	{
		throw new \app\Exception_NotApplicable
			('[all] method not implmented in ['.\get_called_class().'].');
	}

	/**
	 * Excutes and caches, or just returns from cache if present.
	 *
	 * @return array single row
	 */
	function entry()
	{
		throw new \app\Exception_NotApplicable
			('[entry] method not implmented in ['.\get_called_class().'].');
	}

} # class