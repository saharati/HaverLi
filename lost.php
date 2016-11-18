<?php require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="he">
<?php
$page_title = 'עמותת חבר לי - אבד לי הכלב';
$page_description = "הכלב שלכם אבד? קודם כל קחו נשימה עמוקה. בשביל זה ארגנו לכם מספר פעולות פשוטות לעזור לכם למצוא את כלבכם האהוב.";
$page_url = 'http://imutz.org/lost';
$page_image = 'http://imutz.org/images/og/lost.jpg';
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