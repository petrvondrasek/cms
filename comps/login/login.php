<?php

namespace comps\login
{

class login
{
	public function __construct($app)
	{
		$this->app = $app;

		$conf = $app->model->readJSON(
			__DIR__.'/login.json');

		$this->model = new login_model($app->model);

		$this->dict = $app->entity($conf[$app->conf['lang']]);
	}

	public function __invoke($name)
	{
		echo $this->dict->$name;
	}

	public function process()
	{
		if($this->app->read($_GET, 'logout'))
		{
			$_SESSION['admin'] = false;
			$_SESSION['role'] = false;
			$this->app->redirect('/');
		}

		if($login_data = $this->app->read($_POST, 'login_data'))
		{
			if($user = $this->model->readUserByLogin(
				$login_data['name'], sha1($login_data['password'])))
			{

				$_SESSION['admin'] = $user;
				$_SESSION['role'] = 'admin';
			}

			$this->app->redirect();
		}

		$this->login();
	}

	public function login()
	{
?>
<?php if($this->app->read($_SESSION, 'admin') == false): ?>
<form id="login" method="post" class="comp">
<h2><?php $this('login_h1'); ?></h2>
<ol>
 <li>
  <label for="name"><?php $this('name'); ?></label>
  <input id="name" name="login_data[name]" class="text" type="text" />
 </li>
 <li>
  <label for="passwd"><?php $this('password'); ?></label>
  <input id="passwd" name="login_data[password]" class="text" type="password" />
 </li>
</ol>
 <input type="submit" class="submit" id="login-submit" name="actions[login]" value="<?php $this('login'); ?>" />
</form>
<?php endif; ?>
<?php
	}
}

}

?>
