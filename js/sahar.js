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
$(document).ready(function()
{
	width = window.innerWidth;
	
	var slider = $('.bxslider');
	if (slider)
	{
		slider.bxSlider
		(
			{
				slideWidth: 290,
				minSlides: 2,
				maxSlides: 3,
				moveSlides: 3,
				slideMargin: 15,
				captions: true,
				pager: false,
				nextText: 'הבא',
				prevText: 'הקודם',
				auto: true,
				pause: 6000,
				autoHover: true,
				shrinkItems: true
			}
		);
	}
});
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