<?php

require "init.php";

$id_of_user=$_GET["id"];
$access_token=$_GET["access_token"];

if(check_access_token($id_of_user, $access_token, $con))
{
	$sql="SELECT request_to_specialist.id, 
				 request_to_specialist.type_of_request, 
				 request_to_specialist.status,
				 request_to_specialist.message_of_user
		 FROM request_to_specialist
		 WHERE request_to_specialist.id_of_user='".$id_of_user."' and 
			   request_to_specialist.id_of_specialist<1 and	
			   request_to_specialist.status='1'";
		
	$result=mysqli_query($con, $sql);
	$data=array();

	while($row=mysqli_fetch_array($result))
	{
		array_push($data, array('id'=>$row['id'], 'type_of_request'=>$row['type_of_request'], 'status'=>$row['status'],'message_of_user'=>$row['message_of_user']));
	}

	echo json_encode(array("data"=>$data));
}
mysqli_close($con);

?>