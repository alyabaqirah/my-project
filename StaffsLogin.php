<?php
session_start();
include "pknmdb.php";

if(isset($_POST['NO_KAD_PENGENALAN']) && isset($_POST['PASSWORD'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

if(isset($_POST['NO_KAD_PENGENALAN']) && isset($_POST['PASSWORD'])){
$NO_KAD_PENGENALAN = validate($_POST['NO_KAD_PENGENALAN']);
$PASSWORD = validate($_POST['PASSWORD']);

    if(empty($NO_KAD_PENGENALAN)){
        header ("Location: StaffLogin.php?error=Nama Pengguna Diperlukan");
        exit();
    }

    else if(empty($PASSWORD)){
        header ("Location: StaffLogin.php?error=Kata Laluan Diperlukan");
        exit();
    }

    $sqlchk = "SELECT * FROM `staff` WHERE `NO_KAD_PENGENALAN`='$NO_KAD_PENGENALAN' AND `PASSWORD`='$PASSWORD'";
    $result = mysqli_query($pknmgovm_klinik, $sqlchk);

    if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);
        if($row['NO_KAD_PENGENALAN'] === $NO_KAD_PENGENALAN && $row['PASSWORD'] === $PASSWORD){
            echo "Logged In";
            
            $_SESSION['NO_KAD_PENGENALAN'] = $row['NO_KAD_PENGENALAN'];
            header("Location: StaffHomepage.php");
            exit();
        }
    }

    else{
        echo '<script>alert("Maklumat Yang Anda Masukkan Salah");</script>';
        session_destroy(); //tutup session start
        echo '<script>window.location.assign("StaffLogin.php");</script>';
    }
}

?>