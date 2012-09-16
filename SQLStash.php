<?php namespace mjolnir\cache;

/**
 * @package    mjolnir
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
	protected $mass_ints = [];
	protected $mass_bools = [];
	protected $int_sets = [];
	protected $page = null;
	protected $partial_key;
	protected $value_sets = [];
	protected $order;
	protected $group_by;
	protected $constraints = [];

	/**
	 * @return \app\SQLStash
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
	function timers(array $tags)
	{
		$this->tags = $tags;

		return $this;
	}

	/**
	 * @return \mjolnir\cache\SQLStash $this
	 */
	function identity($identity)
	{
		$this->identity = $identity;

		return $this;
	}

	/**
	 * @return \mjolnir\cache\SQLStash $this
	 */
	function constraints(array $constraints)
	{
		$this->constraints = $constraints;

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

	function set($label, $value)
	{
		$this->value_sets[$label] = $value;

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
	 * @return \app\SQLCache $this
	 */
	function mass_int(array & $fields, array & $keys)
	{
		$this->mass_ints[] = [$fields, $keys];

		return $this;
	}

	/**
	 * @return \app\SQLCache $this
	 */
	function mass_bool(array & $fields, array & $keys)
	{
		$this->mass_bools[] = [$fields, $keys];

		return $this;
	}

	/**
	 * @return \app\SQLCache $this
	 */
	function order(array & $order)
	{
		$this->order = $order;

		return $this;
	}

	/**
	 * @return \mjolnir\cache\SQLStash $this
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
		$page = $page === null ? null : (int) $page;
		$limit = $limit === null ? null : (int) $limit;
		$offset = (int) $offset;
		$this->page = [$page, $limit, $offset];

		return $this;
	}

	/**
	 * @return \app\SQLStash $this
	 */
	function group_by($statement)
	{
		$this->group_by = $statement;
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
	function fetch_all(array $format = null)
	{
		if (empty($this->identity))
		{
			throw new \app\Exception_NotApplicable
				('Identity not provided for snatch query.');
		}

		if (empty($this->partial_key))
		{
			throw new \app\Exception_NotApplicable
				('Partial key not provided for snatch query.');
		}

		$cachekey = $this->identity;

		if ( ! empty($this->partial_key))
		{
			$cachekey .= '__'.$this->partial_key;
		}

		$sql = $this->sql;
		if ( ! empty($this->constraints))
		{
			$constraints = ' WHERE ';
			$constraints .= \app\Collection::implode
				(
					' AND ', # delimiter
					$this->constraints, # source

					function ($k, $value) {

						$k = \strpbrk($k, ' .()') === false ? '`'.$k.'`' : $k;

						if (\is_bool($value))
						{
							return $k.' = '.($value ? 'TRUE' : 'FALSE');
						}
						else if (\is_numeric($value))
						{
							return $k.' = '.$value;
						}
						else if (\is_null($value))
						{
							return $k.' IS NULL';
						}
						else # string, or string compatible
						{
							return $k.' = '.\app\SQL::quote($value);
						}
					}
				);

			$sql .= $constraints;
			$cachekey .= '__con'.\sha1($constraints);
		}

		if ( ! empty($this->group_by))
		{
			$group_by = ' GROUP BY '.$this->group_by;
			$sql .= $group_by;
			$cachekey .= '__groupby'.\sha1($group_by);
		}

		if ( ! empty($this->order))
		{
			$order = ' ORDER BY ';
			$order .= \app\Collection::implode(', ', $this->order, function ($query, $order) {
				return \strpbrk($query, ' .') === false ? '`'.$query.'` '.$order : $query.' '.$order;
			});

			$sql .= $order;
			$cachekey .= '__order'.\sha1($order);
		}

		if ( ! empty($this->page))
		{
			$sql .= ' LIMIT :limit OFFSET :offset';
			$cachekey .= '__p'.$this->page[0].'l'.$this->page[1].'o'.$this->page[2];
		}

		$result = \app\Stash::get($cachekey, null);

		if ($result === null)
		{
			if (empty($this->table))
			{
				throw new \app\Exception_NotApplicable
					('Table not provided for snatch query.');
			}

			$statement = \app\SQL::prepare($this->identifier, \strtr($sql, [':table' => '`'.$this->table.'`']));

			if ($this->page !== null)
			{
				$statement->page($this->page[0], $this->page[1], $this->page[2]);
			}

			static::process_statement($statement);

			$result = $statement->execute()->fetch_all($format);

			\app\Stash::store($cachekey, $result, $this->tags);
		}

		return $result;
	}

	/**
	 * Excutes and caches, or just returns from cache if present.
	 *
	 * @return array single row
	 */
	function fetch_array(array $format = null)
	{
		$result = $this->fetch_all($format);

		if (empty($result))
		{
			return null;
		}
		else
		{
			return $result[0];
		}
	}

	/**
	 * Include all sets in statement
	 */
	protected function process_statement($statement)
	{
		foreach ($this->mass_sets as $mass_set)
		{
			$statement->mass_set($mass_set[1], $mass_set[0]);
		}

		foreach ($this->mass_ints as $mass_set)
		{
			$statement->mass_int($mass_set[1], $mass_set[0]);
		}

		foreach ($this->mass_bools as $mass_set)
		{
			$statement->mass_bool($mass_set[1], $mass_set[0]);
		}

		foreach ($this->int_sets as $label => $value)
		{
			$statement->set_int($label, $value);
		}

		foreach ($this->value_sets as $label => $value)
		{
			$statement->set($label, $value);
		}
	}

} # class