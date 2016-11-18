<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - משפחות מאושרות';
$page_description = "סיפורם האישי של משפחות שאימצו מעמותת חבר לי, חוויות והמלצות.";
$page_url = 'http://imutz.org/adopted';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="about adopted fullwidth">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT image, imageCaption FROM family ORDER BY imageOrder');
for ($i = 0;$row = $result->fetch_assoc();$i++)
{
	echo '<div class="clearfix innerDiv">
<div class="right">
<img src="/images/families/' . $row['image'] . '" alt="">
</div>
<div class="left">
' . $row['imageCaption'] . '
</div>
</div>';
if ($i + 1 < $result->num_rows)
	echo '<div class="spaceDiv"></div>';
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