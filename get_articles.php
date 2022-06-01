<?php

require "init.php";

$id_of_articles = $_GET["id_of_articles"];

if($id_of_articles=='f') $sql="SELECT id, name_of_article, link_to_article FROM articles_tohelp";
else if($id_of_articles=='k') $sql="SELECT id, name_of_article, link_to_article FROM articles1_tohelp";
else if($id_of_articles=='b') $sql="SELECT id, name_of_article, link_to_article FROM articles2_tohelp";
else if($id_of_articles=='l') $sql="SELECT id, name_of_article, link_to_article FROM articles3_tohelp";
else $sql="SELECT id, name_of_article, link_to_article FROM articles_tohelp";

	
$result=mysqli_query($con, $sql);
$data=array();

while($row=mysqli_fetch_array($result))
{
	array_push($data, array('id'=>$row['id'], 'name_of_article'=>$row['name_of_article'], 'link_to_article'=>$row['link_to_article']));
}

echo json_encode(array("data"=>$data));

mysqli_close($con);

?>