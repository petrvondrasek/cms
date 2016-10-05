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

	public function readImagesByPath($path, $root, $lang)
	{
		$c = $this->conf;

		$content = 'content_'.$lang;

		// MYSQL - CONCAT

		$query = $this->app_model->sqlite_prepare("
			SELECT images.id, alt, filename, path,
				'$root' || '/images/' || '$c[dir1x]' || filename AS img1x,
				'$root' || '/images/' || '$c[dir2x]' || filename AS img2x

			FROM images
				LEFT JOIN $content ON images.content_id=$content.id	
			WHERE $content.path=%s
				AND enabled=1
			ORDER BY images.updated DESC",
			array($path));

		return $this->app_model->sqlite_query($query);
	}
}

}

?>
