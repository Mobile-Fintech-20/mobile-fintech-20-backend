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

if(!isset($data->amount_approved) || 
!isset($data->loan_id) 

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
$NewLoan->Fillables['AmountApproved']=clean($data->amount_approved);
$result=$NewLoan->__approveLoan(clean($data->loan_id));


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