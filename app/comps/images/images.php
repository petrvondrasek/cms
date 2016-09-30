<?php

namespace comps\images
{

class images
{
	public function __construct($app)
	{
		$this->app = $app;

		$this->model = new images_model($app->model);

		$this->dict = $app->entity($this->model->conf[$app->conf['lang']]);
	}

	public function __invoke($name)
	{
		echo $this->dict->$name;
	}

	public function init() {}

	public function process()
	{
		$sizes = $this->model->conf['img_sizes'];

		$root = $_SERVER['DOCUMENT_ROOT'].$this->app->conf['main_root'];

		$path = $this->app->read($_GET, 'id', '/');

		if($img = $this->app->readEntity($_POST, 'img'))
		{
			if($this->app->read($_POST, 'delete'))
			{
				// delete image
			}

			$this->app->redirect();
		}

		if($img = $this->app->readEntity($_FILES, 'img') and $img->error == 0)
		{
			// upload image

			$this->app->redirect();
		}

		$this->images();
	}

	protected function images()
	{
		$path = $this->app->read($_GET, 'id', '/');

		$images = $this->model->readImagesByPath(
			$path, $this->app->conf['main_url']);
?>
<div id="images" class="comp">
<div id="<?php $this('id'); ?>" class="comp_header">
 <h2><a id="images_button" title="<?php $this('title'); ?>" href="?id=<?php echo $path; ?>#<?php $this('id'); ?>"><?php $this('title'); ?></a></h2>
<?php
		$this->add_image();
?>
</div>
<div class="comp_body">
 <div id="images_body">
<?php
		$this->script();
?>
<?php foreach($images as $object): ?>
  <a class="img prefetch" href="<?php $object->img2x(); ?>">
   <noscript data-src="<?php $object->img2x(); ?>"<?php if(1): ?> data-srcset="<?php $object->img1x(); ?> 1x, <?php $object->img2x(); ?> 2x"<?php endif; ?> data-alt="<?php $object->alt(); ?>"><img src="<?php $object->img1x(); ?>" alt="<?php $object->alt(); ?>" width="32" height="32" />
   </noscript>
  </a>
<p><?php $object->filename(); ?></p>
<?php
		$this->edit_image($object);
?>
<?php endforeach; ?>
 </div>
</div>
</div>
<?php
	}

	public function add_image()
	{
?>
<form class="upload" method="post" enctype="multipart/form-data">
 <ol>
  <li>
   <label for="img"><?php $this('add_image'); ?></label>
   <input type="file" id="img" name="img" class="file" data-multiple-caption="{count} <?php $this('id'); ?>" accept="image/jpeg" />
  </li>
 </ol>
 <input type="submit" class="submit" value="<?php $this('upload'); ?>" />
</form>
<?php
	}

	public function script()
	{
?>
<script>
	if(images_body = document.getElementById('images_body'))
	{
		images_body.className = 'disabled';
	}
</script>
<?php
	}
}

}

?>
