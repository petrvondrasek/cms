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
		$query = "
			SELECT content_admin.*
			FROM content_admin
			WHERE content_admin.path='$path' AND deleted IS NULL
			ORDER BY path DESC, id ASC";

		return $this->app_model->query_one($query);
	}

	public function readNodeByPath($path)
	{
		// DATE_FORMAT(updated, '%%e. %%c. %%Y %%H:%%i') as updated1

		$query = $this->app_model->prepare("
			SELECT *,
				updated as updated1
			FROM content_cs
			WHERE path='%s'",
			array($path));

		return $this->app_model->query_one($query);
	}

	public function updateNode()
	{

	}

	public function deleteNode()
	{

	}
}

}

?>
