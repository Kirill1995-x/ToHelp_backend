<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(mysqli_num_rows(mysqli_query($con, "SELECT id FROM resume WHERE id like '".$id."'"))>0)
	{
		$sql="SELECT career_objective, salary, employment, schedule, marital_status, business_trips, moving, 
					 having_children, basic_skills, program, computer_skills, military_service, drivers_licences, 
					 personal_characteristics, hobby, wishes_for_work, languages, courses, projects, education, work FROM resume WHERE id like '".$id."'";

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
			$basic_skills = $row[8];
			$program = $row[9];
			$computer_skills = $row[10];
			$military_service = $row[11];
			$drivers_licences = $row[12];
			$personal_characteristics = $row[13];
			$hobby = $row[14];
			$wishes_for_work = $row[15];
			$languages = $row[16];
			$courses = $row[17];
			$projects = $row[18];
			$education = $row[19];
			$work = $row[20];
			$code="success";	
			array_push($response, array("code"=>$code, "career_objective"=>$career_objective, "salary"=>$salary, "employment"=>$employment, "schedule"=>$schedule, 
										"marital_status"=>$marital_status, "business_trips"=>$business_trips, "moving"=>$moving, "having_children"=>$having_children,
										"basic_skills"=>$basic_skills, "program"=>$program, "computer_skills"=>$computer_skills, "military_service"=>$military_service, 
										"drivers_licences"=>$drivers_licences, "personal_characteristics"=>$personal_characteristics, "hobby"=>$hobby, "wishes_for_work"=>$wishes_for_work, 
										"languages"=>$languages, "courses"=>$courses, "projects"=>$projects, "education"=>$education, "work"=>$work));
			echo json_encode($response);
		}
		else
		{
			$code="failed";
			$title="Ответ от сервера";
			$message="Пользователь не найден";
			array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
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
