<?php

namespace comps\footer
{

class footer
{
	public function __construct($app)
	{
		$this->app = $app;

		$conf = $app->model->readJSON(
			__DIR__.'/footer.json');

		$this->dict = $app->entity($conf[$app->conf['lang']]);
	}

	public function __invoke($name)
	{
		echo $this->dict->$name;
	}

	public function process($user = false)
	{
?>
<footer id="footer">
<?php if($user or $user = $this->app->read($_SESSION, 'admin')): ?>
 <p class="center"><strong><?php $user->role(); ?></strong>, <?php $user->email(); ?></p>
 <p id="logout"><a href="<?php $this('logout_path'); ?>"><?php $this('logout'); ?></a></p>
<?php endif; ?>
</footer>
<?php
	}
}

}

?>
