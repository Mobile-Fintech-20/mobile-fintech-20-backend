<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers:*');
include("../modal/Contribution.php");


/*******
 *  Loan. reg... registers new loan Request
 */
$data=json_decode(file_get_contents("php://input"));

if(!isset($data->amount_contributed) || 
!isset($data->member_id) ||
!isset($data->payment_type_id) 

)
{
    $info=array(
        'status' => 'Fail',
        'details' => array("Paramaters were not specified")
    );
    print_r(json_encode($info));

    exit;
}



$NewLoan=new Contribution;
$NewLoan->Fillables['AmountContributed']=clean($data->amount_contributed);
$NewLoan->Fillables['MemberId']=clean($data->member_id);
$NewLoan->Fillables['PaymentType']=clean($data->payment_type_id);





$result=$NewLoan->__registerContribution();
// print_r($result);
if($result)
{
    $info=array(
        'status' => 'OK',
        'details' => array(
            "info"=>$NewLoan->Success,
            "data"=>$result
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