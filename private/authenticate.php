<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'])
{
	header('Location: /login');
	exit;
}
if (time() - $_SESSION['LAST_ACTIVITY'] > 900)
{
	session_destroy();
	session_unset();
	$_SESSION['logged_in'] = false;
	header('Location: /login');
	exit;
}
else
	$_SESSION['LAST_ACTIVITY'] = time();
function rrmdir($dir)
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
?>