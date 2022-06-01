<?php
require "init.php";

$surname=mysqli_real_escape_string($con,$_POST["surname"]);
$name=mysqli_real_escape_string($con,$_POST["name"]);
$middlename=mysqli_real_escape_string($con,$_POST["middlename"]);
$check_number=mysqli_real_escape_string($con,$_POST["check_number"]);

$sql="SELECT * FROM check_number WHERE surname_of_user = '".$surname."' AND 
									   name_of_user = '".$name."' AND 
									   middlename_of_user = '".$middlename."' AND 
									   check_number = '".$check_number."';";
$result=mysqli_query($con, $sql);
$response=array();

if (mysqli_num_rows($result)>0)
{
	$code="success";
	$title="Проверка данных";
	$message="Проверка выполнена успешно";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
else
{
	$code="failed";
	$title="Проверка данных";
	$message="Пользователь с такими данными не найден";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}

mysqli_close($con);

?>