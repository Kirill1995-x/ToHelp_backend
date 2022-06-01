<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(mysqli_num_rows(mysqli_query($con, "SELECT id FROM resume WHERE id like '".$id."'"))>0)
	{
		$sql="SELECT career_objective, salary, employment, schedule, marital_status, business_trips, moving, having_children FROM resume WHERE id like '".$id."'";
		$result=mysqli_query($con, $sql);
		if (mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_row($result);
			$career_objective = $row[0];
			$salary = $row[1];
			$employment = $row[2];
			$schedule = $row[3];
			$marital_status = $row[4];
			$business_trips = $row[5];
			$moving = $row[6];
			$having_children = $row[7];
						
			$code="success";	
			array_push($response, array("code"=>$code, "career_objective"=>$career_objective, "salary"=>$salary, "employment"=>$employment, "schedule"=>$schedule, 
										"marital_status"=>$marital_status, "business_trips"=>$business_trips, "moving"=>$moving, "having_children"=>$having_children));
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