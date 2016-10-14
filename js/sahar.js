window.onload = function()
{
	saharSwal = document.getElementsByClassName('sweet-alert')[0];
	if (saharSwal)
	{
		swal.setDefaults({confirmButtonText: 'אישור'});
		window.onresize = function()
		{
			saharSwal.style.marginTop = -Math.round(saharSwal.clientHeight / 2) + 'px';
		}
	}
	
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
			modals[i].onclick = function()
			{
				image.src = this.firstChild.src;
				imageModal.style.display = 'block';
			}
		}
	}
}