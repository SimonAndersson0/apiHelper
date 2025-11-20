<?php
require_once __DIR__ . '/../../classes/group-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new GroupHandler();

//check required parameters         MARK:parameters
$reqparameter=['id','project_id'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
//set all parameters 

//required parameters
$id = $data['id'];
$projectId = $data['project_id'];
//optional parameters
$name=$input['name'] ?? NULL;
$parent_id=$input['parent_id'] ?? NULL;


//method call
echo $handler->editGroup($id, $project_id, $name, $parent_id );

?>