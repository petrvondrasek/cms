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

	public function initVars($array, $meta)
	{
		$values = array();

		foreach($array as $name)
		{
			if(isset($meta[$name])) // init var by name
			{
				$value = $this->$name;
				$this->$name = $meta[$name];
				$this->$name->value = $value;

				eval("\$valid = array(".$this->$name->valid.");");
				$this->$name->valid = $valid;

				$options = array();
				if($count = $this->$name->options)
				{
					for($i=1; $i<=$count; ++$i)
					{
						$optname = $name.'.opt'.$i;
						$option = $meta[$optname];
						
						$options[] = $option;
					}

					$this->$name->options = $options;
				}
			}
			$values[] = (string) $this->$name;
		}

		return implode('', $values);
	}
	
	public function validate($array = array())
	{
		$return = true;

		foreach($this->vars as $var)
		{
			if(is_object($var) and is_array($var->valid))
			{
				if(!empty($array) and !in_array($var->id, $array))
					continue;

				foreach($var->valid as $rule => $arg)
				{
					is_int($rule) and $rule = $arg;
					if(!$this->check($var->value, $rule, $arg))
					{
						$var->error = $rule;
						$return = false;
					}
				}
			}
		}

		return $return;
	}

	private function check($data, $rule, $arg = "")
	{
		switch($rule)
		{
			case '_nonempty':
				return !empty($data);
			case '_min':
				return empty($data) or mb_strlen($data) >= $arg;
			case '_max':
				return mb_strlen($data) <= $arg;
			case '_sameas':
				return $data == $arg;
			case '_num':
				if($data)
					return is_numeric($data);
				else
					return true;
			case '_email':
				return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/', $data);
			case '_in':
				return in_array($data, $arg);
			default:
				return $arg == false;
		}
	}

	public function URLfriendly($url)
	{
		$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
		$url = trim($url, "-");
		$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
		$url = mb_strtolower($url);
		$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
		return $url;
	}
}

}

?>
