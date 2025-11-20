<?php
require_once __DIR__ . '/../../classes/endpoint-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new EndpointHandler();

//check required parameters         MARK:parameters
$reqparameter=['id'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
//set all parameters 

//required parameters
$id = $data['id'];

//optional parameters
$group_id=$input['group_id'] ?? NULL; 
$title=$input['title'] ?? NULL; 
$url=$input['url'] ?? NULL; 
$method=$input['method'] ?? NULL; 
$description=$input['description'] ?? NULL; 


//method call
echo $handler->editEndpoint($id, $group_id = null, $title = null, $url = null, $method = null, $description = null);

?>