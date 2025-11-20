<?php
require_once __DIR__ . '/../../classes/group-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new GroupHandler();

//check required parameters         MARK:parameters
$reqparameter=['name','project_id'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
//set all parameters 

//required parameters
$name = $data['name'];
$project_id = $data['project_id'];
//optional parameters
$parent_id=$data['parent_id'] ?? NULL; 


//method call
echo $handler->createGroup($project_id, $name, $parent_id);

?>