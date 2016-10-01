<?php

namespace comps\nodes
{

class nodes_model
{
	public $conf;

	public function __construct($app_model)
	{
		$this->app_model = $app_model;

		$this->conf = $app_model->readJSON(
			__DIR__.'/nodes.json');
	}

	public function readNodesByPath($path, $root)
	{
		$c = $this->conf;

		// CONCAT('$root', content_cs.path, '$c[dir1x]', images.filename) AS img_1x
		// AND path REGEXP '^$path.[-a-z0-9]+/$'

		$query = $this->app_model->prepare("
			SELECT content_cs.path, content_cs.name, images.alt as img_alt
				
			FROM content_cs
				LEFT JOIN images ON content_cs.id=images.content_id 
			WHERE path!='$path' AND path!='/'
				AND content_cs.deleted IS NULL
				AND (images.content_id IS NULL
				OR images.enabled)
			GROUP BY content_cs.id
			ORDER BY content_cs.pos ASC, content_cs.updated DESC",
			array($path, $path));

		return $this->app_model->query($query);
	}
}

}

?>
