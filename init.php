<?php

$host=;
$db_user=;
$db_password=;
$db_name=;

$con=mysqli_connect($host, $db_user, $db_password, $db_name);

mysqli_set_charset($con,"utf8");

function check_access_token($id, $access_token, $con)
{
	$sql = "SELECT status_of_profile FROM information_about_users WHERE id like '".$id."' AND access_token like '".$access_token."'";
	$result=mysqli_query($con, $sql);
	if (mysqli_num_rows($result)>0)
	{
		$row = mysqli_fetch_row($result);
		if($row[0]=='1')return true;
		else return false;
	}
	else return false;
}

?>
