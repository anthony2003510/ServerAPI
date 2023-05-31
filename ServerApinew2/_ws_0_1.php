<?php
include_once __DIR__."/ServerAPI/com/clsXMLUtils.php";
include_once __DIR__."/ServerAPI/com/clsRequest.php";
include_once __DIR__."/ServerAPI/com/clsResponse.php";
include_once __DIR__."/ServerAPI/com/clsErrors.php";
include_once __DIR__."/ServerAPI/com/clsParam.php";
include_once __DIR__."/ServerAPI/com/clsMethod.php";
include_once __DIR__."/ServerAPI/com/clsServerApi.php";
include_once __DIR__."/ServerAPI/bdd/connectDB.php";
include_once __DIR__."/ServerAPI/bdd/clsControllerDB.php";
include_once __DIR__."/Security/clsSecurityControler.php";
include_once __DIR__."/DBUtils/clsUser.php";
include_once __DIR__."/DBUtils/clsSession.php";
include_once __DIR__."/DBUtils/clsCart.php";


$time_start = microtime(true);
$obj_res = new clsResponse(true);
$obj_request= new clsRequest();


$obj_api=new clsServerApi("./ServerAPI/xml/configure.xml");
$obj_api->setServerErrors($obj_res);
$Errs_API = $obj_res->Get_Num_API_Err();


if($Errs_API == 0)
{
    clsResponse::printResponse("El error del SAPI es igual a 0");
    $SC = new clsSecurityControler();
}else
{
    clsResponse::printResponse("El error del SAPI es distinto a 0");   
}


$obj_res->GenerateXML();

?>