<?php
require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$basic_skills=mysqli_real_escape_string($con,$_POST["basic_skills"]);
$program=mysqli_real_escape_string($con, $_POST["program"]);
$computer_skills=mysqli_real_escape_string($con, $_POST["computer_skills"]);
$military_service=mysqli_real_escape_string($con,$_POST["military_service"]);
$drivers_licences=mysqli_real_escape_string($con,$_POST["drivers_licences"]);
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(mysqli_num_rows(mysqli_query($con, "SELECT id FROM resume WHERE id like '".$id."'"))>0)
	{
		$sql="UPDATE resume SET basic_skills='".$basic_skills."',
		program='".$program."',
		computer_skills='".$computer_skills."',
		military_service='".$military_service."',
		drivers_licences='".$drivers_licences."'
		WHERE id='".$id."';";
	}
	else
	{
		$sql="INSERT INTO resume (id, basic_skills, program, computer_skills, military_service, drivers_licences,
								  personal_characteristics, hobby, wishes_for_work)
			VALUES ('".$id."', '".$basic_skills."', '".$program."', '".$computer_skills."', '".$military_service."', '".$drivers_licences."');";
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