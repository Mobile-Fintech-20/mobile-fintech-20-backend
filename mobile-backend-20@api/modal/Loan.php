<?php 
include_once("../include/class.database.php");
include_once("../include/functions.php");
include_once("app.php");

class Loan extends App
{
    public $ApplicationNo;
    public $MemberId;
    public $AmountApproved;
    public $LoanID;
    public $interest;
    public $months;
    public $currentbalance;
    public $amountpaid;
    public $paymentID;
    
    public $Fillables=array(
        "AmountApplied"=>"",
        "Interest"=>"",
        // should be in months
        "RepaymentPeriod"=>"",
        "MemberId"=>"",
        "MemberIdGuarant"=>"",
        "AmountGuaranteed"=>"",
        "LoanType"=>"",
        "AmountApprove"=>"",

    );


// todo if the application fails like the databse is 
// new application
    function __registerNewLoanApplication()
    {
        $db=Database::getInstance();
        $mysqli=$db->getConnection();

       $query="INSERT INTO `LoanApplication`(
        `repayment_period`,
       `interest`,
       `member_id`,
       `amount_applied`,
       `loan_type_id`)
       VALUES(";

       $query.="'{$this->Fillables['RepaymentPeriod']}'
       , '{$this->Fillables['Interest']}'
       ,'{$this->Fillables['MemberId']}'
       ,'{$this->Fillables['AmountApplied']}',
       '{$this->Fillables['LoanType']}'";
       $query.=")";

        $result=$mysqli->query($query);
        echo $mysqli->error;
        $loan_id=$mysqli->insert_id;
        if(!$loan_id){
            $this->Error="Un error occured";
            return false;
        }
        $this->Success="loan application form added succefully,wait for a confirmation";
        return $loan_id;


    }
    // approve application

    // guaranteed
    function __registerNewGuarant()
    {
        $db=Database::getInstance();
        $mysqli=$db->getConnection();

        $query="INSERT INTO `Guatantor`(
            `loan_id`,
           `member_id`,
           `Amount_guaranteed`)
           VALUES(";
    
           $query.="'$this->LoanID'
           ,'{$this->Fillables['MemberIdGuarant']}'
           ,'{$this->Fillables['AmountGuaranteed']}'
           ";
           $query.=")";
    
            $result=$mysqli->query($query);
            echo $mysqli->error;
            $loan_id=$mysqli->insert_id;
            if(!$loan_id){
                $this->Error="Un error occured";
                return false;
            }
            $this->Success="loan application form added succefully,wait for a confirmation";
            return $loan_id;
    
    }

    function __approveLoan($Loanid){
        $db=Database::getInstance();
        $mysqli=$db->getConnection();

        $query="UPDATE  `LoanApplication`
        SET 
            approve = '1',
            amount_approved='{$this->Fillables['AmountApproved']}',
            approval_date=now()
        WHERE
            application_no = $Loanid
        ";
        $result=$mysqli->query($query);
        echo $mysqli->error;
        $loan_id=$mysqli->affected_rows;
        if(!($loan_id)){
          $this->Error="Un error occured";
          return false;
        }
        $this->Success="loan confirmed ,your will receive the money shortly";
        return true;
    }

