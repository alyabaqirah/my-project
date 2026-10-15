<?php

$hostname="localhost";

$username="root";

$password="";

$dbname="pknmgovm_klinik";

			

$pknmgovm_klinik=new mysqli($hostname,$username,$password,$dbname);

if ($pknmgovm_klinik->connect_error) {

    die("Database Connection Failed: " . $pknmgovm_klinik->connect_error);

}

?>

