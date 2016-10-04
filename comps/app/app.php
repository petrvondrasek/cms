<?php

namespace comps\app
{
	use comps\app\entity\entity as entity;

class app
{
	public $model, $conf, $request;

	public $notfound;

	public function __construct()
	{
		$this->model = new app_model();
	}

	public function init($conf)
	{
		$this->root = $_SERVER['DOCUMENT_ROOT'];

		$this->conf = $conf['app'];

		$this->init_model($conf);

		$this->init_request();

		$this->init_headers();

		$this->init_lang($conf['lang']);

		$this->init_session($conf['session']);
		
		$this->init_cache($conf['cache']);
	}

	public function init_model($conf)
	{
		if($conf['mysql']['use'])
		{
			$this->model->mysql_connect($conf['mysql']);

			$this->model->mysql_init_lang($conf['lang']);
		}

		if($conf['sqlite']['use'])
		{
			$this->model->sqlite_open($conf['sqlite']['filepath']);
		}
	}

	public function init_request()
	{
		$this->request = parse_url(
			$_SERVER['REQUEST_URI'], PHP_URL_PATH);

		$this->request_array = explode('/', trim($this->request, '/'));
	}

	public function init_headers()
	{
		$remove = array('X-Powered-By');
		foreach($remove as $str)
		{
			header_remove($str);
		}
	}

	public function init_lang($conf_lang)
	{
		date_default_timezone_set($conf_lang['timezone']);
		setlocale(LC_ALL, $conf_lang['locale']);
		mb_internal_encoding($conf_lang['charset']);
	}

	public function init_session($conf_session)
	{
		if($conf_session['name'])
		{
			session_name($conf_session['name']);

			session_set_cookie_params(
				$conf_session['time'], '/', $conf_session['hostname']);

			session_start();
			session_regenerate_id();
		}
	}

	public function init_cache($conf_cache)
	{
		
	}


	public function entity($set = array())
	{
		return new entity($set);
	}


	public function read($array, $key, $default = NULL)
	{
		if(isset($array[$key]) and !empty($array[$key]))
			return $array[$key];

		return $default;
	}

	public function readEntity($array, $key)
	{
		if($arr = $this->read($array, $key))
			return new entity($arr);

		return false;
	}


	public function header_css()
	{
		$offset = 3600 * 24 * 14;
		$expire = 'Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';

		header('Content-type: text/css');
		header($expire);
	}

	public function header_js()
	{
		$offset = 3600 * 24 * 14;
		$expire = 'Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';

		header('Content-type: text/javascript charset: UTF-8');
		header($expire);
	}

	public function redirect($url = NULL)
	{
		if($url == NULL)
			$url = $_SERVER['REQUEST_URI'];
		// + http status code
		header('Location: '.$url);
		exit;
	}

	public function notfound()
	{
		$protocol = $this->read($_SERVER, 'SERVER_PROTOCOL');
		header($protocol." 404 Not Found", true);

		$this->notfound = true;
	}


	public function mail($from, $to, $subject, $content, $extract = array())
	{
		$headers[] = "Content-Type: text/html; charset=UTF-8";
		$headers[] = "From: $from";
		$headers = implode("\r\n", $headers);
		
		$content = $this->content_eval($content, $extract);

		return mail($to, $subject, $content, $headers);
	}

	public function content_eval($content, $extract = array())
	{
		extract($extract);

		ob_start();
		$str = htmlspecialchars($content);
		eval("\$str = \"$str\";");
		$str = htmlspecialchars_decode($str);
		echo $str;
		return ob_get_clean();
	}

	public static function minify($string, $enabled = true)
	{
		if($enabled)
			return str_replace(array("\r\n", "\n", "\t", "  "), "", $string);

		return $string;
	}

	public function out($string)
	{
		echo htmlspecialchars($string);
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
