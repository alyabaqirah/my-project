<?php

session_start();

include "pknmdb.php";


if(isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data){

        $data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return $data;

    }

}



if(isset($_POST['username']) && isset($_POST['password'])){

$user = validate($_POST['username']);

$pass = validate($_POST['password']);



    if(empty($user)){

        header ("Location: AdminLogin.php?error=Nama Pengguna Diperlukan");

        exit();

    }



    else if(empty($pass)){

        header ("Location: AdminLogin.php?error=Kata Laluan Diperlukan");

        exit();

    }



    $sqlchk = "SELECT * FROM `admins` WHERE `username`='$user' AND `password`='$pass'";

    $result = mysqli_query($pknmgovm_klinik, $sqlchk);



    if (mysqli_num_rows($result) === 1) {

        $row = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $row['username']; 


        // pegi admin homepage lepas dah login 

        header("Location: AdminHomepage.php");

        exit();

    } else {

        echo '<script>alert("Maklumat Yang Anda Masukkan Salah");</script>';

        session_destroy();

        echo '<script>window.location.assign("AdminLogin.php");</script>';

    }

}



?>

