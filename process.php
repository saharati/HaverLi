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
$result = $mysqli->query('SELECT * FROM process');
$row = $result->fetch_assoc();
$result->free();
if ($row)
{
	echo '<div class="innerDiv">' . $row['description'] . '</div>
<div class="spaceDiv"></div>
<div class="innerDiv">';
	if (empty($row['imageLink']))
		echo '<img src="/images/pages/' . $row['image'] . '" alt="">';
	else
		echo '<a href="' . $row['imageLink'] . '"><img src="/images/pages/' . $row['image'] . '" alt=""></a>';
	echo '</div>
<div class="spaceDiv"></div>';
}
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