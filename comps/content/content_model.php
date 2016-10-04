<?php

namespace comps\content
{

class content_model
{
	public $conf;

	public function __construct($app_model)
	{
		$this->app_model = $app_model;

		$this->conf = $app_model->readJSON(
			__DIR__.'/content.json');
	}

	public function readByPath($path)
	{
		$query = $this->app_model->sqlite_prepare("
			SELECT *
			FROM content_admin
			WHERE content_admin.path='%s' AND deleted IS NULL
			ORDER BY path DESC, id ASC",
			array($path));

		return $this->app_model->sqlite_query_one($query);
	}

	public function readNodeByPath($path, $lang)
	{
		// DATE_FORMAT(updated, '%%e. %%c. %%Y %%H:%%i') as updated1
		$content = 'content_'.$lang;

		$query = $this->app_model->sqlite_prepare("
			SELECT *,
				updated AS updated1
			FROM $content
			WHERE path='%s'",
			array($path));

		return $this->app_model->sqlite_query_one($query);
	}
}

}

?>
