<?php
require_once __DIR__ . '/../../classes/endpoint-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new EndpointHandler();

//check required parameters         MARK:parameters
/* 
$reqparameter=['id'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
 */
//set all parameters 

//required parameters


//optional parameters
$group_id=$data[$group_id] ?? NULL

//method call
echo $handler->listEndpoints($group_id);

?>