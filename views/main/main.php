<?php

namespace views\main
{

class main
{
	const version = '1.0.0';

	const index = false;

	public $comps = array();

	public $main_comp;

	public function __construct($app)
	{
		$this->app = $app;
	}

	public function getObject()
	{
		return $this;
	}

	public function init()
	{
		if(empty($this->main_comp))
		{
			$this->main_comp = $this->c('content')->init();
		}

		return $this->html();
	}

	protected function c($comp_name)
	{
		if(!isset($this->comps[$comp_name]))
		{
			$class = 'comps\\'.$comp_name.'\\'.$comp_name;
			$this->comps[$comp_name] = new $class($this->app);
		}

		return $this->comps[$comp_name];
	}

	protected function html()
	{
		ob_start();
?>
<!DOCTYPE html>
<html lang="<?php echo $this->app->conf['lang']; ?>" dir="ltr">
<?php
		$this->head();
		$this->body();
?>
</html>
<?php
		return ob_get_flush();
	}

	protected function head()
	{
		$content = $this->main_comp->content;
?>
<head id="main_head">
 <meta charset="UTF-8" />
<?php if($_GET or main::index == false): ?>
 <meta name="robots" content="noindex, nofollow" />
<?php endif; ?>
 <meta name="description" content="<?php echo $content->description; ?>" />
 <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width" />
 <title><?php echo $content->title; ?></title>
<?php if($this->app->read($_SERVER, 'HTTP_X_REAL_PORT') == 443): ?>
 <base href="<?php echo $this->app->conf['url_https']; ?>" />
<?php else: ?>
 <base href="<?php echo $this->app->conf['url']; ?>" />
<?php endif; ?>
 <link rel="stylesheet" href="index.css?<?php echo main::version; ?>" />
 <link rel="shortcut icon" href="/icon.png?<?php echo main::version; ?>" />
 <script src="index.js?<?php echo main::version; ?>" async defer></script>
</head>
<?php
	}

	protected function body()
	{
		$user = $this->app->entity(array(
			'role' => 'admin',
			'email' => 'petr.vondrasek@gmail.com'));
?>
<body id="main_body">
<?php
		if(1 or $this->app->read($_SESSION, 'admin'))
		{
			$this->c('header')->process();

			$this->content();

			// $this->c('login')->process();
			$this->c('footer')->process($user);
		}
		else
		{
			$this->c('login')->process();
		}
?>
</body>
<?php
	}

	protected function content()
	{
		$content_edit = $this->c('content')->get_content();
?>
<div id="main_content">
<?php
		$this->c('content')->process();

		$this->c('nodes')->process();
		// todo: pokud neexistuji poduzly, ukazeme jen komponentu, ktera pridava nove

		if($content_edit->ac_product)
		{
			//$this->c('product')->process();
		}
		else
		{
			//$this->c('product')->process();
		}

		if($content_edit->ac_images)
			$this->c('images')->process();
?>
</div>
<?php
	}
}

}

?>
