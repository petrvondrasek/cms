<?php

namespace comps\nodes
{

class nodes
{
	public function __construct($app)
	{
		$this->app = $app;

		$conf = $app->model->readJSON(
			__DIR__.'/nodes.json');

		$this->model = new nodes_model($app->model);

		$this->dict = $app->entity($conf[$app->conf['lang']]);

		$this->root = $this->app->root.$this->app->conf['main_root'];

		$this->main = $this->app->conf['main_url'];

		$this->path = $this->app->read($_GET, 'id', '/');
	}

	public function __invoke($name)
	{
		echo $this->dict->$name;
	}

	public function process()
	{
		$this->nodes();
	}

	public function nodes()
	{
		$nodes = $this->model->readNodesByPath($this->path, $this->main);
?>
<div id="nodes" class="comp">
<div id="<?php $this('id'); ?>" class="comp_header">
 <h2><a id="nodes_button" accesskey="2" title="<?php $this('title'); ?>" href="?id=<?php echo $this->path; ?>#<?php $this('id'); ?>"><?php $this('title'); ?></a></h2>
</div>
<div class="comp_body">
<?php if($nodes): ?>
<div id="nodes_list">
<ul>
<?php $c=0; foreach($nodes as $object): ?>
 <li>
<?php if(0): ?>
  <a class="img prefetch" href="/?id=<?php $object->path(); ?>">
   <noscript data-src="<?php $object->img_1x(); ?>"<?php if(0): ?> data-srcset="<?php $object->img_1x(); ?> 1x, <?php $object->img_1x(); ?> 2x"<?php endif; ?> data-alt=""><img src="<?php $object->img_1x(); ?>" alt="" width="32" height="32" />
   </noscript>
  </a>
<?php endif; ?>
  <a href="/?id=<?php $object->path(); ?>"><?php $object->name(); ?></a>
 </li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
</div>
</div>
<?php
	}
}

}

?>
