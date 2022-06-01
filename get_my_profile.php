<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$date_last_visit=date("d.m.Y");
$time_last_visit=date("H:i");

$sql="SELECT surname, name, middlename, child_home, email, phone_number, city, subject_of_country, registration_address, factual_address,
type_of_flat, sex, date_of_born, month_of_born, year_of_born, name_of_photo, status_of_profile FROM information_about_users WHERE id like '".$id."' AND access_token like '".$access_token."';";

$result=mysqli_query($con, $sql);
$response=array();
if (mysqli_num_rows($result)>0)
{
	mysqli_query($con, "UPDATE information_about_users SET date_last_visit='".$date_last_visit."', time_last_visit='".$time_last_visit."' WHERE id='".$id."';");
	$row = mysqli_fetch_row($result);
	$surname=$row[0];
	$name=$row[1];
	$middlename=$row[2];
	$child_home=$row[3];
	$email=$row[4];
	$phone_number=$row[5];
	$city=$row[6];
	$subject=$row[7];
	$registration_address=$row[8];
	$factual_address=$row[9];
	$type_of_flat=$row[10];
	$sex=$row[11];
	$date_of_born=$row[12];
	$month_of_born=$row[13];
	$year_of_born=$row[14];
	$name_of_photo=$row[15];
	$status_of_profile=$row[16];
	$code="success";	
	array_push($response, array("code"=>$code, "surname"=>$surname, "name"=>$name, "middlename"=>$middlename, "child_home"=>$child_home, "email"=>$email, "phone_number"=>$phone_number, 
	"city"=>$city, "subject_of_country"=>$subject, "registration_address"=>$registration_address, "factual_address"=>$factual_address, "type_of_flat"=>$type_of_flat, 
	"sex"=>$sex, "date_of_born"=>$date_of_born, "month_of_born"=>$month_of_born, "year_of_born"=>$year_of_born, "name_of_photo"=>$name_of_photo, "status_of_profile"=>$status_of_profile));
	echo json_encode($response);
}
else
{
	$code="failed";
	$title="Ошибка";
	$message="Пользователь не найден";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>