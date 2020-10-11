<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, ContentType, Access-Control-Allow-Methods, Authorization, X-Requested-With');
include("../modal/Member.php");

$data=json_decode(file_get_contents("php://input"));

$Members=New Member;

$response=$Members->getData();


if(is_null($response)){
    return null;
    // print_r(json_encode($response));
}else{
     print_r(json_encode($response));
}