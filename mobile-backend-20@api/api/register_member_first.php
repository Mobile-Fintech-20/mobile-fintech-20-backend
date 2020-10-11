<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, ContentType, Access-Control-Allow-Methods, Authorization, X-Requested-With');
include("../modal/Member.php");


/*******
 * user registers new account
 */
$data=json_decode(file_get_contents("php://input"));

if(!isset($data->fname) || 
!isset($data->lname) ||
!isset($data->re_password) ||
!isset($data->email) ||
!isset($data->password) 

)
{
    $info=array(
        'status' => 'Fail',
        'details' => array("Paramaters were not specified")
    );
    print_r(json_encode($info));

    exit;
}


$NewMember=new Member;
$NewMember->Fillables['FirstName']=clean($data->fname);
$NewMember->Fillables['LastName']=clean($data->lname);
$NewMember->Fillables['Password']=clean($data->password);
$NewMember->Fillables['RePassword']=clean($data->re_password);
$NewMember->Fillables['Email']=clean($data->email);




$registered=$NewMember->__signup(1,1);

if($registered)
{
    $info=array(
        'status' => 'OK',
        'details' => array(
            "info"=>$NewMember->Success,
            "user"=>$registered
            )
    );
    print_r(json_encode($info));
}
else
{


   
    $info=array(
        'status' => 'Fail',
        'details' => array($NewMember->Error)
    );
    print_r(json_encode($info));

}



    

?>