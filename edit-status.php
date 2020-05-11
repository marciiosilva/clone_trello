<?php 
require_once "ProjectManagement.php";
$projectManagement = new ProjectManagement();
$tipo = $_GET["task_tipo"];

if($tipo == 'banner'){
    $status_id = $_GET["status_id"];
    $status_name = $_GET["status_name"];
    $result = $projectManagement->editStatus($status_name, $status_id);

}else if($tipo == 'card'){
    $status_id = $_GET["status_id"];
    $task_id = $_GET["task_id"];
    $result = $projectManagement->editTaskStatus($status_id, $task_id);
}
?>