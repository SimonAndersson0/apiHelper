<?php
require_once __DIR__ . '/../../classes/parameter-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new ParametersHandler();

//check required parameters         MARK:parameters
$reqparameter=['endpoint_id','name','type','required'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
//set all parameters 

//required parameters
$endpoint_id = $data['endpoint_id'];
$name = $data['name'];
$type = $data['type'];
$required = $data['required']; //true or false should work i think
//optional parameters
$description=$data['description'] ?? NULL; 


//method call
echo $handler->createParameter($endpoint_id, $name, $type, $required, $description);

?>