<?php

namespace comps\app
{
	use comps\app\entity\entity as entity;

class app_model
{
	public $mysql;

	public $sqlite;

	public function __construct() {}

	public function __destruct()
	{
		if(isset($this->mysql) and $this->mysql->connect_error == false)
		{
			$this->mysql->close();
		}

		if(isset($this->sqlite))
		{
			$this->sqlite = NULL;
			//$this->sqlite->close();
		}
	}
	
	public function entity($set = array())
	{
		return new entity($set);
	}

	public function readJSON($filename)
	{
		if(($json =  @file_get_contents($filename)) == false)
			throw new \exception('config read error : (');

		if(($array = json_decode($json, true)) == NULL)
			throw new \exception('config parse error : (');

		return $array;
	}


	public function mysql_connect($conf_db)
	{
		$this->mysql = @new \mysqli(
			$conf_db['host'], $conf_db['name'],
			$conf_db['passwd'], $conf_db['dbname']);

		// $this->db->query('SET profiling=1');

		if($this->mysql->connect_error)
		{
			throw new \exception('database connection error 8 (');
		}
		else
			return $this->mysql;
	}

	public function mysql_init_lang($conf_lang)
	{
		$this->mysql->query("SET CHARACTER SET utf8");
		$this->mysql->query("SET lc_time_names = '$conf_lang[time_names]'");
	}

	public function mysql_set($object)
	{
		$set = array();

		foreach($object->vars as $key => &$value)
		{
			$value = $this->mysql->real_escape_string($value);

			if(strlen($value) == 0)
				$set[] = $key."=".'NULL';
			else
				$set[] = $key."='".$value."'";
		}

		return implode(", ", $set);
	}

	public function mysql_prepare($query, $vars)
	{
		foreach($vars as $key => &$value)
			$value = $this->mysql->real_escape_string($value);

		array_unshift($vars, $query);

		return call_user_func_array('sprintf', $vars);
	}

	public function mysql_query($query,
		$class = 'comps\app\entity\entity', $argv = array())
	{
		$objects = array();

		try
		{
			if($result = $this->mysql->query($query))
			{
				if($this->mysql->field_count)
				{
					while($object = $result->fetch_object($class, $argv))
					{
						if($object->id)
							$objects[$object->id] = $object;
						else
							$objects[] = $object;
					}
				}
			}
			else
			{
				throw new \exception($this->mysql->error);
			}
		}
		catch(\exception $e) // log
		{

		}
		
		if(substr(ltrim($query), 0, 6) == 'SELECT')
		{
			return $objects;
		}
		else
		{
			return $this->mysql->affected_rows;
		}
	}

	public function mysql_query_one($query)
	{
		if($ret = $this->mysql_query($query))
			return current($ret);
		else
			return false;
	}


	public function sqlite_open($file_path)
	{
		$this->sqlite = @new \PDO('sqlite:'.$file_path);

		if(($result = $this->sqlite->query("
			SELECT 1 FROM content_admin LIMIT 1")) == false)
		{
			throw new \exception('database open error 8 (');
		}

		/*
		$this->sqlite = @new \SQLite3($file_path);

		if($this->sqlite == false)
		{
			throw new \exception('database open error 8 (');
		}
		*/

		return $this->sqlite;
	}

	public function sqlite_prepare($query, $vars)
	{
		foreach($vars as $key => &$value)
		{
			//$value = $this->sqlite->escapeString($value);
			$value = $this->sqlite->quote($value);
		}

		array_unshift($vars, $query);

		return call_user_func_array('sprintf', $vars);
	}

	public function sqlite_query($query,
		$class = 'comps\app\entity\entity')
	{
		$objects = array();

		if($result = $this->sqlite->query($query))
		{
			// while($entry = $result->fetchArray(SQLITE3_ASSOC))
			while($entry = $result->fetchObject($class))
			{
				if(isset($entry->id))
					$objects[$enryy->id] = $this->entity($entry);
				else
					$objects[] = $this->entity($entry);
			}
		}

		return $objects;
	}

	public function sqlite_query_one($query)
	{
		if($ret = $this->sqlite_query($query))
			return current($ret);
		else
			return false;
	}
}
	
}
	
?>
