<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
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