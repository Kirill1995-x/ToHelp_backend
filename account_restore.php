<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require "init.php";

$email=$_POST["email"];

$sql="SELECT id FROM information_about_users WHERE email = '".$email."'";
$result=mysqli_query($con, $sql);
$response=array();
if(mysqli_num_rows($result)>0)
{
	$code="success";
	$title="Ответ от сервера";
	$message="Ссылка для восстановления аккаунта направлена на почту";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
	
	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";
	$mail->setFrom('info@tohelptohelp.ru', 'ВПомощь');
	$mail->addReplyTo('info@tohelptohelp.ru', 'ВПомощь');
	$mail->addAddress($email);
	$mail->Subject = 'Восстановление аккаунта ВПомощь';                         
	$mail->Body = 
	'Добрый день,<br>
	Для восстановления аккаунта перейдите по ссылке.<br>
	Если ссылка не работает, скопируйте ее в адресную строку.<br>
	Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
	https://tohelptohelp.ru/tohelp/account_ver.php?email='.$email.'<br>
	С уважением,<br>
	команда разработчиков ВПомощь';
	$mail->AltBody = 
	"Добрый день,
	Для восстановления аккаунта перейдите по ссылке.
	Если ссылка не работает, скопируйте ее в адресную строку.
	Если сообщение пришло к Вам по ошибке, проигнорируйте его.
	https://tohelptohelp.ru/tohelp/account_ver.php?email='.$email.'
	С уважением,
	команда разработчиков ВПомощь";
	if ($mail->send()) echo 'Письмо отправлено!';
	else echo 'Ошибка: ' . $mail->ErrorInfo;
}
else
{
	$code="failed";
	$title="Ответ от сервера";
	$message="Пользователь не найден";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>