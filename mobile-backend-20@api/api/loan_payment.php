<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, ContentType, Access-Control-Allow-Methods, Authorization, X-Requested-With');
include("../modal/Loan.php");


/*******
 *  Loan. approval
 */
$data=json_decode(file_get_contents("php://input"));

if(
!isset($data->loan_id) ||
!isset($data->member_id)
)
{
    $info=array(
        'status' => 'Fail',
        'details' => array("Paramaters were not specified")
    );
    print_r(json_encode($info));

    exit;
}



$NewLoan=new Loan;
$NewLoan->ApplicationNo=clean($data->loan_id);
$NewLoan->MemberId=clean($data->member_id);
$result=false;

if($NewLoan->returnLoanDetails()){
    $result=$NewLoan->__loanpayment($NewLoan-> __payLoan());
}

if($result)
{
    $info=array(
        'status' => 'OK',
        'details' => array(
            "info"=>$NewLoan->Success,
            )
    );
    print_r(json_encode($info));
}
else
{


   
    $info=array(
        'status' => 'Fail',
        'details' => array($NewLoan->Error)
    );
    print_r(json_encode($info));

}



    

?>