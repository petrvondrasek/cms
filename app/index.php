<?php

namespace
{
	use comps\app\app as app;

class main
{
	public $app, $conf;

	public function __construct()
	{
		$this->time = microtime(true);

		spl_autoload_extensions('.php');
		spl_autoload_register();

		try
		{
			$this->app = new app();

			$this->conf = $this->app->model->readJSON(
				'../app/cfg/config.conf');

			$this->app->init($this->conf);
		}
		catch(exception $e)
		{
			exit($e->getMessage());
		}
	}

	public function doc()
	{
		$view_name = $_SERVER['APP_VIEW'];

		$view_name = 'views\\'.$view_name.'\\'.$view_name;
		$view = new $view_name($this->app);

		$view->getObject()->init();
	}

	public function css($path)
	{
		$this->app->header_css();

		ob_start('comps\app\app::minify');

		include($path);

		return $this->app->minify(ob_get_flush());
	}

	public function js($path)
	{
		$this->app->header_js();

		ob_start('comps\app\app::minify');

		include($path);

		return $this->app->minify(ob_get_flush());
	}
}

}

?>
