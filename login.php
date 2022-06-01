<?php

require "init.php";

$login=$_POST["login"];
$password=$_POST["password"];
$status_of_profile='1';
$confirmed='1';
$code_registration='0';
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");

$sql="SELECT id, confirmed, code_registration, surname, name, middlename, child_home, email, phone_number, city, subject_of_country, registration_address, factual_address,
type_of_flat, sex, date_of_born, month_of_born, year_of_born, name_of_photo, password, access_token, status_of_profile FROM information_about_users WHERE 
(email like '".$login."' or (phone_number like '".$login."' and confirmed='".$confirmed."' and code_registration='".$code_registration."'))";

$result=mysqli_query($con, $sql);
$response=array();
if (mysqli_num_rows($result)>0)
{
	$row = mysqli_fetch_row($result);
	if(password_verify($password, $row[19]))
	{
		if ($row[1]==1 && $row[2]==0)
		{
			$id = $row[0];
			mysqli_query($con, "UPDATE information_about_users SET date_last_visit='".$date_last_visit."', time_last_visit='".$time_last_visit."' WHERE id='".$id."';"); 
			$filename='images_tohelp/'.$id;
			if (!file_exists($filename)) 
			{
				mkdir($filename, 0777);
			}
			$surname=$row[3];
			$name=$row[4];
			$middlename=$row[5];
			$child_home=$row[6];
			$email=$row[7];
			$phone_number=$row[8];
			$city=$row[9];
			$subject=$row[10];
			$registration_address=$row[11];
			$factual_address=$row[12];
			$type_of_flat=$row[13];
			$sex=$row[14];
			$date_of_born=$row[15];
			$month_of_born=$row[16];
			$year_of_born=$row[17];
			$name_of_photo=$row[18];
			$access_token=$row[20];
			$status_of_profile=$row[21];
			if($status_of_profile=='1')
			{
				$code="login_success";	
				array_push($response, array("code"=>$code, "id"=>$id, "surname"=>$surname, "name"=>$name, "middlename"=>$middlename, "child_home"=>$child_home, 
				"email"=>$email, "phone_number"=>$phone_number, "city"=>$city, "subject_of_country"=>$subject, "registration_address"=>$registration_address, 
				"factual_address"=>$factual_address, "type_of_flat"=>$type_of_flat, "sex"=>$sex, "date_of_born"=>$date_of_born, "month_of_born"=>$month_of_born, 
				"year_of_born"=>$year_of_born, "name_of_photo"=>$name_of_photo, "access_token"=>$access_token));
				echo json_encode($response);
			}
			else
			{
				$code="account_deleted";
				$title="Ответ от сервера";
				$message="Ваш аккаунт был удален. Нажмите 'Восстановить', если хотите восстановить аккаунт. После этого Вам на почту будет направлена ссылка для подтверждения";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message, "email"=>$email));
				echo json_encode($response);
			}
		}
		else
		{
			$code="registration_not_finish";
			$title="Ошибка авторизации";
			$message="Ваша регистрация не была завершена. Перейдите, пожалуйста в свою почту $row[7] и перейдите по ссылке для завершения регистрации.";
			array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
			echo json_encode($response);
		}
	}
	else
	{
		$code="login_failed";
		$title="Ошибка авторизации...";
		$message="Пользователь не найден";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="login_failed";
	$title="Ошибка авторизации...";
	$message="Пользователь не найден";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>