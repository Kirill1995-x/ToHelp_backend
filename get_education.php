<?php

require "init.php";

$sql="SELECT id, title, url, description FROM education_tohelp";
	
$result=mysqli_query($con, $sql);
$data=array();

while($row=mysqli_fetch_array($result))
{
	array_push($data, array('id'=>$row['id'], 'title'=>$row['title'], 'url'=>$row['url'], 'description'=>$row['description']));
}

echo json_encode(array("data"=>$data));

mysqli_close($con);

?>