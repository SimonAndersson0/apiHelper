<?php
require_once __DIR__ . '/../../classes/parameter-handler.php';
require_once __DIR__ . '/../../helpers/response.php';

$handler = new ParametersHandler();

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


//method call
echo $handler->deleteParameter($id);

?>