<?php
$metaData = $mysqli->query('SELECT title, description, url, image FROM promote WHERE page="default"');
$md = $metaData->fetch_assoc();
$metaData->free();
if (empty($page_title))
	$page_title = htmlspecialchars($md['title'], ENT_QUOTES);
if (empty($page_description))
	$page_description = htmlspecialchars(str_replace(array("\r", "\n"), array('', ' '), $md['description']), ENT_QUOTES);
if (empty($page_url))
	$page_url = $md['url'];
if (empty($page_image) && !empty($md['image']))
{
	$page_image = 'http://imutz.org/images/og/' . $md['image'];
	list($page_image_width, $page_image_height) = getimagesize($page_image);
}
?>
<head>
<meta charset="UTF-8">
<title><?php echo $page_title; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="keywords" content="חבר לי,אימוץ,כלבים,חתולים,חיפוש,מאגר,עמותה,מסירה,בעלי חיים,חיות מחמד,גורים,מאמרים,סרטונים,אירועים,ימי אימוץ">
<meta name="description" content="<?php echo $page_description; ?>">
<meta property="og:site_name" content="עמותת חבר לי - לחברים עוזרים תמיד">
<meta property="og:type" content="Website">
<meta property="og:locale" content="he_IL">
<meta property="og:title" content="<?php echo $page_title; ?>">
<meta property="og:description" content="<?php echo $page_description; ?>">
<meta property="og:image" content="<?php echo $page_image; ?>">
<meta property="og:image:width" content="<?php echo $page_image_width; ?>">
<meta property="og:image:height" content="<?php echo $page_image_height; ?>">
<meta property="og:url" content="<?php echo $page_url; ?>">
<link rel="canonical" href="<?php echo $page_url; ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="/css/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script src="/js/tinymce/placeholder.js"></script>
<script src="/js/fitvids.js"></script>
<script src="/js/bxslider.js"></script>
<script src="/js/sorttable.js"></script>
<script src="/js/sweetalert.js"></script>
<script src="/js/fine-uploader.js"></script>
<script src="/js/sahar.js"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-101863642-1', 'auto');
ga('send', 'pageview');
</script>
</head>