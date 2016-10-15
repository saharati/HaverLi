swal.setDefaults({confirmButtonText: 'אישור'});

window.onload = function()
{
	modals = document.getElementsByClassName('imageModal');
	if (modals.length > 0)
	{
		imageModal = document.createElement('div');
		imageModal.setAttribute('id', 'imageModal');
		imageModal.setAttribute('onclick', 'this.style.display=\'none\'');
		image = document.createElement('img');
		image.setAttribute('title', 'לחץ לסגירת התמונה');
		image.setAttribute('alt', 'לחץ לסגירת התמונה');
		link = document.createElement('a');
		link.setAttribute('href', 'javascript:void(0);');
		link.setAttribute('title', 'סגור');
		italic = document.createElement('i');
		italic.setAttribute('class', 'fa fa-times');
		italic.setAttribute('aria-hidden', 'true');
		
		link.appendChild(italic);
		imageModal.appendChild(image);
		imageModal.appendChild(link);
		document.body.appendChild(imageModal);
		
		for (i = 0;i < modals.length;i++)
		{
			modals[i].onclick = function(e)
			{
				e.preventDefault();
				
				image.src = this.href;
				
				if (this.getAttribute('data-height') < window.innerHeight)
					image.style.top = ((window.innerHeight - this.getAttribute('data-height')) / 2) + 'px';
				else
					image.style.top = '0';
				
				imageModal.style.display = 'block';
			}
		}
	}
	
	saharSwal = document.getElementsByClassName('sweet-alert')[0];
	window.onresize = function()
	{
		if (saharSwal && saharSwal.style.display == 'inline-block')
			saharSwal.style.marginTop = -Math.round(saharSwal.offsetHeight / 2) + 'px';
		else if (modals.length > 0 && imageModal.style.display == 'block')
		{
			if (image.offsetHeight < window.innerHeight)
				image.style.top = ((window.innerHeight - image.offsetHeight) / 2) + 'px';
			else
				image.style.top = '0';
		}
	}
}