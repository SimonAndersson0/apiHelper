<?php
require_once __DIR__ . '/../../classes/group-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new GroupHandler();

//check required parameters         MARK:parameters
/*
$reqparameter=[];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
*/

//set all parameters 

//required parameters

//optional parameters
$projectId = $data['project_id'] ?? NULL;


//method call
echo $handler->listGroups($project_id = null);

?>