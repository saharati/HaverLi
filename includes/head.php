<?php
if (!isset($page_title))
	$page_title = 'עמותת חבר לי - לחברים עוזרים תמיד';
if (!isset($page_description))
	$page_description = "ברוכים הבאים לעמותת חבר לי. \r\nכאן תמצאו כלבים וחתולים לאימוץ מכל הגדלים והמינים, גורים ובוגרים, מידע למאמץ, מאמרים והמלצות.";
if (!isset($page_url))
	$page_url = 'http://imutz.org/';
if (!isset($page_image))
{
	$page_image = 'http://imutz.org/images/logo.jpg';
	$page_image_width = 960;
	$page_image_height = 960;
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
</head>