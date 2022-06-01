<?php

require "init.php";

$id=$_POST["id"];
$access_token=$_POST["access_token"];
$subject=mysqli_real_escape_string($con,$_POST["subject"]);
$type_of_request=mysqli_real_escape_string($con,$_POST["type_of_request"]);
$status_of_profile=1;
$status_of_busy=1;
$confirmed=1;
$code_registration=0;
$confirmed_email=1;
$email_registration=0;
$response=array();

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

	$result=mysqli_query($con,$sql);

	if(mysqli_num_rows($result)>0)
	{
		$code="find_specialist_success";
		array_push($response, array("code"=>$code));
		echo json_encode($response);
	}
	else
	{
		$code="find_specialist_failed";
		$title="Ответ от сервера";
		$message="К сожалению, по заданным критериям не было найдено ни одного специалиста. Попробуйте изменить параметры запроса";
		array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
		echo json_encode($response);
	}
}
else
{
	$code="find_specialist_failed";
	$title="Ответ от сервера";
	$message="В доступе отказано";
	array_push($response, array("code"=>$code, "title"=>$title, "message"=>$message));
	echo json_encode($response);
}
mysqli_close($con);

?>