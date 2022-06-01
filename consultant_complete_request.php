<?php
require "init.php";

$id=$_POST["id"];
$id_of_user=$_POST["id_of_user"];
$access_token=$_POST["access_token"];
$status=$_POST["status"];
$response=array();

if(check_access_token($id_of_user, $access_token, $con))
{
	$sql="UPDATE request_to_specialist SET status='".$status."' WHERE id='".$id."'";

	$result=mysqli_query($con,$sql);

	if ($result==true)
	{
		$code="change_status_success";
		array_push($response, array("code"=>$code));
		echo json_encode($response);
	}
	else
	{
		$code="change_status_failed";
		$title="Ответ от сервера";
		$message="Не удалось обновить статус запроса. Повторите попытку позже";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="change_status_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>