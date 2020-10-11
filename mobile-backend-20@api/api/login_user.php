<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');
include("../modal/Member.php");


/*******
 * user login 
 */
$data=json_decode(file_get_contents("php://input"));

if(!isset($data->email) || 
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

$NewLogin=new Member;//Instantiating the Member class

//The login function requires the Email and Password parameters to be set 
//so let's set the parameters with values extracted from the form
$NewLogin->Email=clean($data->email);
$NewLogin->Password=clean($data->password);

$logged_in=$NewLogin->__login_user(); //call the function store it's results in a variable i.e $logged_in

if($logged_in)//check if true was returned
{
    //prepare an associative array containing login returned data
    $info=array(
        'status' => 'OK',
        'details' => array(
            "first_name"=>$NewLogin->FirstName,
            "user_id"=>$NewLogin->UserID
            )
    );

    print_r(json_encode($info));//convert the array into a json object and print it
}
else//option if the function returned false
{
   //prepare an associative array containing login error
    $info=array(
        'status' => 'Fail',
        'details' => array($NewLogin->Error)
    );
    print_r(json_encode($info));//convert the array into a json object and print it

}

?>