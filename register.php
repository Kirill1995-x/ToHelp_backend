<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require "init.php";

function check_fields($surname, $name, $middlename, $email, $password, $phone_number, 
                      $city, $subject, $sex, $date, $month, $year, $agreement)
{
	if(empty($surname))return false;
	else if(empty($name))return false;
	else if(empty($middlename))return false;
	else if(empty($email))return false;
	else if(empty($password))return false;
	else if(empty($phone_number))return false;
	else if(empty($city))return false;
	else if(empty($subject))return false;
	else if(empty($sex))return false;
	else if(empty($date))return false;
	else if(empty($month))return false;
	else if(empty($year))return false;
	else if(empty($agreement))return false;
	else return true;
}

$surname=mysqli_real_escape_string($con,$_POST["surname"]);
$name=mysqli_real_escape_string($con,$_POST["name"]);
$middlename=mysqli_real_escape_string($con,$_POST["middlename"]);
$child_home=mysqli_real_escape_string($con,$_POST["child_home"]);
$email=$_POST["email"];
$password=password_hash($_POST["password"], PASSWORD_BCRYPT);
$phone_number=$_POST["phone_number"];
$city=mysqli_real_escape_string($con,$_POST["city"]);
$subject=mysqli_real_escape_string($con,$_POST["subject_of_country"]);
$registration_address=mysqli_real_escape_string($con,$_POST["registration_address"]);
$factual_address=mysqli_real_escape_string($con,$_POST["factual_address"]);
$type_of_flat=mysqli_real_escape_string($con,$_POST["type_of_flat"]);
$sex=$_POST["sex"];
$date=$_POST["date_of_born"];
$month=$_POST["month_of_born"];
$year=$_POST["year_of_born"];
$agreement=mysqli_real_escape_string($con,$_POST["agreement"]);
$name_of_photo="without_photo";
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");
$access_token=md5(random_bytes(16));

$response=array();

if(check_fields($surname, $name, $middlename, $email, $password, $phone_number, $city, $subject, $sex, $date, $month, $year, $agreement))
{
	if($agreement=='success')
	{
		$sql="select * from information_about_users where email like '".$email."' or phone_number like '".$phone_number."';";
		$result=mysqli_query($con,$sql);
		
		if (mysqli_num_rows($result)>0)
		{
			$code="reg_failed";
			$title="Ответ от сервера";
			$message="Пользователь с таким email или номером телефона уже существует";
			array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
			echo json_encode($response);
		}
		else
		{
			$confirmed=false;
			$code_registration=rand();
			
			$sql="INSERT INTO information_about_users (confirmed, code_registration, surname, name, middlename, child_home, email, password, phone_number, city, subject_of_country, 
			registration_address, factual_address, type_of_flat, sex, date_of_born, month_of_born, year_of_born, name_of_photo, date_last_visit, time_last_visit, access_token)
			VALUES ('".$confirmed."','".$code_registration."','".$surname."','".$name."','".$middlename."','".$child_home."','".$email."','".$password."','".$phone_number."', 
			'".$city."', '".$subject."','".$registration_address."','".$factual_address."','".$type_of_flat."','".$sex."','".$date."','".$month."','".$year."','".$name_of_photo."',
			'".$date_last_visit."', '".$time_last_visit."', '".$access_token."')";
			
			$result=mysqli_query($con,$sql);
			
			if($result==true)
			{
				$code="reg_success";
				$title="Ответ от сервера";
				$message="$name $middlename, спасибо за регистрацию. На вашу почту $email отправлена ссылка. Перейдите по ней для завершения регистрации. Если сообщение не появилось, проверьте Спам";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
				
				$mail = new PHPMailer();
				$mail->CharSet = "utf-8";
				$mail->setFrom('info@tohelptohelp.ru', 'ВПомощь');
				$mail->addReplyTo('info@tohelptohelp.ru', 'ВПомощь');
				$mail->addAddress($email);
				$mail->Subject = 'Регистрация в приложении ВПомощь';                         
				$mail->Body = 
				''.$name.' '.$middlename.',<br>
				пожалуйста, нажмите на ссылку ниже для подтверждения регистрации.<br>
				Если ссылка не работает, скопируйте ее в адресную строку.<br>
				Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
				https://tohelptohelp.ru/tohelp/emailver.php?email='.$email.'&code_registration='.$code_registration.'';
				$mail->AltBody = 
				"$name $middlename,
				пожалуйста, нажмите на ссылку ниже для подтверждения регистрации.
				Если ссылка не работает, скопируйте ее в адресную строку.
				Если сообщение пришло к Вам по ошибке, проигнорируйте его.
				https://tohelptohelp.ru/tohelp/emailver.php?email=$email&code_registration=$code_registration";
				if ($mail->send()) echo 'Письмо отправлено!';
				else echo 'Ошибка: ' . $mail->ErrorInfo;
			}
			else
			{
				$code="reg_failed";
				$title="Ответ от сервера";
				$message="Произошла ошибка. Повторите попытку";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
			}
		}
	}
	else
	{
		$code="reg_failed";
		$title="Ответ от сервера";
		$message="Доступ запрещен";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="reg_failed";
	$title="Ответ от сервера";
	$message="Не все обязательные поля заполнены";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>