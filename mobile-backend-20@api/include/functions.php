<?php
function field_exists($tab, $field)
{
    $db=Database::getInstance();
    $mysqli=$db->getConnection();
    $query="SHOW COLUMNS FROM `$tab` LIKE '$field'";
    $result=$mysqli->query($query);
    echo $mysqli->error;
    $exists=($result->num_rows)?true:false;
    return $exists;
}

function table_exists($table)
{
	$db=Database::getInstance();
    $mysqli=$db->getConnection();
	$query_check="SELECT*FROM information_schema.tables WHERE";
	$query_check.=" table_schema = 'db_mobile_20' AND table_name = '$table'";
	$query_check.=" LIMIT 1;";
	$result_check=$mysqli->query($query_check);
	$num_check=$result_check->num_rows;
	$bol=$num_check>0 ? true : false;
	return $bol;
}


function clean($string)
{
    $db=Database::getInstance();
    $mysqli=$db->getConnection();
    $string=trim($string);
    $string=mysqli_real_escape_string($mysqli, $string);
    return $string;
}

function len($string)
{
    $string=trim($string);
    if(strlen($string)>0)
    {
     return false; 
    }
    return true;
}

function capitalize($stg){
		
	return ucwords(strtolower($stg));

}


?>