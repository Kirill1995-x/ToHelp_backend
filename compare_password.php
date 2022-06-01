<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$password=$_POST["old_password"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	$sql="SELECT password FROM information_about_users WHERE id like '".$id."'";
	$result=mysqli_query($con, $sql);

	if (mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_row($result);
		if(password_verify($password, $row[0]))
		{
			$code="compare_password_success";	
			array_push($response, array("code"=>$code));
			echo json_encode($response);
		}
		else
		{
			$code="compare_password_failed";
			$message="Пароль введен неверно";
			array_push($response, array("code"=>$code, "message"=>$message));
			echo json_encode($response);
		}
	}
	else
	{
		$code="compare_password_failed";
		$message="Неизвестная ошибка";
		array_push($response, array("code"=>$code, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="compare_password_failed";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>