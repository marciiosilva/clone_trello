<?php 
require_once "ProjectManagement.php";
$projectManagement = new ProjectManagement();
$id = $_GET["card_id"];

$result = $projectManagement->getCard($id);
die(json_encode($result[0]));
?>