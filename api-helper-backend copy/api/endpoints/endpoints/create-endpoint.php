<?php
require_once __DIR__ . '/../../classes/endpoint-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new EndpointHandler();

//check required parameters         MARK:parameters
$reqparameter=['group_id','title','url','method'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
//set all parameters 

//required parameters
$group_id = $data['group_id'];
$title = $data['title'];
$url = $data['url'];
$method = $data['method'];
//optional parameters
$description=$data['description'] ?? NULL; 


//method call
echo $handler->createEndpoint($group_id, $title, $url, $method, $description);

?>