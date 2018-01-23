<?php
require $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
if (isset($_POST['petId']) && is_numeric($_POST['petId']) && $_POST['petId'] > 0)
{
	if (isset($_COOKIE['pet-' . $_POST['petId']]))
		echo 'כבר הוספת בעל חיים זה לרשימת המשאלות שלך.syserror';
	else
	{
		setcookie('pet-' . $_POST['petId'], $_POST['petId'], time() + 86400 * 30);
		echo 'בעל החיים נוסף בהצלחה לרשימת המשאלות שלך.syssuccess';
	}
}
?>