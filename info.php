<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php'; ?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="about fullwidth">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT title, caption, image FROM info ORDER BY position');
while ($row = $result->fetch_assoc())
	echo '<div class="innerDiv"><h2>' . $row['title'] . '</h2>' . $row['caption'] . '</div><div class="spaceDiv"></div><div class="innerDiv"><img src="/images/pages/' . $row['image'] . '" alt=""></div><div class="spaceDiv"></div>';
$result->free();
?>
<div class="innerDiv">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/contact.php'; ?>
</div>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>