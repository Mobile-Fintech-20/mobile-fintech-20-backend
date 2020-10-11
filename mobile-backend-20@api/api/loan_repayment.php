<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');
include("../modal/Loan.php");


/*******
 *  Loan. repayments
 */
$data=json_decode(file_get_contents("php://input"));

if(
!isset($data->loan_id) ||
!isset($data->member_id) ||
!isset($data->amount_paid) ||
!isset($data->payment_id)
)
{
    $info=array(
        'status' => 'Fail',
        'details' => array("Paramaters were not specified")
    );
    print_r(json_encode($info));

    exit;
}



$NewLoanPayment=new Loan;
$NewLoanPayment->ApplicationNo=clean($data->loan_id);
$NewLoanPayment->MemberId=clean($data->member_id);
$NewLoanPayment->amountpaid=clean($data->amount_paid);
$NewLoanPayment->paymentID=clean($data->payment_id);

$result=$NewLoanPayment->__loanRepayment();


if($result)
{
    $info=array(
        'status' => 'OK',
        'details' => array(
            "info"=>$NewLoanPayment->Success,
            )
    );
    print_r(json_encode($info));
}
else
{


   
    $info=array(
        'status' => 'Fail',
        'details' => array($NewLoanPayment->Error)
    );
    print_r(json_encode($info));

}



    

?>