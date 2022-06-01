<?php

require "init.php";

$id=$_GET["id"];
$access_token=$_GET["access_token"];
$subject=$_GET["subject"];
$type_of_request=$_GET["type_of_request"];
$status_of_profile=1;
$status_of_busy=1;
$confirmed=1;
$code_registration=0;
$confirmed_email=1;
$email_registration=0;

if(check_access_token($id, $access_token, $con))
{
	if($type_of_request==1)//педагог-психолог
	{
		$sql="SELECT * FROM information_about_specialist where status_of_profile like '".$status_of_profile."' and type_of_specialist like 'Педагог-психолог' and status_of_busy like '".$status_of_busy."' and subject_of_country like '".$subject."' and
		confirmed like '".$confirmed."' and code_registration like '".$code_registration."' and confirmed_email like '".$confirmed_email."' and email_registration like '".$email_registration."';";
	}
	else if($type_of_request==2)//юрист
	{
		$sql="SELECT * FROM information_about_specialist where status_of_profile like '".$status_of_profile."' and type_of_specialist like 'Юрист' and status_of_busy like '".$status_of_busy."' and subject_of_country like '".$subject."' and
		confirmed like '".$confirmed."' and code_registration like '".$code_registration."' and confirmed_email like '".$confirmed_email."' and email_registration like '".$email_registration."';";
	}
	else if($type_of_request==3)//социальный педагог и специалист по социальной работе
	{
		$sql="SELECT * FROM information_about_specialist where status_of_profile like '".$status_of_profile."' and (type_of_specialist like 'Социальный педагог' or type_of_specialist like 'Специалист по социальной работе') and status_of_busy like '".$status_of_busy."' and subject_of_country like '".$subject."' and
		confirmed like '".$confirmed."' and code_registration like '".$code_registration."' and confirmed_email like '".$confirmed_email."' and email_registration like '".$email_registration."';";
	}
	else if($type_of_request==4)//специалист по социальной работе
	{
		$sql="SELECT * FROM information_about_specialist where status_of_profile like '".$status_of_profile."' and type_of_specialist like 'Специалист по социальной работе' and status_of_busy like '".$status_of_busy."' and subject_of_country like '".$subject."' and
		confirmed like '".$confirmed."' and code_registration like '".$code_registration."' and confirmed_email like '".$confirmed_email."' and email_registration like '".$email_registration."';";	
	}
	else if($type_of_request==5)//педагог и специалист по социальной работе
	{
		$sql="SELECT * FROM information_about_specialist where status_of_profile like '".$status_of_profile."' and (type_of_specialist like 'Социальный педагог' or type_of_specialist like 'Специалист по социальной работе') and status_of_busy like '".$status_of_busy."' and subject_of_country like '".$subject."' and
		confirmed like '".$confirmed."' and code_registration like '".$code_registration."' and confirmed_email like '".$confirmed_email."' and email_registration like '".$email_registration."';";
	}
	else if($type_of_request==6)//педагог и специалист по социальной работе
	{
		$sql="SELECT * FROM information_about_specialist where status_of_profile like '".$status_of_profile."' and (type_of_specialist like 'Социальный педагог' or type_of_specialist like 'Специалист по социальной работе') and status_of_busy like '".$status_of_busy."' and subject_of_country like '".$subject."' and
		confirmed like '".$confirmed."' and code_registration like '".$code_registration."' and confirmed_email like '".$confirmed_email."' and email_registration like '".$email_registration."';";
	}
	else if($type_of_request==7)//специалист по социальной работе
	{
		$sql="SELECT * FROM information_about_specialist where status_of_profile like '".$status_of_profile."' and type_of_specialist like 'Специалист по социальной работе' and status_of_busy like '".$status_of_busy."' and subject_of_country like '".$subject."' and
		confirmed like '".$confirmed."' and code_registration like '".$code_registration."' and confirmed_email like '".$confirmed_email."' and email_registration like '".$email_registration."';";	
	}
		
	$result=mysqli_query($con, $sql);
	$data=array();

	while($row=mysqli_fetch_array($result))
	{
		array_push($data, array('id'=>$row['id'], 'type_of_specialist'=>$row['type_of_specialist'], 'surname'=>$row['surname'], 'name'=>$row['name'], 'middlename'=>$row['middlename'], 
								'call_hours'=>$row['call_hours'], 'phone_number'=>$row['phone_number'], 'name_of_photo'=>$row['name_of_photo']));
	}

	echo json_encode(array("data"=>$data));
}
mysqli_close($con);

?>