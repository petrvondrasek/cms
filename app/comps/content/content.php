<?php

namespace comps\content
{

class content
{
	public function __construct($app)
	{
		$this->app = $app;

		$conf = $app->model->readJSON(
			__DIR__.'/content.json');

		$this->model = new content_model($app->model);

		$this->dict = $app->entity($conf[$app->conf['lang']]);

		$this->path = $this->app->read($_GET, 'id', '/');
	}

	public function __invoke($name)
	{
		echo $this->dict->$name;
	}

	public function get_content()
	{
		$this->object = $this->model->readNodeByPath($this->path);

		if(empty($this->object))
		{
			$this->object = $this->app->entity();
		}

		return $this->object;
	}

	public function init()
	{
		if($this->content = $this->model->readByPath(
				$this->app->request, $this->app->conf['lang']))
		{
			return $this;
		}
		else
		{
			$this->app->notfound();

			$this->content = $this->model->readByPath(
				'', $this->app->conf['lang']);

			return $this;
		}
	}

	public function set($array = array())
	{
		$this->content = $this->app->entity($array);
	}

	public function fetch($name, $value)
	{
		$this->extract[$name] = $value;
	}


	public function process()
	{
		$root = $this->app->root.$this->app->conf['main_root'];

		if($node = $this->app->readEntity($_POST, 'node'))
		{
			if($this->app->read($_POST, 'update'))
			{

			}

			if($this->app->read($_POST, 'delete'))
			{

			}
		}

		$this->node($this->object);
	}

	public function node($object)
	{
		$views = $this->app->conf['views'];
?>
<div id="node" class="comp">
<div id="<?php $this('id'); ?>" class="comp_header">
 <h1 id="node_h1"><?php $object->title(); ?></h1>
 <h2><a id="node_button" accesskey="1" title="<?php $this('main_title'); ?>" href="?id=<?php echo $this->path; ?>#<?php $this('id'); ?>"><?php $this('main_title'); ?></a></h2>
 <p id="node_p"><?php $object->description(); ?></p>
</div>
<div class="comp_body">
<form method="post" id="node_body" spellcheck="false">
<?php
		$this->script();
?>
<ol>
 <li>
  <label for="node_title"><?php $this('title'); ?></label>
  <input type="text" id="node_title" name="node[title]" class="text" value="<?php $object->title(); ?>" />
 </li>
 <li>
  <label for="node_desc"><?php $this('description'); ?></label>
  <input type="text" id="node_desc" name="node[description]" class="text" value="<?php $object->description(); ?>" />
 </li>
 <li>
 <label for="node_name"><?php $this('name'); ?></label>
  <input type="text" id="node_name" name="node[name]" class="text" value="<?php $object->name(); ?>" />
 </li>
 <li>
  <label for="node_content"><?php $this('content'); ?></label>
  <textarea id="node_content" name="node[content]" title="<?php $this('content_title'); ?>"><?php $object->content(); ?></textarea>
 </li>
<?php if($object->path != '/'): ?>
 <li>
  <label for="node_dir"><?php $this('dir'); ?></label>
  <input type="text" id="node_dir" name="node[dir]" class="text" value="<?php $object->dir(); ?>" />
 </li>
 <li>
  <label for="node_type"><?php $this('type'); ?></label>
  <select id="node_type" name="node[type]">
<?php foreach($views as $id): ?>
   <option value="<?php echo $id; ?>"<?php if($object->type == $id): ?> selected<?php endif; ?>><?php $this($id); ?></option>
<?php endforeach; ?>
  </select>
 </li>
 <li>
  <label for="node_pos"><?php $this('pos'); ?></label>
  <input type="text" id="node_pos" name="node[pos]" class="text" value="<?php $object->pos(); ?>" />
 </li>
 <li>
  <label for="node_public"><?php $this('public'); ?></label>
  <input type="hidden" value="0" name="node[public]" />
  <input id="node_public" class="checkbox" type="checkbox" value="1" name="node[public]"<?php if($object->public): ?> checked<?php endif; ?> />
 </li>
<?php endif; ?>
</ol>
 <input type="hidden" name="node[path]" value="<?php $this->app->out($this->path); ?>" />
 <input type="submit" class="submit_update" name="update" onclick="return alert('<?php $this('update_alert'); ?>')" value="<?php $this('update'); ?>" />
<?php if($object->id and $object->path != '/'): ?>
 <input type="submit" class="submit_delete" name="delete" onclick="return alert('<?php $this('delete_alert'); ?>')" value="<?php $this('delete'); ?>" />
<?php endif; ?>
</form>
</div>
</div>
<?php
	}

	public function script()
	{
?>
<script>
	if(node_body = document.getElementById('node_body'))
	{
		node_body.className = 'disabled';
	}
</script>
<?php
	}
}

}

?>
