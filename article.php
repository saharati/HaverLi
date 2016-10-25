<?php
if (!isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] < 0)
{
	header('Location: /notfound');
	exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$result = $mysqli->query('SELECT image, imageName, imageCaption FROM article WHERE id=' . $_GET['page']);
$row = $result->fetch_assoc();
$result->free();
if (!$row)
{
	header('Location: /notfound');
	exit;
}
?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'מאמרים - ' . $row['imageName'];
$page_description = str_replace(array('<br>', "\r", "\n"), array(' ', '', ''), $row['imageCaption']);
$page_url = 'http://v2.imutz.org/article-' . $_GET['page'];
$page_image = 'http://v2.imutz.org/images/articles/' . $row['image'];
list($page_image_width, $page_image_height) = getimagesize($page_image);
require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="article fullwidth">
<div id="contentInner">
<?php
echo '<a class="imageModal" href="/images/articles/' . $row['image'] . '" title="' . $row['imageName'] . '" data-width="' . $page_image_width . '" data-height="' . $page_image_height . '"><img class="floated" src="/images/articles/' . $row['image'] . '" title="' . $row['imageName'] . '" alt="' . $row['imageName'] . '"></a>
<h2>' . $row['imageName'] . '</h2>
' . $row['imageCaption'] . '
<p class="lastp"><a href="/articles">חזרה למאמרים</a></p>';
?>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>