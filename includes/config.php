<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'on');
mb_internal_encoding('UTF-8');
$mysqli = new MySQLi('p:127.0.0.1', 'root', 'Pp2p7br4', 'imutz-v2');
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
function calcSize($image)
{
	$boxSize = 260;
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
	$image['style'] = 'style="';
	// For wide/square images use width:{imageWidth};height:auto
	// For high images use height:{imageHeight};width:auto
	// High images first.
	if ($image['height'] > $image['width'])
		$image['style'] .= 'height:' . $image['height'] . 'px;width:auto;';
	else
		$image['style'] .= 'width:' . $image['width'] . 'px;height:auto;';
	// Here one of the values equals to $boxSize for sure, so need to style negative margin the other to center it.
	if ($image['width'] > $boxSize)
		$image['style'] .= 'margin-left:-' . (round(($image['width'] - $boxSize) / 2)) . 'px"';
	elseif ($image['height'] > $boxSize)
		$image['style'] .= 'margin-top:-' . (round(($image['height'] - $boxSize) / 2)) . 'px"';
	$image['style'] .= '"';
	return $image;
}
function initMailer()
{
	require $_SERVER['DOCUMENT_ROOT'] . '/includes/mailer/PHPMailerAutoload.php';
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->Host = 'mail.petpal.co.il';
	$mail->CharSet = 'UTF-8';
	$mail->Username = 'support@imutz.org';
	$mail->Password = 'Pp2p7br4';
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