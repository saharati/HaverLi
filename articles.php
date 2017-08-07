<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - מאמרים';
$page_description = "עמותת חבר לי מזמינה אתכם לעיין במאמרים על החברים הכי טובים שלכם בנושאים שונים ומגוונים.";
$page_url = 'http://imutz.org/articles';
$page_image = 'http://imutz.org/images/og/articles.jpg';
$page_image_width = 1200;
$page_image_height = 630;
require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="fullwidth">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT id, image, imageName, imageCaption FROM article ORDER BY imageOrder');
while ($row = $result->fetch_assoc())
{
	echo '<div class="rectDiv clearfix">
<img src="/images/articles/' . $row['image'] . '" title="' . $row['imageName'] . '" alt="' . $row['imageName'] . '">
<h3>' . $row['imageName'] . '</h3>';
	if (mb_strpos($row['imageCaption'], '</span>') !== false)
	{
		$caption = explode('</span>', $row['imageCaption']);
		$text = '';
		foreach ($caption as $c)
		{
			if (mb_strlen($c) > 300)
				$c = mb_substr($c, 0, 301);
			$text .= $c;
			if (mb_strlen($text) > 300)
			{
				$text .= '...</span>';
				break;
			}
			$text .= '</span>';
		}
		$doc = new DOMDocument();
		$doc->loadHTML(mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8'));
		$text = $doc->saveHTML($doc->getElementsByTagName('p')->item(0));
	}
	else
		$text = $row['imageCaption'];
	echo $text . '
<p class="lastp"><a href="/article-' . $row['id'] . '">למאמר המלא לחץ כאן</a></p>
</div>';
}
$result->free();
?>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>