<?php
require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$career_objective=mysqli_real_escape_string($con,$_POST["career_objective"]);
$salary=mysqli_real_escape_string($con,$_POST["salary"]);
$employment=mysqli_real_escape_string($con,$_POST["employment"]);
$schedule=mysqli_real_escape_string($con,$_POST["schedule"]);
$marital_status=mysqli_real_escape_string($con,$_POST["marital_status"]);
$business_trips=mysqli_real_escape_string($con,$_POST["business_trips"]);
$moving=mysqli_real_escape_string($con,$_POST["moving"]);
$having_children=mysqli_real_escape_string($con,$_POST["having_children"]);
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(mysqli_num_rows(mysqli_query($con, "SELECT id FROM resume WHERE id like '".$id."'"))>0)
	{
		$sql="UPDATE resume SET career_objective='".$career_objective."',
		salary='".$salary."',
		employment='".$employment."',
		schedule='".$schedule."',
		marital_status='".$marital_status."',
		business_trips='".$business_trips."',
		moving='".$moving."',
		having_children='".$having_children."'
		WHERE id='".$id."';";
	}
	else
	{
		$sql="INSERT INTO resume (id, career_objective, salary, employment, schedule, marital_status, business_trips, moving, having_children)
			VALUES ('".$id."', '".$career_objective."', '".$salary."', '".$employment."', '".$schedule."', '".$marital_status."', '".$business_trips."',
					'".$moving."','".$having_children."');";
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