<?php
// Start configuration.
$db_host = 'host';
$db_username = 'username';
$db_password = 'password';
$db_database = 'database';
$mail_host = 'host';
$mail_username = 'username';
$mail_password = 'password';
// End configuration.
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'on');
mb_internal_encoding('UTF-8');
$mysqli = new MySQLi($db_host, $db_username, $db_password, $db_database);
$mysqli->set_charset('utf8');
function sanitize($data)
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
function calcSize($image, $boxSize)
{
	// Resize the image so at least one of its dimensions is precicely $boxSize.
	if ($image['width'] < $boxSize)
	{
		// Formula: desiredHeight = desiredWidth * (height/width)
		$image['height'] = $boxSize * ($image['height'] / $image['width']);
		$image['width'] = $boxSize;
	}
	if ($image['height'] < $boxSize)
	{
		// Formula: desiredWidth = desiredHeight * (width/height)
		$image['width'] = $boxSize * ($image['width'] / $image['height']);
		$image['height'] = $boxSize;
	}
	if ($image['width'] > $boxSize && $image['height'] > $boxSize)
	{
		// Wide image.
		if ($image['width'] > $image['height'])
		{
			// Bring height to $boxSize.
			$image['width'] = $boxSize * ($image['width'] / $image['height']);
			$image['height'] = $boxSize;
		}
		else
		{
			// Bring width to $boxSize.
			$image['height'] = $boxSize * ($image['height'] / $image['width']);
			$image['width'] = $boxSize;
		}
	}
	$image['width'] = round($image['width']);
	$image['height'] = round($image['height']);
	return $image;
}
function calcStyle($image, $isBackground, $boxSize = 260)
{
	$image = calcSize($image, $boxSize);
	$style = 'style="';
	// For wide/square images use width:{imageWidth};height:auto
	// For high images use height:{imageHeight};width:auto
	// High images first.
	// Here one of the values equals to $boxSize for sure, so need to style negative margin the other to center it.
	if ($isBackground)
	{
		$style .= 'background-image:url(/images/albums/' . $image['albumId'] . '/' . $image['image'] . ');';
		if ($image['height'] > $image['width'])
			$style .= 'background-size:auto ' . $image['height'] . 'px;';
		else
			$style .= 'background-size:' . $image['width'] . 'px auto;';
		if ($image['width'] > $boxSize)
			$style .= 'background-position:-' . (round(($image['width'] - $boxSize) / 2)) . 'px 0;';
		elseif ($image['height'] > $boxSize)
			$style .= 'background-position:0 -' . (round(($image['height'] - $boxSize) / 2)) . 'px;';
	}
	else
	{
		if ($image['height'] > $image['width'])
		{
			$heightInPercent = round(($image['height'] / $boxSize) * 100);
			$style .= 'height:' . $heightInPercent . '%;width:auto;';
		}
		else
		{
			$widthInPercent = round(($image['width'] / $boxSize) * 100);
			$style .= 'width:' . $widthInPercent . '%;height:auto;';
		}
		if ($image['width'] > $boxSize)
		{
			$movePixels = ($image['width'] - $boxSize) / 2;
			$movePercent = round(($movePixels / $image['width']) * 100);
			$style .= 'margin-left:-' . $movePercent . '%';
		}
		elseif ($image['height'] > $boxSize)
		{
			$movePixels = ($image['height'] - $boxSize) / 2;
			$movePercent = round(($movePixels / $image['height']) * 100);
			$style .= 'margin-top:-' . $movePercent . '%';
		}
	}
	$style .= '"';
	return $style;
}
function initMailer()
{
	require $_SERVER['DOCUMENT_ROOT'] . '/includes/mailer/PHPMailerAutoload.php';
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->Host = $mail_host;
	$mail->CharSet = 'UTF-8';
	$mail->Username = $mail_username;
	$mail->Password = $mail_password;
	$mail->SetFrom('support@imutz.org', 'עמותת חבר לי');
	return $mail;
}
function sendMail($mail, $subject, $message, $from, $fromName, $to, $toName)
{
	try
	{
		$mail->ClearAddresses();
		$mail->Subject = htmlspecialchars_decode($subject, ENT_QUOTES);
		$mail->Body = strip_tags($message);
		$mail->AddReplyTo($from, htmlspecialchars_decode($fromName, ENT_QUOTES));
		$mail->AddAddress($to, htmlspecialchars_decode($toName, ENT_QUOTES));
		return $mail->Send();
	}
	catch (exception $e)
	{
		return false;
	}
}
?>