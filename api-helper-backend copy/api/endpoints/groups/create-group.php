<?php
require_once __DIR__ . '/../../classes/GroupHandler.php';
require_once __DIR__ . '/../../helpers/Response.php';

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
$parent_id=$input['parent_id'] ?? NULL; //default to empty string if not provided only needed for non required parameters


//method call
echo $handler->createGroup($project_id, $name, $parent_id);

?>