<?php

namespace comps\content
{

class content_model
{
	public function __construct($app_model)
	{
		$this->app_model = $app_model;
	}

	public function readByPath($path, $lang = 'cs')
	{
		$query = $this->app_model->sqlite_prepare("
			SELECT *
			FROM content_admin
			WHERE content_admin.path='%s' AND deleted IS NULL
			ORDER BY path DESC, id ASC",
			array($path));

		return $this->app_model->sqlite_query_one($query);
	}

	public function readNodeByPath($path)
	{
		// DATE_FORMAT(updated, '%%e. %%c. %%Y %%H:%%i') as updated1

		$query = $this->app_model->sqlite_prepare("
			SELECT *,
				updated as updated1
			FROM content_cs
			WHERE path='%s'",
			array($path));

		return $this->app_model->sqlite_query_one($query);
	}
}

}

?>
