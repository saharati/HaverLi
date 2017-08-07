<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - צור קשר';
$page_description = "עמותת חבר לי מזמינה אתכם ליצור איתנו קשר לגבי אימוץ ומסירה, תרומות, התייעצות, שעות פעילות, ימי אימוץ ועוד...";
$page_url = 'http://imutz.org/contact';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<body>
<div id="wrapper">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; ?>
<main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/mobile.php'; ?>
<div id="content" class="contact fullwidth">
<div id="contentInner">
<div class="right">
<h2>דרכי התקשרות</h2>
<?php
$result = $mysqli->query('SELECT name, value FROM contact ORDER BY viewOrder');
while ($row = $result->fetch_assoc())
	echo '<p>' . $row['name'] . ': ' . $row['value'] . '</p>';
$result->free();
?>
</div>
<div class="left">
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/contact.php'; ?>
</div>
</div>
</div>
</main>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
</div>
</body>
</html>