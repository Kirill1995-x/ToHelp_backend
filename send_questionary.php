<?php
require "init.php";

function check_fields($main_target, $problem_education, $problem_flat, $problem_money, $problem_law, $problem_other, 
					  $name_education_institution, $level_of_education, $my_professional, $my_interests)
{
	if(empty($main_target))return false;
	else if(empty($problem_education))return false;
	else if(empty($problem_flat))return false;
	else if(empty($problem_money))return false;
	else if(empty($problem_law))return false;
	else if(empty($problem_other))return false;
	else if(empty($name_education_institution))return false;
	else if(empty($level_of_education))return false;
	else if(empty($my_professional))return false;
	else if(empty($my_interests))return false;
	else return true;
}

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$main_target=mysqli_real_escape_string($con,$_POST["main_target"]);
$problem_education=mysqli_real_escape_string($con,$_POST["problem_education"]);
$problem_flat=mysqli_real_escape_string($con,$_POST["problem_flat"]);
$problem_money=mysqli_real_escape_string($con,$_POST["problem_money"]);
$problem_law=mysqli_real_escape_string($con,$_POST["problem_law"]);
$problem_other=mysqli_real_escape_string($con,$_POST["problem_other"]);
$name_education_institution=mysqli_real_escape_string($con,$_POST["name_education_institution"]);
$level_of_education=mysqli_real_escape_string($con,$_POST["level_of_education"]);
$my_professional=mysqli_real_escape_string($con,$_POST["my_professional"]);
$my_interests=mysqli_real_escape_string($con, $_POST["my_interests"]);
$date_of_last_questionary=date("d.m.Y");
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(check_fields($main_target, $problem_education, $problem_flat, $problem_money, $problem_law, $problem_other, 
					$name_education_institution, $level_of_education, $my_professional, $my_interests))
	{
		$res = mysqli_query($con,"SELECT id_of_user FROM questionary WHERE id_of_user = '".$id."'");
		$count = mysqli_num_rows($res);
		//---
		if($count>0)
		{
			$sql="UPDATE questionary SET main_target='".$main_target."',
			problem_education='".$problem_education."',
			problem_flat='".$problem_flat."',
			problem_money='".$problem_money."',
			problem_law='".$problem_law."',
			problem_other='".$problem_other."',
			name_education_institution='".$name_education_institution."',
			level_of_education='".$level_of_education."',
			my_professional='".$my_professional."',
			my_interests='".$my_interests."',
			date_of_last_questionary='".$date_of_last_questionary."'
			WHERE id_of_user='".$id."';";

			$result=mysqli_query($con,$sql);

			if ($result==true)
			{
				$code="questionary_get_success";
				$title="Ответ от сервера";
				$message="Данные анкетирования успешно приняты";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
			}
			else
			{
				$code="questionary_get_failed";
				$title="Ответ от сервера";
				$message="Данные анкетирования не приняты. Повторите попытку или зайдите в приложение снова";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
			}
		}
		else
		{
			$sql="INSERT INTO questionary(id_of_user, main_target, problem_education, problem_flat, problem_money, problem_law, problem_other,
			                              name_education_institution, level_of_education, my_professional, my_interests, date_of_last_questionary) 
			      VALUES('".$id."', '".$main_target."', '".$problem_education."', '".$problem_flat."', '".$problem_money."', '".$problem_law."', '".$problem_other."',
				        '".$name_education_institution."', '".$level_of_education."', '".$my_professional."', '".$my_interests."', '".$date_of_last_questionary."')";

			$result=mysqli_query($con,$sql);

			if ($result==true)
			{
				$code="questionary_get_success";
				$title="Ответ от сервера";
				$message="Данные анкетирования успешно приняты";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
			}
			else
			{
				$code="questionary_get_failed";
				$title="Ответ от сервера";
				$message="Данные анкетирования не приняты. Повторите попытку или зайдите в приложение снова";
				array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
				echo json_encode($response);
			}
		}
	}
	else
	{
		$code="questionary_get_failed";
		$title="Ответ от сервера";
		$message="Не все обязательные поля заполнены";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="questionary_get_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>