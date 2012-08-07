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
	protected $paged = null;
	protected $partial_key;

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

	function identity($identity)
	{
		$this->identity = $identity;
		
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
	 * @return \ibidem\cache\SQLStash $this
	 */
	function key($partial_key)
	{
		$this->partial_key = $partial_key;
		
		return $this;
	}
	
	/**
	 * @return \app\SQLStash $this
	 */
	function page($page, $limit, $offset = 0)
	{
		$this->paged = [$page, $limit, $offset];
		
		return $this;
	}
	
	/**
	 * Executes the given query, and processes cache consequences.
	 */
	function run()
	{		
		$statement = \app\SQL::prepare($this->identifier, \strtr($this->sql, [':table' => '`'.$this->table.'`']));
		
		static::process_statement($statement);
		
		$statement->execute();
		
		// invalidte tags
		\app\Stash::purge($this->tags);
	}

	/**
	 * Excutes and caches, or just returns from cache if present.
	 *
	 * @return array rows
	 */
	function fetch_all()
	{
		if (empty($this->identity))
		{
			throw new \app\Exception_NotApplicable
				('Identity not provided for snatch query.');
		}
		
		$key = $this->identity.'__'.$this->partial_key;
		
		$result = \app\Stash::get($key, null);
		
		if ($result === null)
		{
			if (empty($this->table))
			{
				throw new \app\Exception_NotApplicable
					('Table not provided for snatch query.');
			}
			
			$statement = \app\SQL::prepare($this->identifier, \strtr($this->sql, [':table' => '`'.$this->table.'`']));
			
			if ($this->paged !== null)
			{
				$statement->page($this->paged[0], $this->paged[1], $this->paged[2]);
			}
			
			static::process_statement($statement);
			
			$result = $statement->execute()->fetch_all();
			
			\app\Stash::store($key, $result, $this->tags);
		}
		
		return $result;
	}

	/**
	 * Excutes and caches, or just returns from cache if present.
	 *
	 * @return array single row
	 */
	function entry()
	{
		$result = $this->fetch_all();
		
		if (empty($result))
		{
			return null;
		}
		else
		{
			return $result[0];
		}
	}
	
	protected function process_statement($statement)
	{
		foreach ($this->mass_sets as $mass_set)
		{
			$statement->mass_set($mass_set[1], $mass_set[0]);
		}
		
		foreach ($this->int_sets as $label => $value)
		{
			$statement->set_int($label, $value);
		}
	}

} # class