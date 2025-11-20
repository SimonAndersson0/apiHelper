<?php
require_once __DIR__ . '/../../classes/parameter-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new ParametersHandler();

//check required parameters         MARK:parameters
$reqparameter=['endpoint_id'];
foreach($reqparameter as $param){
    if(!isset($data[$param])){
        Response::error("Missing parameter: ".$param);
    }
}
//set all parameters 

//required parameters
$endpoint_id = $data['endpoint_id'];

//optional parameters


//method call
echo $handler->getParametersByEndpoint($endpoint_id);

?>