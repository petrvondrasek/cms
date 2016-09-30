function activate(trigger_id, block_id, scroll_to)
{
	var trigger, block;

	var show = false;

	if(trigger = document.getElementById(trigger_id))
	{
		if(block = document.getElementById(block_id))
		{
			trigger.addEventListener('click', function(e)
			{
				if(show = !show)
				{
					block.className = 'enabled';
				}
				else
				{
					block.className = 'disabled';
				}

				window.scroll(0,findPos(document.getElementById(scroll_to)));

			}, false);
		}
	}		
}

if(typeof document.getElementsByClassName !== 'function')
{
	document.getElementsByClassName = function(search)
	{
		var d = document, elements, pattern, i, results = [];
		if(d.querySelectorAll)
		{
			return d.querySelectorAll("." + search);
		}

		if(d.evaluate)
		{
			pattern = ".//*[contains(concat(' ', @class, ' '), ' " + search + " ')]";
			elements = d.evaluate(pattern, d, null, 0, null);
			while((i = elements.iterateNext()))
			{
				results.push(i);
			}
		}
		else
		{
			elements = d.getElementsByTagName("*");
			pattern = new RegExp("(^|\\s)" + search + "(\\s|$)");
			for(i = 0; i < elements.length; i++)
			{
				if(pattern.test(elements[i].className))
				{
					results.push(elements[i]);
				}
			}
		}
		return results;
	}
}

function findPos(obj)
{
    var curtop = 0;
    if (obj.offsetParent)
	{
        do
		{
        	curtop += obj.offsetTop;
        }
		while (obj = obj.offsetParent);
		return [curtop];
    }
}

var imagesLoaded = 0;

var elements = document.getElementsByTagName('noscript');

for(var i=0; i<elements.length; i++)
{
	var img = new Image();
	img.className = 'defer';

	elements[i].parentNode.insertBefore(img, elements[i]);

	img.onload = function()
	{
		this.className += ' loaded';

		if(++imagesLoaded == elements.length)
		{
			var links = document.getElementsByClassName('prefetch');
			for(var i = 0; i < links.length; i++)
			{
				var link = document.createElement("link");
				link.rel = "prefetch";
				link.href = links[i].getAttribute('href');
				document.getElementsByTagName("head")[0].appendChild(link);
			}
		}
	};
	img.alt = elements[i].getAttribute('data-alt');
	var srcset = elements[i].getAttribute('data-srcset');
	if(srcset)
		img.srcset = srcset;
	img.src = elements[i].getAttribute('data-src');
}

