<?php

namespace comps\images
{

class images_model
{
	public $conf;

	public function __construct($app_model)
	{
		$this->app_model = $app_model;

		$this->conf = $app_model->readJSON(
			__DIR__.'/images.json');
	}

	public function readImagesByPath($path, $root)
	{
		$c = $this->conf;

		// MYSQL - CONCAT

		$query = $this->app_model->sqlite_prepare("
			SELECT images.id, alt, filename, path,
				'$root' || '$path' || '$c[dir1x]' || filename AS img1x,
				'$root' || '$path' || '$c[dir2x]' || filename AS img2x

			FROM images
				LEFT JOIN content_cs ON images.content_id=content_cs.id	
			WHERE content_cs.path='%s'
				AND enabled=1
			ORDER BY images.updated DESC",
			array($path));

		return $this->app_model->sqlite_query($query);
	}
}

}

?>
