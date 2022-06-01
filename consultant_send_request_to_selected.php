<?php

require "init.php";

$id_of_user=$_POST["id_of_user"];
$access_token=$_POST["access_token"];
$status='1';
$type_of_request=mysqli_real_escape_string($con,$_POST["type_of_request"]);
$subject_of_country=mysqli_real_escape_string($con,$_POST["subject_of_country"]);
$message=mysqli_real_escape_string($con,$_POST["message"]);
$id_of_specialist=$_POST["id_of_specialist"];
$date=date("d.m.Y");
$time=date("H:i");
$response=array();

if(check_access_token($id_of_user, $access_token, $con))
{
	$sql="INSERT INTO request_to_specialist (type_of_request, subject_of_country, status, id_of_user, message_of_user, TIME_sent_user, DATE_sent_user, id_of_specialist)
		  VALUES ('".$type_of_request."','".$subject_of_country."','".$status."','".$id_of_user."','".$message."','".$time."','".$date."','".$id_of_specialist."')";


	$result=mysqli_query($con,$sql);
	if($result)
	{
		$code="request_call_success";
		$title="Ответ от сервера";
		$message="Ваш запрос принят";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
	else
	{
		$code="request_call_failed";
		$title="Ответ от сервера";
		$message="Ваш запрос не был принят. Повторите попытку";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="request_call_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>