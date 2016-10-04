enable_disable('images_button', 'images_body');

var inputs = document.querySelectorAll('.file');

Array.prototype.forEach.call(inputs, function(input)
{
	var label = input.previousElementSibling;
	var labelVal = label.innerHTML;

	input.addEventListener('change', function(e)
	{
		var fileName = '';
		if(this.files && this.files.length > 1)
		{
			fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
		}
		else
		{
			fileName = e.target.value.split('\\').pop();
		}

		if(fileName)
		{
			label.innerHTML = fileName;
		}
		else
		{
			label.innerHTML = labelVal;
		}
	});
});

if(img = document.getElementById('img'))
{
	if(img_label = document.getElementById('img_label'))
	{
		img.addEventListener('focus', function(e)
		{
			img_label.className = 'focus';
		});

		img.addEventListener('blur', function(e)
		{
			img_label.className = '';
		});
	}
}

