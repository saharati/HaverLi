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
var width;
window.onload = function()
{
	width = window.innerWidth;
}
window.onresize = function()
{
	if (width == window.innerWidth)
		return;
	
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
	
	width = window.innerWidth;
}