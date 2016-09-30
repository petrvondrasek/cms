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

		// CONCAT('$root', '$path', '$c[dir1x]', filename) AS img1x,
		// CONCAT('$root', '$path', '$c[dir2x]', filename) AS img2x

		$query = "
			SELECT images.id, alt, filename, path
			FROM images
				LEFT JOIN content_cs ON images.content_id=content_cs.id	
			WHERE content_cs.path='$path'
				AND enabled=1
			ORDER BY images.updated DESC";

		return $this->app_model->query($query);
	}
}

}

?>