    function returnLoanDetails(){
        $db=Database::getInstance();
        $mysqli=$db->getConnection();
        $query_chk="SELECT `amount_approved`,`interest`,`repayment_period` FROM `LoanApplication` WHERE `application_no`='$this->ApplicationNo'";
        $result_chk=$mysqli->query($query_chk);
        echo $mysqli->error;
        $num_chk=$result_chk->num_rows;
        if($num_chk>0)//check if a record exists in the recordset
        {
            $row_chk=$result_chk->fetch_assoc();
            $this->AmountApproved=$row_chk['amount_approved'];
            $this->interest=$row_chk['interest'];
            $this->months=$row_chk['repayment_period'];
            return true;
        }
    }
    function __payLoan(){

        $db=Database::getInstance();
        $mysqli=$db->getConnection();

        $repayment_amount_interest=$this->AmountApproved*($this->interest/100)*($this->months/12);
        
        $repayment_amount=$repayment_amount_interest+$this->AmountApproved;

        $query="INSERT INTO `LoanDisbursement`(
            `loan_app_id`,
           `principal_amount`,
           `repayment_amount`)
           VALUES(";
    
           $query.="'$this->ApplicationNo'
           ,'$this->AmountApproved',
           '$repayment_amount'
           ";
           $query.=")";
    
            $result=$mysqli->query($query);
            echo $mysqli->error;
            $id=$mysqli->insert_id;
            if(!$id){
                
                return false;
            }
            
            return $id;
        
            
    
    }
    function __loanpayment($id){
        $db=Database::getInstance();
        $mysqli=$db->getConnection();
        
         $currentdate=date('Y-m-d');
        $query="INSERT INTO `Loan`(
           `member_id`,
           `deadline_payment_date`,
           `amount_paid`,
           `current_balance`,
           `loan_disbursement_id`
           )
           VALUES('$this->MemberId',";
           $query.="DATE_ADD('$currentdate', INTERVAL $this->months MONTH),
           '$this->AmountApproved',
           '$this->AmountApproved',
           '$id'
           ";
           $query.=")";
    
            $result=$mysqli->query($query);
            echo $mysqli->error;
            $id=$mysqli->insert_id;
            if(!$id){
                $this->Error="Un error occured";
                return false;
            }
            $this->Success="loan succefully disbursed";
            return $id;
        
    }
     function __loanRepayment(){
        $db=Database::getInstance();
        $mysqli=$db->getConnection();
    
        $query_chk="SELECT Loan.current_balance  AS current_balance FROM `Loan` INNER JOIN LoanDisbursement ON Loan.loan_disbursement_id=LoanDisbursement.loan_id 
        WHERE LoanDisbursement.loan_app_id='$this->ApplicationNo'";
        $result_chk=$mysqli->query($query_chk);
        echo $mysqli->error;
        $num_chk=$result_chk->num_rows;
        if($num_chk>0)//check if a record exists in the recordset
        {
            $row_chk=$result_chk->fetch_assoc();
            $this->repaymentamount=$row_chk['current_balance'];
            
        }
        // if the about exceedd to be paid handle it 
        $loanbalance= $this->currentbalance - $this->amountpaid;

        $query1="UPDATE  `Loan`
        SET 
        current_balance = ' $loanbalance'
        WHERE
        loan_disbursement_id = (SELECT loan_id FROM LoanDisbursement WHERE loan_app_id=$this->ApplicationNo)
        ";
        $result=$mysqli->query($query1);
        echo $mysqli->error;

        $query="INSERT INTO `LoanRepayment`(
            `member_id`,
            `repayment_amount`,
            `balance_to_date`,
            `repayment_date`,
            `payment_type_id`,
            `loan_app_id`
            )
            VALUES('$this->MemberId',";
            $query.="$this->amountpaid,
            '$loanbalance',
            now(),
            '$this->paymentID',
            '$this->ApplicationNo'
            ";
            $query.=")";
     
             $result=$mysqli->query($query);
             echo $mysqli->error;
             $id=$mysqli->insert_id;
             if(!$id){
                 $this->Error="Un error occured";
                 return false;
             }
             $this->Success="loan repayment Successfully";
             return $id;


     }
     function __getLoandetails(){
        $db=Database::getInstance();
        $mysqli=$db->getConnection();
    
        $query="SELECT  LoanDisbursement.repayment_amount,Loan.deadline_payment_date ,LoanApplication.interest,loantype.loan_type_name,member.first_name,member.last_name,loanapplication.application_no ,guatantor.member_id FROM `LoanApplication` 
        INNER JOIN LoanDisbursement ON LoanApplication.application_no=LoanDisbursement.loan_id
        INNER JOIN loantype ON loanapplication.loan_type_id=loantype.loan_id
        INNER JOIN member ON loanapplication.member_id=member.member_id 
        INNER JOIN guatantor ON loanapplication.application_no=guatantor.loan_id
        INNER JOIN Loan ON loandisbursement.loan_app_id=loan.Loan_id";
        $results=$mysqli->query($query);
        echo $mysqli->error;

        $num_chk=array();
		while($row = $results->fetch_assoc()){
			array_push($num_chk,$row);
			
        }
        return $num_chk;
     }

     function __getLoanRequests(){
        $db=Database::getInstance();
        $mysqli=$db->getConnection();
        $query ="SELECT  LoanApplication.*,loantype.loan_type_name,member.first_name,member.last_name,guatantor.member_id FROM `LoanApplication` 
         INNER JOIN loantype ON loanapplication.loan_type_id=loantype.loan_id
        INNER JOIN member ON loanapplication.member_id=member.member_id 
        INNER JOIN guatantor ON loanapplication.application_no=guatantor.loan_id WHERE loanapplication.approve=0
        ";
         $results=$mysqli->query($query);
         echo $mysqli->error;
 
         $num_chk=array();
         while($row = $results->fetch_assoc()){
             array_push($num_chk,$row);
             
         }
         return $num_chk;
     }

}


?>