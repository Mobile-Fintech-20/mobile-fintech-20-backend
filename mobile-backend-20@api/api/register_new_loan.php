<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');
include("../modal/Loan.php");


/*******
 *  Loan. reg... registers new loan Request
 */
$data=json_decode(file_get_contents("php://input"));


if(!isset($data->amount_applied) || 
!isset($data->interest) ||
!isset($data->repay_peroid) ||
!isset($data->member_id) ||
!isset($data->loan_type_id) ||
!isset($data->member_id_gr) ||
!isset($data->amount_gr) 
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
$NewLoan->Fillables['AmountApplied']=clean($data->amount_applied);
$NewLoan->Fillables['Interest']=clean($data->interest);
$NewLoan->Fillables['RepaymentPeriod']=clean($data->repay_peroid);
$NewLoan->Fillables['MemberId']=clean($data->member_id);
$NewLoan->Fillables['LoanType']=clean($data->loan_type_id);
$NewLoan->Fillables['MemberIdGuarant']=clean($data->member_id_gr);
$NewLoan->Fillables['AmountGuaranteed']=clean($data->amount_gr);



$result=$NewLoan->__registerNewLoanApplication();
$NewLoan->LoanID=$result;
$result1=$NewLoan->__registerNewGuarant();
if($result1)
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