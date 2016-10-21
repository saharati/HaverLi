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
?>