<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers:*');
include("../modal/Loan.php");

$data=json_decode(file_get_contents("php://input"));

$Loan=New Loan;

$response=$Loan->__getLoanRequests();


if(is_null($response)){
    return null;
    // print_r(json_encode($response));
}else{
     print_r(json_encode($response));
}