<?php 
include_once("../include/class.database.php");
include_once("../include/functions.php");
include_once("app.php");

	class Member extends App
	{
            

            //basic properties
            public $Email;
            public $FirstName;
            public $Password;
            public $DesignationID;
            public $ContactID;
            public $PinNo;
            public $UserID;
            
          
            public $Fillables=array(
                "Email"=>"",
                "FirstName"=>"",
                "LastName"=>"",
                "Password"=>"",
                "RePassword"=>"",
                // "ContactID"=>"",
                // "PinNo" =>""
            );
            
            
            //  the role with new reques
            // todo adding phone numbers to the database with phone number
            function __signup($designation_id)
            {
                $db=Database::getInstance();
                $mysqli=$db->getConnection();

                foreach($this->Fillables as $field=>$value)
                // checking if any of the fields is null
                {
                    if(len($value))
                    {
                        $this->Error="Oops! All fields are required";

                        return false;
                    }
                }
               // cleaning the passwornd to check for white spaces
                $RePassword=clean($this->Fillables['RePassword']);
                $Password=clean($this->Fillables['Password']);

                if($RePassword!=$Password)
                {
                    $this->Error="Oops! Passwords do not match";
                    return false;
                }
            
                if(!filter_var(($this->Fillables['Email']), FILTER_VALIDATE_EMAIL))
                {
                    $this->Error="Oops! Enter a valid email address and try again";
                    return false;
                }
            

            
                $Password=sha1($Password);



               $query_signup="INSERT INTO `Member`(`first_name`,`last_name`,`email`, `Designation_ID`,`password`)VALUES(";
               $query_signup.="'{$this->Fillables['FirstName']}', '{$this->Fillables['LastName']}','{$this->Fillables['Email']}',";
               $query_signup.="'$designation_id',";
               $query_signup.="'$Password'";
               $query_signup.=")";

                $result_signup=$mysqli->query($query_signup);
                echo $mysqli->error;
                // returning the last inserted id 
                $user_id=$mysqli->insert_id;
                $this->Success="member added";
                return $user_id;


            }

            



            function __login_user()
            {
                $db=Database::getInstance();
                $mysqli=$db->getConnection();
                
                $this->Password=sha1($this->Password);//encrypt the password

                //check in the user table for the email and password
                $query_chk="SELECT `member_id`, `first_name` FROM `Member` WHERE `email`='$this->Email'";
                $query_chk.=" AND `password`='$this->Password'";
                $result_chk=$mysqli->query($query_chk);
                echo $mysqli->error;
                $num_chk=$result_chk->num_rows;
                if($num_chk>0)//check if a record exists in the recordset
                {
                    $row_chk=$result_chk->fetch_assoc();
                    $this->FirstName=$row_chk['first_name'];
                    $this->UserID=$row_chk['member_id'];
                    return true;
                }
                $this->Error="Incorrect email or password";
                return false;
            }
            function  getData(){

                $db=Database::getInstance();
                $mysqli=$db->getConnection();
                $query_chk="SELECT * FROM `Member` ";
                $result_chk=$mysqli->query($query_chk);
                echo $mysqli->error;
                $num_chk=array();
                while($row = $result_chk->fetch_assoc()){
        
                    array_push($num_chk,$row);
                    
                }
                return $num_chk;
                // $this->Error="Incorrect email or password";
                // return false;
            }
                      
    
    }//ends class user

    // function get_user_specs_loansapplication(){
    //     $db=Database::getInstance();
    //             $mysqli=$db->getConnection();
    //             $query_chk="SELECT Member.*,LoanApplication.* FROM `Member` INNER JOIN LoanApplication ON LoanApplication.member_id=Member.member_id";
                
    //             $result_chk=$mysqli->query($query_chk);
    //             echo $mysqli->error;
    //             $num_chk=array();
    //             while($row = $result_chk->fetch_assoc()){
        
    //                 array_push($num_chk,$row);
                    
    //             }
    //             return $num_chk;
    //             // $this->Error="Incorrect email or password";
    //             // return false;
    // }



   
    ?>



