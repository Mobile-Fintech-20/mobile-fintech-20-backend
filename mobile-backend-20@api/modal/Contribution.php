<?php
include_once( '../include/class.database.php' );
include_once( '../include/functions.php' );
include_once( 'app.php' );

class Contribution extends App
 {

    public $ApplicationNo;
    public $ApprovalDate;

    public $MemberId;
    public $amountApproved;
    public $LoanID;

    public $Fillables = array(
        'AmountContributed'=>'',
        'MemberId'=>'',
        'PaymentType'=>'',
    );

    // todo if the application fails like the databse is
    // new application

    function __registerContribution()
 {

        $db = Database::getInstance();
        $mysqli = $db->getConnection();

        $amount = $this->Fillables['AmountContributed'];
        //The JSON data.

        $url = 'http://achors.hipipo.mojaloop-hackathon.io:4101/transfers';

        //Initiate cURL.
        $ch = curl_init( $url );

        //The JSON data.
        $jsonData = array(
            'from' =>array(
                'displayName'=> 'Lwanga',
                'idType'=> 'MSISDN',
                'idValue'=> '260222222222'
            ), 'to'=>array(
                'idType'=> 'MSISDN',
                'idValue'=> '610298765432'
            ),
            'amountType'=> 'SEND',
            'currency'=>'ZMW',
            'amount'=> $amount,
            'transactionType'=> 'TRANSFER',
            'initiatorType'=> 'CONSUMER',
            'note'=> 'test payment',
            'homeTransactionId'=> '{{}}' );

            //Encode the array into JSON.
            $jsonDataEncoded = json_encode( $jsonData );

            //Tell cURL that we want to send a POST request.
            curl_setopt( $ch, CURLOPT_POST, 1 );

            //Attach our encoded JSON string to the POST fields.
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $jsonDataEncoded );

            //Set the content type to application/json
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );

            // Return response instead of outputting
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            //Execute the request
            $result = curl_exec( $ch );

            // json_decode( $result );
            $data = json_decode( $result );
            
            if ( is_null($data) ) {
                $this->Error = 'Un error occured';
                return  null;
            }

            $query = "INSERT INTO `Contribution`(
        `amount_contributed`,
       `contribution_date`,
       `member_id`,
       `payment_type_id`)
       VALUES(";

            $query .= " '{$this->Fillables['AmountContributed']}'
       ,now()
       ,'{$this->Fillables['MemberId']}',
       '{$this->Fillables['PaymentType']}'";
            $query .= ')';

            $result = $mysqli->query( $query );
            echo $mysqli->error;
            $loan_id = $mysqli->insert_id;
            if ( !$loan_id ) {
                $this->Error = 'Un error occured while adding a new record';
            } else {
                $this->Success = 'Thanks for you contribution';
                return $data;
            }

        }

        function getContributionData() {

            $db = Database::getInstance();
            $mysqli = $db->getConnection();

            $query = "  SELECT  contribution.amount_contributed,contribution.contribution_date,member.first_name,member.last_name,paymenttypes.name FROM `contribution` 
         INNER JOIN PaymentTypes ON contribution.payment_type_id=paymenttypes.payment_id
        INNER JOIN member ON contribution.member_id=member.member_id ORDER BY contribution.contribution_date DESC";
            $results = $mysqli->query( $query );
            echo $mysqli->error;

            $num_chk = array();
            while( $row = $results->fetch_assoc() ) {
                array_push( $num_chk, $row );

            }
            return $num_chk;
        }

    }

    ?>