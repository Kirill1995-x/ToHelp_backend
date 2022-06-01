<?php

require "init.php";

$id_of_user=$_GET["id"];
$access_token=$_GET["access_token"];

if(check_access_token($id_of_user, $access_token, $con))
{
	$sql="SELECT request_to_specialist.id, 
				 request_to_specialist.type_of_request, 
				 request_to_specialist.status,
				 request_to_specialist.id_of_specialist,
				 information_about_specialist.type_of_specialist,
				 information_about_specialist.surname, 
				 information_about_specialist.name, 
				 information_about_specialist.middlename, 
				 information_about_specialist.email,
				 information_about_specialist.phone_number, 
				 information_about_specialist.call_hours,
				 information_about_specialist.name_of_photo,
				 request_to_specialist.message_of_user
		 FROM request_to_specialist, information_about_specialist
		 WHERE request_to_specialist.id_of_user='".$id_of_user."' and 
			   request_to_specialist.id_of_specialist>0 and
			   request_to_specialist.id_of_specialist=information_about_specialist.id and
			   request_to_specialist.status>0 and request_to_specialist.status<3";
		
	$result=mysqli_query($con, $sql);
	$data=array();

	while($row=mysqli_fetch_array($result))
	{
		array_push($data, array('id'=>$row['id'], 'type_of_request'=>$row['type_of_request'], 'status'=>$row['status'], 'id_of_specialist'=>$row['id_of_specialist'], 
		'type_of_specialist'=>$row['type_of_specialist'], 'surname'=>$row['surname'], 'name'=>$row['name'], 'middlename'=>$row['middlename'], 
		'phone_number'=>$row['phone_number'], 'call_hours'=>$row['call_hours'], 'message_of_user'=>$row['message_of_user'], 'photo_of_specialist'=>$row['name_of_photo']));
	}

	echo json_encode(array("data"=>$data));
}
mysqli_close($con);

?>