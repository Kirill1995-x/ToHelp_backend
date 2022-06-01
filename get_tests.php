<?php

require "init.php";

$number = $_GET["number"];

$sql="SELECT id, name_of_test, description_of_test, link_to_test FROM tests_tohelp WHERE number='".$number."'";
	
$result=mysqli_query($con, $sql);
$data=array();

while($row=mysqli_fetch_array($result))
{
	array_push($data, array('id'=>$row['id'], 'name_of_test'=>$row['name_of_test'], 
	'description_of_test'=>$row['description_of_test'], 'link_to_test'=>$row['link_to_test']));
}

echo json_encode(array("data"=>$data));

mysqli_close($con);

?>