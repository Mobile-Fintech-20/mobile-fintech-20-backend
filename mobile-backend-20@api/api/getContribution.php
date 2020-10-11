<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers:*');
include("../modal/Contribution.php");

$data=json_decode(file_get_contents("php://input"));

$Contribution=New Contribution;

$response=$Contribution->getContributionData();


if(is_null($response)){
    return null;
  
}else{
     print_r(json_encode($response));
}