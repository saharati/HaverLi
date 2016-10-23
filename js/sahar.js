// SWAL OBJECT ALWAYS PRESENT.
swal.setDefaults({confirmButtonText: 'אישור'});
// RICH TEXT EDITORS.
tinymce.init({
	selector: 'textarea.tinymce',
	directionality: 'rtl',
	language: 'he_IL',
	language_url: '/js/tinymce/he_IL.js',
	content_css: '/css/editor.css',
	plugins: ['link', 'textcolor', 'placeholder', 'image'],
	toolbar: 'undo redo | styleselect | bold italic underline forecolor fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	image_list: '/private/ajax/imagelist.php',
	fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
	min_height: 200,
	setup: function(editor){
		editor.on('init', function(args){
			editor = args.target;
	        editor.on('NodeChange', function(e){
	        	if (e && e.element.nodeName.toLowerCase() == 'img'){
	        		width = e.element.width;
	        		height = e.element.height;
	        		tinyMCE.DOM.setAttribs(e.element, {'width': null, 'height': null});
	        		tinyMCE.DOM.setAttribs(e.element, {'style': 'width:' + width + 'px; height:' + height + 'px;vertical-align:baseline'});
	        	}
	        });
		});
	}
});
// SWITCH BETWEEN MOBILE AND DESKTOP MENUS.
function switchContent()
{
	var link = document.getElementById('submenuLink').getElementsByTagName('a')[0];
	var main = document.getElementsByTagName('main')[0];
	var menu = main.getElementsByTagName('nav')[0];
	var content = document.getElementById('content');
	if (menu.style.display == 'block')
	{
		link.className = '';
		menu.style.display = 'none';
		content.style.display = 'table';
	}
	else
	{
		link.className = 'active';
		menu.style.display = 'block';
		content.style.display = 'none';
	}
}
// SHOW/HIDE MOBILE MENU SUB LISTS.
function toggleView(elem)
{
	var list = elem.getElementsByTagName('ul')[0];
	if (list.className == 'activeList')
		list.className = '';
	else
		list.className = 'activeList';
}
// TOGGLE BETWEEN IMAGE SRC ON HOVER.
function toggleSrc(elem)
{
	var src = elem.getAttribute('src');
	var dataSrc = elem.getAttribute('data-src');
	
	elem.setAttribute('src', dataSrc);
	elem.setAttribute('data-src', src);
	
	if (elem.hasAttribute('data-style'))
	{
		var style = elem.getAttribute('style');
		var dataStyle = elem.getAttribute('data-style');
		
		elem.setAttribute('style', dataStyle);
		elem.setAttribute('data-style', style);
	}
}
// FUNCTIONS TO RUN WHEN DOCUMENT LOADS.
$(document).ready(function()
{
	// WIDTH USED TO CLOSE MOBILE MENU IF WIDTH CHANGES DURING A RESIZE.
	var width = window.innerWidth;
	// SWAL POPUP, IF ANY.
	var saharSwal = document.getElementsByClassName('sweet-alert')[0];
	
	// INITIALIZE MODALS, IF ANY.
	var modals = document.getElementsByClassName('imageModal');
	if (modals.length > 0)
	{
		var imageModal = document.createElement('div');
		var image = document.createElement('img');
		var link = document.createElement('a');
		
		imageModal.setAttribute('id', 'imageModal');
		imageModal.setAttribute('onclick', 'this.style.display=\'none\'');
		image.setAttribute('title', 'לחץ לסגירת התמונה');
		image.setAttribute('alt', 'לחץ לסגירת התמונה');
		link.setAttribute('href', 'javascript:void(0);');
		link.setAttribute('title', 'סגור');
		link.innerHTML = 'X';
		
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
	
	// FUNCTIONS TO RUN WHEN DOCUMENT RESIZES.
	window.onresize = function()
	{
		// MAKE CHANGES TO SWAL AND MODALS ACCORDING TO SCREEN CHANGES.
		if (saharSwal && saharSwal.style.display == 'inline-block')
			saharSwal.style.marginTop = -Math.round(saharSwal.offsetHeight / 2) + 'px';
		else if (modals.length > 0 && imageModal.style.display == 'block')
		{
			if (image.offsetHeight < window.innerHeight)
				image.style.top = ((window.innerHeight - image.offsetHeight) / 2) + 'px';
			else
				image.style.top = '0';
		}
		
		// IF WIDTH HASN'T CHANGED, IGNORE.
		if (width == window.innerWidth)
			return;
		
		// HIDE MOBILE MENU UPON WIDTH RESIZE.
		var main = document.getElementsByTagName('main')[0];
		var menu = main.getElementsByTagName('nav')[0];
		if (menu.style.display == 'block')
		{
			var link = document.getElementById('submenuLink').getElementsByTagName('a')[0];
			var content = document.getElementById('content');
			
			link.className = '';
			menu.style.display = 'none';
			content.style.display = 'table';
		}
		
		// KEEP TRACK OF NEW WIDTH.
		width = window.innerWidth;
	}
});