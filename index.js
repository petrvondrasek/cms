window.onload = function()
{
<?php
		foreach($this->app->conf['comps'] as $comp)
		{
			$file = 'comps'.'/'.$comp.'/'.$comp.'.js';

			if(file_exists($file))
				include($file);
		}
?>
};

