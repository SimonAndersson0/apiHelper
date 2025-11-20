<?php
require_once __DIR__ . '/../../classes/GroupHandler.php';
require_once __DIR__ . '/../../helpers/Response.php';

$handler = new GroupHandler();

//check required parameters         MARK:parameters
$reqparameter=['title','project_id'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
//set all parameters 

//required parameters
$title = $data['title'];
$projectId = $data['project_id'];
//optional parameters
$content=$input['content'] ?? ''; //default to empty string if not provided only needed for non required parameters


//method call
echo $handler->editGroup($id, $project_id = null, $name = null, $parent_id = null);

?>