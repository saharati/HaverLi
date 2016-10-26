<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');
$oldDB = new MySQLi('p:127.0.0.1', 'root', 'Pp2p7br4', 'imutz');
$oldDB->set_charset('utf8');
$newDB = new MySQLi('p:127.0.0.1', 'root', 'Pp2p7br4', 'imutz-v2');
$newDB->set_charset('utf8');
$albums = array();
$result = $newDB->query('SELECT id FROM album');
while ($row = $result->fetch_assoc())
	$albums[] = $row['id'];
$result->free();
$albums = implode(',', $albums);
$result = $oldDB->query('SELECT * FROM album WHERE id NOT IN (' . $albums . ')');
while ($row = $result->fetch_assoc())
{
	$id = $row['id'];
	$postDate = $row['postDate'];
	$name = $row['name'];
	$description = $row['description'];
	$isDog = $row['petType'] == 1 ? 1 : 0;
	$isMale = $row['gender'] == 1 ? 1 : 0;
	$isAdopted = $row['isActive'] != 1 ? 1 : 0;
	$size = $row['size'];
	$bornDate = date('Y-m-d', strtotime('-' . $row['age'] . ' months'));
	if ($row['breedId'] > 0)
	{
		if ($isDog == 1)
			$result2 = $oldDB->query('SELECT name FROM dog_breed WHERE id=' . $row['breedId']);
		else
			$result2 = $oldDB->query('SELECT name FROM cat_breed WHERE id=' . $row['breedId']);
		$row2 = $result2->fetch_assoc();
		$result2->free();
		$result2 = $newDB->query('SELECT id FROM pet_breed WHERE name="' . $row2['name'] . '"');
		$row2 = $result2->fetch_assoc();
		$result2->free();
		if ($row2)
			$breedId = $row2['id'];
		else
			$breedId = 0;
	}
	else
		$breedId = 0;
	$newDB->query('INSERT INTO album VALUES (' . $id . ', "' . $postDate . '", "' . $name . '", "' . $description . '", ' . $isDog . ', ' . $isMale . ', ' . $isAdopted . ', ' . $breedId . ', ' . $size . ', "' . $bornDate . '")');
	// mkdir($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $id);
}
$result->free();
echo 'Done.';
// Part 2: transfer photos.
/*$pics = array();
$result = $newDB->query('SELECT id FROM album_photo');
while ($row = $result->fetch_assoc())
	$pics[] = $row['id'];
$result->free();
$result = $oldDB->query('SELECT * FROM photo');
while ($row = $result->fetch_assoc())
{
	if (in_array($row['id'], $pics))
		continue;
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $row['albumId'] . '/' . $row['bigpic'], file_get_contents('http://imutz.org/images/adopts/' . $row['albumId'] . '/' . $row['bigpic']));
	$newDB->query('INSERT INTO album_photo VALUES (' . $row['id'] . ', ' . $row['albumId'] . ', "' . $row['bigpic'] . '", ' . $row['bigwidth'] . ', ' . $row['bigheight'] . ', ' . ($row['cover'] == 1 ? 2 : 0) . ')');
}
$result->free();
echo 'Done.';*/
?>