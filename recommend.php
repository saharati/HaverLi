<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - המומלצים שלנו';
$page_description = "עמותת חבר לי מזמינה אתכם לבקר ברשימת המומלצים שלנו, וטרינרים, מאלפים ועוד..";
$page_url = 'http://imutz.org/recommend';
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
$result = $mysqli->query('SELECT image, imageName, imageCaption FROM recommand ORDER BY imageOrder');
while ($row = $result->fetch_assoc())
	echo '<div class="rectDiv clearfix"><img src="/images/recommands/' . $row['image'] . '" title="' . $row['imageName'] . '" alt="' . $row['imageName'] . '"><h3>' . $row['imageName'] . '</h3>' . $row['imageCaption'] . '</div>';
$result->free();
?>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>