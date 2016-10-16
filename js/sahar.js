swal.setDefaults({confirmButtonText: 'אישור'});

window.onload = function()
{
	var modals = document.getElementsByClassName('imageModal');
	if (modals.length > 0)
	{
		var imageModal = document.createElement('div');
		var image = document.createElement('img');
		var link = document.createElement('a');
		var italic = document.createElement('i');
		
		imageModal.setAttribute('id', 'imageModal');
		imageModal.setAttribute('onclick', 'this.style.display=\'none\'');
		image.setAttribute('title', 'לחץ לסגירת התמונה');
		image.setAttribute('alt', 'לחץ לסגירת התמונה');
		link.setAttribute('href', 'javascript:void(0);');
		link.setAttribute('title', 'סגור');
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
	
	var labels = document.getElementsByClassName('special-label');
	var selects = document.getElementsByClassName('special-select');
	function PetPalList(labelElem)
	{
		var obj = this;
		
		if (typeof labelElem === 'string')
		{
			this.label = null;
			this.list = document.getElementById(labelElem);
		}
		else
		{
			this.label = labelElem;
			this.list = document.getElementById(this.label.getAttribute('for'));
		}
		this.id = this.list.id;
		this.div = this.list.getElementsByTagName('div')[0];
		this.defaultString = this.div.innerHTML;
		this.dropdown = this.list.getElementsByClassName('dropdown')[0];
		this.options = this.dropdown.getElementsByTagName('li');
		
		this.labels = new Array(this.options.length);
		this.checkboxes = new Array(this.options.length);
		for (j = 0;j < this.options.length;j++)
		{
			this.labels[j] = this.options[j].getElementsByTagName('label')[0];
			this.checkboxes[j] = this.options[j].getElementsByTagName('input')[0];
		}
		
		this.resetCheckboxes =  function()
		{
			for (j = 0;j < this.checkboxes.length;j++)
				this.checkboxes[j].checked = false;
			
			this.div.innerHTML = this.defaultString;
		}
		this.changeContent = function()
		{
			var string = '';
			for (i = 0;i < this.options.length;i++)
			{
				if (this.checkboxes[i].checked)
				{
					if (string == '')
						string = this.labels[i].innerHTML;
					else
						string += ', ' + this.labels[i].innerHTML;
				}
			}
			
			if (string == '')
				this.div.innerHTML = this.defaultString;
			else
				this.div.innerHTML = string;
		}
		this.toggleStyle = function(event)
		{
			if (this.list.className.includes('active'))
				this.list.className = 'special-select';
			else
				this.list.className = 'special-select active';
			
			for (i = 0;i < selects.length;i++)
				if (selects[i] != this.list)
					selects[i].className = 'special-select';
			
			event.stopPropagation();
		}
		if (this.label != null)
		{
			this.label.onclick = function(event)
			{
				obj.toggleStyle(event);
			}
		}
		this.list.onclick = function(event)
		{
			obj.toggleStyle(event);
		}
		this.dropdown.onclick = function(event)
		{
			obj.changeContent();
			
			event.stopPropagation();
		}
	}
	document.onclick = function()
	{
		for (i = 0;i < selects.length;i++)
			selects[i].className = 'special-select';
	}
	var lists = [];
	for (i = 0;i < labels.length;i++)
	{
		var petlist = new PetPalList(labels[i]);
		lists[petlist.id] = petlist;
	}
	
	var saharSwal = document.getElementsByClassName('sweet-alert')[0];
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