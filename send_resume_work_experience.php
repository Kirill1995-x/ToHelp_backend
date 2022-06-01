<?php
require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$work=mysqli_real_escape_string($con,$_POST["work"]);
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(mysqli_num_rows(mysqli_query($con, "SELECT id FROM resume WHERE id like '".$id."'"))>0)
	{
		$sql="UPDATE resume SET work='".$work."' WHERE id='".$id."';";
	}
	else
	{
		$sql="INSERT INTO resume (id, work) VALUES ('".$id."', '".$work."');";
	}

	$result=mysqli_query($con,$sql);

	if ($result==true)
	{
		$code="resume_get_success";
		$title="Ответ от сервера";
		$message="Данные резюме успешно приняты";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
	else
	{
		$code="resume_get_failed";
		$title="Ответ от сервера";
		$message="Данные резюме не приняты. Повторите попытку или зайдите в приложение снова";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="resume_get_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>