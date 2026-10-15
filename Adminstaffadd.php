<?php
include "pknmdb.php";

$newstaff = null; //declareeee
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NAMA = $_POST['NAMA'];
    $NO_KAD_PENGENALAN = $_POST['NO_KAD_PENGENALAN'];
    $PERUNTUKAN = $_POST['PERUNTUKAN'];
    $PASSWORD = $_POST['PASSWORD'];

    if ($PERUNTUKAN < 2000 || $PERUNTUKAN > 2500) {
        echo "<script>alert('Peruntukan harus antara 2000 dan 2500.');</script>";
        exit;
    }

    $hashed_password = password_hash($PASSWORD, PASSWORD_DEFAULT);

    $result = $pknmgovm_klinik->query("SELECT MAX(BIL) AS max_bil FROM staff");
    $row = $result->fetch_assoc();
    $next_bil = $row['max_bil'] + 1;

    $insertsql = "INSERT INTO staff (BIL, NAMA, NO_KAD_PENGENALAN, PERUNTUKAN, PASSWORD) 
                  VALUES ('$next_bil', '$NAMA', '$NO_KAD_PENGENALAN', '$PERUNTUKAN', '$hashed_password')";

    if ($pknmgovm_klinik->query($insertsql) === TRUE) {
        echo "<script>alert('Rekod Telah Diisi');</script>";
        echo "<script>window.location.href = 'adminstaff.php';</script>";
    } else {
        echo "Rekod Tidak Diisi: " . $pknmgovm_klinik->error;
    }
    }
    ?>