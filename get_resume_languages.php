<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$response=array();

if(check_access_token($id, $access_token, $con))
{
	if(mysqli_num_rows(mysqli_query($con, "SELECT id FROM resume WHERE id like '".$id."'"))>0)
	{
		$sql="SELECT languages FROM resume WHERE id like '".$id."'";

		$result=mysqli_query($con, $sql);
		if (mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_row($result);
			$languages = $row[0];
			
			$code="success";	
			array_push($response, array("code"=>$code, "languages"=>$languages));
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