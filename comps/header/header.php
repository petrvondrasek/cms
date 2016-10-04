<?php

namespace comps\header
{

class header
{
	public function __construct($app)
	{
		$this->app = $app;

		$conf = $app->model->readJSON(
			__DIR__.'/header.json');

		$this->dict = $app->entity($conf[$app->conf['lang']]);
	}

	public function __invoke($name)
	{
		echo $this->dict->$name;
	}

	public function process()
	{
		$path = $this->app->read($_GET, 'id', '/');
?>
<header id="header">
<?php
		$this->nav($path);
?>
</header>
<?php
	}

	public function nav($path)
	{
		$parent = str_replace('\\', '/', dirname($path));

		if($parent != '/') $parent.='/';

		$path = explode('/', trim($path, '/'));

		if($path[0] != '') array_unshift($path, '');
?>
<ul id="header_nav">
<?php $i = 0; $p = ''; foreach($path as $nav): ?>
<?php $i++; $p .= $nav.'/'; ?>
 <li><a href="/?id=<?php echo $p; ?>"<?php if($i == sizeof($path)):?> class="active"<?php endif; ?>><?php echo empty($nav)?'home':$nav; ?></a></li>
<?php endforeach; ?>
</ul>
<?php if(1 and $i and $p!=$parent): ?>
<a id="header_up" title="Alt + u" accesskey="u" href="?id=<?php echo $parent; ?>"><?php $this('up'); ?></a>
<?php endif; ?>
<?php
	}
}

}

?>
