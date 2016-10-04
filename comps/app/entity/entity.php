<?php

namespace comps\app\entity
{

class entity
{
	public $vars = array();

	public $value;

	public function __construct($array = array())
	{
		$this->set($array);
	}

	public function get($names = array())
	{
		return array_intersect_key($this->vars, array_flip($names));
	}

	public function set($vars = array())
	{
		foreach($vars as $key => $val)
		{
			if(is_object($this->$key))
				$this->$key->value = $val;
			else
				$this->$key = $val;
		}
	}
	
	public function reset($names = array())
	{
		$this->vars = array_intersect_key($this->vars, array_flip($names));
	}

	public function __get($name)
	{
		if(array_key_exists($name, $this->vars))
		{
			return $this->vars[$name];
		}
		return NULL;
	}

	public function __set($name, $value)
	{
		$this->vars[$name] = $value;
	}

	public function __call($name, $argv)
	{
		$out = htmlspecialchars($this->$name);

		if(isset($argv[0]) and $out != "")
			printf($argv[0], $out);
		else
			echo $out;
	}

	public function __invoke($name)
	{
		return $this->$name();
	}

	public function __toString()
	{
		return (string) $this->value;
	}

	public function toArray()
	{
		return $this->vars;
	}
}

}

?>
