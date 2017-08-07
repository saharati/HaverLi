<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - לוח מודעות';
$page_description = "עמותת חבר לי מזמינה אתכם להתעדכן באירועים, ימי אימוץ, אבדות ומציאות בלוח המודעות שלנו.";
$page_url = 'http://imutz.org/board';
$page_image = 'http://imutz.org/images/og/board.jpg';
$page_image_width = 1200;
$page_image_height = 630;
require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="board">
<div id="contentInner">
<?php
$result = $mysqli->query('SELECT image, imageLink FROM board ORDER BY imageOrder');
if ($result->num_rows)
{
	echo '<ul>';
	while ($row = $result->fetch_assoc())
	{
		echo '<li>';
		if (empty($row['imageLink']))
			echo '<img src="/images/board/' . $row['image'] . '" alt="">';
		else
			echo '<a href="' . $row['imageLink'] . '"><img src="/images/board/' . $row['image'] . '" alt=""></a>';
		echo '</li>';
	}
	echo '</ul>';
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