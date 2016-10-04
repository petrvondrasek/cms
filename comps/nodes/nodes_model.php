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

	public function readNodesByPath($path, $root, $lang)
	{
		$c = $this->conf;

		$content = 'content_'.$lang;

		// CONCAT('$root', $content.path, '$c[dir1x]', images.filename) AS img_1x
		// AND path REGEXP '^$path.[-a-z0-9]+/$'

		$query = $this->app_model->sqlite_prepare("
			SELECT $content.path, $content.name, images.alt as img_alt,
				'$root' || $content.path || '$c[dir1x]' || images.filename AS img_1x
				
			FROM $content
				LEFT JOIN images ON $content.id=images.content_id
			WHERE path!='$path' AND path!='/' AND path LIKE '$path%%'
				AND $content.deleted IS NULL
				AND (images.content_id IS NULL
					OR images.enabled)
			GROUP BY $content.id
			ORDER BY $content.pos ASC, $content.updated DESC",
			array($path));

		return $this->app_model->sqlite_query($query);
	}
}

}

?>
