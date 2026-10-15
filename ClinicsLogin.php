<?php
session_start();
include "pknmdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID']) && isset($_POST['PASSWORD'])) {
    $ID = mysqli_real_escape_string($pknmgovm_klinik, trim($_POST['ID']));
    $PASSWORD = mysqli_real_escape_string($pknmgovm_klinik, trim($_POST['PASSWORD']));

    if (empty($ID)) {
        header("Location: ClinicLogin.php?error=Nama Pengguna Diperlukan");
        exit();
    } else if (empty($PASSWORD)) {
        header("Location: ClinicLogin.php?error=Kata Laluan Diperlukan");
        exit();
    }

    $sql = "SELECT * FROM clinics WHERE ID = '$ID' AND PASSWORD = '$PASSWORD'";
    $result = mysqli_query($pknmgovm_klinik, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['ID'] = $row['ID']; 
        $_SESSION['NAMA_KLINIK'] = $row['NAMA_KLINIK']; 

        // pegi klinik homepage lepas dah login 
        header("Location: ClinicHomepage.php");
        exit();
    } else {
        echo '<script>alert("Maklumat Yang Anda Masukkan Salah");</script>';
        session_destroy();
        echo '<script>window.location.assign("ClinicLogin.php");</script>';
    }
}
?>
