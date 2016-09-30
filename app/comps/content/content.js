activate('node_button', 'node_body', 'page');

if(node_content = document.getElementById('node_content'))
{
	var default_height = parseInt(window.getComputedStyle(node_content).height, 10);

	node_content.addEventListener('focus', function(e)
	{
		height = (this.scrollHeight > this.clientHeight) ? (this.scrollHeight) : default_height;
		this.style.height = (height+2)+"px";

		e.stopPropagation();
	});

	node_content.addEventListener('blur', function(e)
	{
		this.style.height = default_height+"px";

		e.stopPropagation();
	});
}

if(node_title = document.getElementById('node_title'))
{
	if(h1 = document.getElementById('node_h1'))
	{
		node_title.addEventListener('keyup', function(e)
		{
			h1.innerHTML = node_title.value;
		});
	}
}

if(node_desc = document.getElementById('node_desc'))
{
	if(p = document.getElementById('node_p'))
	{
		node_desc.addEventListener('keyup', function(e)
		{
			p.innerHTML = node_desc.value;
		});
	}
}

