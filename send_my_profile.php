<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require "init.php";

function check_fields($surname, $name, $middlename, $email, $phone_number, $city, $subject_of_country, $sex, $date, $month, $year)
{
	if(empty($surname))return false;
	else if(empty($name))return false;
	else if(empty($middlename))return false;
	else if(empty($email))return false;
	else if(empty($phone_number))return false;
	else if(empty($city))return false;
	else if(empty($subject_of_country))return false;
	else if(empty($sex))return false;
	else if(empty($date))return false;
	else if(empty($month))return false;
	else if(empty($year))return false;
	else return true;
}

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$surname=mysqli_real_escape_string($con,$_POST["surname"]);
$name=mysqli_real_escape_string($con,$_POST["name"]);
$middlename=mysqli_real_escape_string($con,$_POST["middlename"]);
$child_home=mysqli_real_escape_string($con,$_POST["child_home"]);
$email=$_POST["email"];
$phone_number=$_POST["phone_number"];
$city=mysqli_real_escape_string($con,$_POST["city"]);
$subject_of_country=mysqli_real_escape_string($con,$_POST["subject_of_country"]);
$registration_address=mysqli_real_escape_string($con,$_POST["registration_address"]);
$factual_address=mysqli_real_escape_string($con,$_POST["factual_address"]);
$type_of_flat=mysqli_real_escape_string($con,$_POST["type_of_flat"]);
$sex=$_POST["sex"];
$date=$_POST["date_of_born"];
$month=$_POST["month_of_born"];
$year=$_POST["year_of_born"];
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(check_fields($surname, $name, $middlename, $email, $phone_number, $city, $subject_of_country, $sex, $date, $month, $year))
	{
		$choose_from_bd="SELECT email, phone_number from information_about_users WHERE id='".$id."'";
		$result_of_request=mysqli_query($con, $choose_from_bd);
		$row=mysqli_fetch_row($result_of_request);
		if ($email==$row[0] && $phone_number==$row[1])//email и номер телефона не изменились
		{
			$sql="UPDATE information_about_users SET surname='".$surname."',
					name='".$name."',
					middlename='".$middlename."',
					child_home='".$child_home."',
					email='".$email."',
					phone_number='".$phone_number."',
					city='".$city."',
					subject_of_country='".$subject_of_country."',
					registration_address='".$registration_address."',
					factual_address='".$factual_address."',
					type_of_flat='".$type_of_flat."',
					sex='".$sex."',
					date_of_born='".$date."',
					month_of_born='".$month."',
					year_of_born='".$year."',
					date_last_visit='".$date_last_visit."',
					time_last_visit='".$time_last_visit."'
					WHERE id='".$id."';";

					$result=mysqli_query($con,$sql);

					if ($result==true)
					{
						$code="my_profile_get_success";
						$title="Ответ от сервера";
						$message="Данные успешно обновлены";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
					else
					{
						$code="my_profile_get_failed";
						$title="Ответ от сервера";
						$message="Данные не обновлены. Повторите попытку или зайдите в приложение снова";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
		}
		else if($email==$row[0] && $phone_number!=$row[1])//изменился номер телефона
		{
			$check="SELECT * from information_about_users WHERE phone_number='".$phone_number."'";
			$check_email=mysqli_query($con, $check);
			if (mysqli_num_rows($check_email)>0)
			{
					$code="my_profile_phone_failed";
					$title="Ответ от сервера";
					$message="Пользователь с таким номером телефона уже есть. Данные не обновлены";
					array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "phone_for_my_profile"=>$row[1]));
					echo json_encode($response);
			}
			else
			{
					$confirmed=false;
					$code_registration=rand();
					
					$sql="UPDATE information_about_users SET confirmed='".$confirmed."',
					code_registration='".$code_registration."',
					surname='".$surname."',
					name='".$name."',
					middlename='".$middlename."',
					child_home='".$child_home."',
					email='".$email."',
					phone_number='".$phone_number."',
					city='".$city."',
					subject_of_country='".$subject_of_country."',
					registration_address='".$registration_address."',
					factual_address='".$factual_address."',
					type_of_flat='".$type_of_flat."',
					sex='".$sex."',
					date_of_born='".$date."',
					month_of_born='".$month."',
					year_of_born='".$year."',
					date_last_visit='".$date_last_visit."',
					time_last_visit='".$time_last_visit."'
					WHERE id='".$id."';";

					$result=mysqli_query($con,$sql);

					if ($result==true)
					{
						$code="my_profile_get_success";
						$title="Ответ от сервера";
						$message="$name $middlename, на вашу почту $email отправлена ссылка. Перейдите по ней для подтверждения изменения номера телефона. Если сообщение не появилось, проверьте Спам";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
						
						$mail = new PHPMailer();
						$mail->CharSet = "utf-8";
						$mail->setFrom('info@tohelptohelp.ru', 'ВПомощь');
						$mail->addReplyTo('info@tohelptohelp.ru', 'ВПомощь');
						$mail->addAddress($email);
						$mail->Subject = 'Обновление номера телефона в приложении ВПомощь';                         
						$mail->Body = 
						''.$name.' '.$middlename.',<br>
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления номера телефона.<br>
						Если ссылка не работает, скопируйте ее в адресную строку.<br>
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
						https://tohelptohelp.ru/tohelp/emailver.php?email='.$email.'&code_registration='.$code_registration.'';
						$mail->AltBody = 
						"$name $middlename,
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления номера телефона.
						Если ссылка не работает, скопируйте ее в адресную строку.
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.
						https://tohelptohelp.ru/tohelp/emailver.php?email=$email&code_registration=$code_registration";
						if ($mail->send()) echo 'Письмо отправлено!';
						else echo 'Ошибка: ' . $mail->ErrorInfo;
					}
					else
					{
						$code="my_profile_get_failed";
						$title="Ответ от сервера";
						$message="Данные не обновлены. Повторите попытку или зайдите в приложение снова";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
			}
		}
		else if($email!=$row[0] && $phone_number==$row[1])//изменился email
		{
			$check="SELECT * from information_about_users WHERE email='".$email."'";
			$check_email=mysqli_query($con, $check);
			if (mysqli_num_rows($check_email)>0)
			{
					$code="my_profile_email_failed";
					$title="Ответ от сервера";
					$message="Пользователь с таким email уже есть. Данные не обновлены";
					array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "email_for_my_profile"=>$row[0]));
					echo json_encode($response);
			}
			else
			{
					$confirmed=false;
					$code_registration=rand();
					
					$sql="UPDATE information_about_users SET confirmed='".$confirmed."',
					code_registration='".$code_registration."',
					surname='".$surname."',
					name='".$name."',
					middlename='".$middlename."',
					child_home='".$child_home."',
					email='".$email."',
					phone_number='".$phone_number."',
					city='".$city."',
					subject_of_country='".$subject_of_country."',
					registration_address='".$registration_address."',
					factual_address='".$factual_address."',
					type_of_flat='".$type_of_flat."',
					sex='".$sex."',
					date_of_born='".$date."',
					month_of_born='".$month."',
					year_of_born='".$year."',
					date_last_visit='".$date_last_visit."',
					time_last_visit='".$time_last_visit."'
					WHERE id='".$id."';";

					$result=mysqli_query($con,$sql);

					if ($result==true)
					{
						$code="my_profile_get_success";
						$title="Ответ от сервера";
						$message="$name $middlename, на вашу почту $email отправлена ссылка. Перейдите по ней для подтверждения изменения почты. Если сообщение не появилось, проверьте Спам";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
						
						$mail = new PHPMailer();
						$mail->CharSet = "utf-8";
						$mail->setFrom('info@tohelptohelp.ru', 'ВПомощь');
						$mail->addReplyTo('info@tohelptohelp.ru', 'ВПомощь');
						$mail->addAddress($email);
						$mail->Subject = 'Обновление почты в приложении ВПомощь';                         
						$mail->Body = 
						''.$name.' '.$middlename.',<br>
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления почты.<br>
						Если ссылка не работает, скопируйте ее в адресную строку.<br>
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
						https://tohelptohelp.ru/tohelp/emailver.php?email='.$email.'&code_registration='.$code_registration.'';
						$mail->AltBody = 
						"$name $middlename,
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления почты.
						Если ссылка не работает, скопируйте ее в адресную строку.
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.
						https://tohelptohelp.ru/tohelp/emailver.php?email=$email&code_registration=$code_registration";
						if ($mail->send()) echo 'Письмо отправлено!';
						else echo 'Ошибка: ' . $mail->ErrorInfo;
					}
					else
					{
						$code="my_profile_get_failed";
						$title="Ответ от сервера";
						$message="Данные не обновлены. Повторите попытку или зайдите в приложение снова";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
			}
		}
		else //изменились email и номер телефона
		{
			$check="SELECT * from information_about_users WHERE email='".$email."' or phone_number='".$phone_number."'";
			$check_email=mysqli_query($con, $check);
			if (mysqli_num_rows($check_email)>0)
			{
					$code="my_profile_email_and_phone_failed";
					$title="Ответ от сервера";
					$message="Пользователь(-и) с таким email и номером телефона уже есть. Данные не обновлены";
					array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "email_for_my_profile"=>$row[0], "phone_for_my_profile"=>$row[1]));
					echo json_encode($response);
			}
			else
			{
					$confirmed=false;
					$code_registration=rand();
					
					$sql="UPDATE information_about_users SET confirmed='".$confirmed."',
					code_registration='".$code_registration."',
					surname='".$surname."',
					name='".$name."',
					middlename='".$middlename."',
					child_home='".$child_home."',
					email='".$email."',
					phone_number='".$phone_number."',
					city='".$city."',
					subject_of_country='".$subject_of_country."',
					registration_address='".$registration_address."',
					factual_address='".$factual_address."',
					type_of_flat='".$type_of_flat."',
					sex='".$sex."',
					date_of_born='".$date."',
					month_of_born='".$month."',
					year_of_born='".$year."',
					date_last_visit='".$date_last_visit."',
					time_last_visit='".$time_last_visit."'
					WHERE id='".$id."';";

					$result=mysqli_query($con,$sql);

					if ($result==true)
					{
						$code="my_profile_get_success";
						$title="Ответ от сервера";
						$message="$name $middlename, на вашу почту $email отправлена ссылка. Перейдите по ней для подтверждения изменения почты и номера телефона. Если сообщение не появилось, проверьте Спам";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
						
						$mail = new PHPMailer();
						$mail->CharSet = "utf-8";
						$mail->setFrom('info@tohelptohelp.ru', 'ВПомощь');
						$mail->addReplyTo('info@tohelptohelp.ru', 'ВПомощь');
						$mail->addAddress($email);
						$mail->Subject = 'Обновление почты и номера телефона в приложении ВПомощь';                         
						$mail->Body = 
						''.$name.' '.$middlename.',<br>
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления почты и номера телефона.<br>
						Если ссылка не работает, скопируйте ее в адресную строку.<br>
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.<br>
						https://tohelptohelp.ru/tohelp/emailver.php?email='.$email.'&code_registration='.$code_registration.'';
						$mail->AltBody = 
						"$name $middlename,
						пожалуйста, нажмите на ссылку ниже для подтверждения обновления почты и номера телефона.
						Если ссылка не работает, скопируйте ее в адресную строку.
						Если сообщение пришло к Вам по ошибке, проигнорируйте его.
						https://tohelptohelp.ru/tohelp/emailver.php?email=$email&code_registration=$code_registration";
						if ($mail->send()) echo 'Письмо отправлено!';
						else echo 'Ошибка: ' . $mail->ErrorInfo;
					}
					else
					{
						$code="my_profile_get_failed";
						$title="Ответ от сервера";
						$message="Данные не обновлены. Повторите попытку или зайдите в приложение снова";
						array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
						echo json_encode($response);
					}
			}
		}
	}
	else
	{
		$code="my_profile_get_failed";
		$title="Ответ от сервера";
		$message="Не все обязательные поля заполнены";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="my_profile_get_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>