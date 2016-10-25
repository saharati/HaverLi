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
$result = $mysqli->query('SELECT description FROM lost');
$row = $result->fetch_assoc();
$result->free();
if ($row)
	echo $row['description'];
$result = $mysqli->query('SELECT image, imageName, imageCaption FROM lost_step ORDER BY imageOrder');
while ($row = $result->fetch_assoc())
	echo '<div class="rectDiv clearfix"><img src="/images/lost/' . $row['image'] . '" title="' . $row['imageName'] . '" alt="' . $row['imageName'] . '"><h3>' . $row['imageName'] . '</h3>' . $row['imageCaption'] . '</div>';
$result->free();
?>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>