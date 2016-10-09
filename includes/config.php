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