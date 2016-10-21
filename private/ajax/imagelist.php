<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$vars = array();
$result = $mysqli->query('SELECT * FROM image ORDER BY imageName');
while ($row = $result->fetch_assoc())
	$vars[] = array('title' => $row['imageName'], 'value' => 'http://v2.imutz.org/images/data/' . $row['image']);
$result->free();
echo json_encode($vars);
?>