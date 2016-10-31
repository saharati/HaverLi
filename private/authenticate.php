<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'])
{
	if (isset($_COOKIE['user']) && is_numeric($_COOKIE['user']) && $_COOKIE['user'] > 0)
	{
		$characters = '0123456789abcdef';
		$salt = '';
		for ($i = 0;$i < 15;$i++)
			$salt .= $characters[mt_rand(0, 15)];
		$userhash = sha1($salt . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
		session_regenerate_id();
		$_SESSION['signature'] = $salt . $userhash;
		$_SESSION['logged_in'] = true;
		$_SESSION['LAST_ACTIVITY'] = time();
		$_SESSION['userId'] = $_COOKIE['user'];
		setcookie('user', $_SESSION['userId'], time() + (86400 * 30));
	}
	else
	{
		header('Location: /login');
		exit;
	}
}
if (time() - $_SESSION['LAST_ACTIVITY'] > 900)
{
	if (isset($_COOKIE['user']) && is_numeric($_COOKIE['user']) && $_COOKIE['user'] > 0)
	{
		$characters = '0123456789abcdef';
		$salt = '';
		for ($i = 0;$i < 15;$i++)
			$salt .= $characters[mt_rand(0, 15)];
		$userhash = sha1($salt . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
		session_regenerate_id();
		$_SESSION['signature'] = $salt . $userhash;
		$_SESSION['logged_in'] = true;
		$_SESSION['LAST_ACTIVITY'] = time();
		$_SESSION['userId'] = $_COOKIE['user'];
		setcookie('user', $_SESSION['userId'], time() + (86400 * 30));
	}
	else
	{
		session_destroy();
		session_unset();
		$_SESSION['logged_in'] = false;
		header('Location: /login');
		exit;
	}
}
else
	$_SESSION['LAST_ACTIVITY'] = time();
function pagination($pageNum, $lastPage, $url)
{
	if ($lastPage < 2)
		return;
	echo '<div class="clearfix">
<a title="הקודם" ' . ($pageNum > 1 ? 'class="prev" href="' . $url . ($pageNum - 1) . '"' : 'class="prev prev_disabled" href="javascript:void(0);"') . '>הקודם</a>
<a title="הבא" ' . ($pageNum < $lastPage ? 'class="next" href="' . $url . ($pageNum + 1) . '"' : 'class="next next_disabled" href="javascript:void(0);"') . '>הבא</a>
</div>';
}
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