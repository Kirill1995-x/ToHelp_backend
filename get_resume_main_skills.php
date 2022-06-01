<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(mysqli_num_rows(mysqli_query($con, "SELECT id FROM resume WHERE id like '".$id."'"))>0)
	{
		$sql="SELECT basic_skills, program, computer_skills, military_service, drivers_licences FROM resume WHERE id like '".$id."'";

		$result=mysqli_query($con, $sql);
		if (mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_row($result);
			$basic_skills = $row[0];
			$program = $row[1];
			$computer_skills = $row[2];
			$military_service = $row[3];
			$drivers_licences = $row[4];
			
			$code="success";	
			array_push($response, array("code"=>$code, "basic_skills"=>$basic_skills, "program"=>$program, "computer_skills"=>$computer_skills, 
										"military_service"=>$military_service, "drivers_licences"=>$drivers_licences));
			echo json_encode($response);
		}
		else
		{
			$code="not_created";
			array_push($response, array("code"=>$code));
			echo json_encode($response);
		}
	}
	else
	{
		$code="not_created";
		array_push($response, array("code"=>$code));
		echo json_encode($response);
	}
}
else
{
	$code="failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>