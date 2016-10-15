<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');
$oldDB = new MySQLi('p:127.0.0.1', 'root', 'Pp2p7br4', 'imutz');
$oldDB->set_charset('utf8');
$newDB = new MySQLi('p:127.0.0.1', 'root', 'Pp2p7br4', 'imutz-v2');
$newDB->set_charset('utf8');
// NOTICE: you can only run 1 part at a time, others must be commented.
// Part 1: Reset.
/*function rrmdir($dir)
{
	$fp = opendir($dir);
	if ($fp)
	{
		while ($f = readdir($fp))
		{
			$file = $dir . '/' . $f;
			if ($f == '.' || $f == '..')
				continue;
			if (is_dir($file) && !is_link($file))
				rrmdir($file);
			else
				unlink($file);
		}
		closedir($fp);
		rmdir($dir);
	}
}
$dir = $_SERVER['DOCUMENT_ROOT'] . '/images/albums';
$fp = opendir($dir);
while ($f = readdir($fp))
{
	$file = $dir . '/' . $f;
	if ($f == '.' || $f == '..')
		continue;
	if (is_dir($file) && !is_link($file))
		rrmdir($file);
	else
		unlink($file);
}
closedir($fp);
$newDB->query('DELETE FROM album');
$newDB->query('DELETE FROM album_breed');
$newDB->query('DELETE FROM album_color');
$newDB->query('DELETE FROM album_size');
$newDB->query('DELETE FROM album_photo');
$result = $oldDB->query('SELECT * FROM album');
while ($row = $result->fetch_assoc())
{
	$id = $row['id'];
	$postDate = $row['postDate'];
	$hebrewName = $row['name'];
	$englishName = '';
	$russianName = '';
	$hebrewDescription = $row['description'];
	$englishDescription = '';
	$russianDescription = '';
	$isDog = $row['petType'] == 1 ? 1 : 0;
	$isMale = $row['gender'] == 1 ? 1 : 0;
	$isAdopted = $row['isActive'] != 1 ? 1 : 0;
	$bornDate = date('Y-m-d', strtotime('-' . $row ['age'] . ' months'));
	$newDB->query('INSERT INTO album VALUES (' . $id . ', "' . $postDate . '", "' . $hebrewName . '", "' . $englishName . '", "' . $russianName . '", "' . $hebrewDescription . '", "' . $englishDescription . '", "' . $russianDescription . '", ' . $isDog . ', ' . $isMale . ', ' . $isAdopted . ', "' . $bornDate . '")');
	if ($row['breedId'] > 0)
	{
		if ($isDog == 1)
			$result2 = $oldDB->query('SELECT name FROM dog_breed WHERE id=' . $row ['breedId']);
		else
			$result2 = $oldDB->query('SELECT name FROM cat_breed WHERE id=' . $row ['breedId']);
		$row2 = $result2->fetch_assoc();
		$result2->free();
		$result2 = $newDB->query('SELECT id FROM pet_breed WHERE hebrewName="' . $row2 ['name'] . '"');
		$row2 = $result2->fetch_assoc();
		$result2->free();
		$newDB->query('INSERT INTO album_breed VALUES (' . $id . ', ' . $row2 ['id'] . ')');
	}
	if ($row ['size'] > 0)
		$newDB->query('INSERT INTO album_size VALUES (' . $id . ', ' . $row ['size'] . ')');
	mkdir($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $id);
	mkdir($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $id . '/thumbs');
}
$result->free();
echo 'Done.';*/
// Part 2: translate only.
/*function sanitize($data)
{
	if (is_array($data))
	{
		foreach ($data as &$value)
			$value = sanitize($value);
		unset($value);
	}
	else
		$data = htmlspecialchars(trim(htmlspecialchars_decode($data, ENT_QUOTES)), ENT_QUOTES);
	return $data;
}
function initTranslate()
{
	$ch = curl_init();
	$paramArr = array(
		'grant_type'    => 'client_credentials',
		'scope'         => 'http://api.microsofttranslator.com',
		'client_id'     => '189557297810523',
		'client_secret' => 'Dog7T3gRVjsU8JVao50wkH0zBtNgdZ4vdF7XQvSqqhU='
	);
	$paramArr = http_build_query($paramArr);
	curl_setopt($ch, CURLOPT_URL, 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$strResponse = json_decode(curl_exec($ch));
	curl_close($ch);
	return $strResponse->access_token;
}
function doTranslate($url, $to, $enc = true)
{
	if (!isset($_SESSION['translator']))
		$_SESSION['translator'] = initTranslate();
	$url = str_replace('<br>', '', $url);
	if ($enc)
		$url = htmlspecialchars_decode($url, ENT_QUOTES);
	if (strlen($url) > 4000)
	{
		$url = explode("\n", $url);
		$resp = '';
		foreach ($url as $u)
			$resp .= doTranslate($u, $to, false) . "\n";
	}
	else
	{
		$url = 'http://api.microsofttranslator.com/V2/Http.svc/translate?text=' . urlencode($url) . '&from=he&to=' . $to;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $_SESSION['translator'], 'Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$resp = simplexml_load_string(curl_exec($ch));
		curl_close($ch);
	}
	if ($enc)
	{
		if ($to == 'en')
			$resp = str_replace(array('bitch', '$'), array('dog', '₪'), $resp);
		else
			$resp = str_replace(array('Сука'), array('Собака'), $resp);
		$resp = sanitize($resp);
		return nl2br($resp, false);
	}
	return $resp;
}
$result = $newDB->query('SELECT id, hebrewName, hebrewDescription FROM album WHERE hebrewName="" OR hebrewDescription=""');
while ($row = $result->fetch_assoc())
{
	$englishName = doTranslate($row['hebrewName'], 'en');
	$russianName = doTranslate($row['hebrewName'], 'ru');
	$englishDescription = str_replace('<br>', '', doTranslate($row['hebrewDescription'], 'en'));
	$englishDescription .= "\r\n\r\nTranslated by Bing.";
	$englishDescription = nl2br($englishDescription, false);
	$russianDescription = str_replace('<br>', '', doTranslate($row['hebrewDescription'], 'ru'));
	$russianDescription .= "\r\n\r\nпереведёт Бинг.";
	$russianDescription = nl2br($russianDescription, false);
	$newDB->query('UPDATE album SET englishName="' . $englishName . '", russianName="' . $russianName . '", englishDescription="' . $englishDescription . '", russianDescription="' . $russianDescription . '" WHERE id=' . $row['id']);
}
$result->free();
echo 'Done.';*/
// Part 3: transfer photos.
$pics = array();
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
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/images/albums/' . $row['albumId'] . '/thumbs/' . $row['smallpic'], file_get_contents('http://imutz.org/images/adopts/' . $row['albumId'] . '/thumbs/' . $row['smallpic']));
	$newDB->query('INSERT INTO album_photo VALUES (' . $row['id'] . ', ' . $row['albumId'] . ', "' . $row['bigpic'] . '", ' . $row['bigwidth'] . ', ' . $row['bigheight'] . ', "' . $row['smallpic'] . '", ' . $row['smallwidth'] . ', ' . $row['smallheight'] . ', ' . $row['cover'] . ')');
}
$result->free();
echo 'Done.';
?>