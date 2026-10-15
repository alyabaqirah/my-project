<?php
include "pknmdb.php";

$newstaff = null; //declareeee

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $BIL = $_POST['BIL'];
    $NAMA = $_POST['NAMA'];
    $NO_KAD_PENGENALAN = $_POST['NO_KAD_PENGENALAN'];
    $PERUNTUKAN = $_POST['PERUNTUKAN'];
    $PASSWORD = $_POST['PASSWORD'];

    $hashed_password = password_hash($PASSWORD, PASSWORD_DEFAULT);

    $insertsql = "INSERT INTO staff (BIL, NAMA, NO_KAD_PENGENALAN, PERUNTUKAN, PASSWORD) VALUES ('$BIL', '$NAMA', '$NO_KAD_PENGENALAN', '$PERUNTUKAN', '$hashed_password')";

    if ($pknmgovm_klinik ->query($insertsql) === TRUE) {
        echo "<script>alert('Rekod Telah Diisi');</script>";

        $newstaff = [
            'BIL' => $BIL,
            'NAMA' => $NAMA,
            'NO_KAD_PENGENALAN' => $NO_KAD_PENGENALAN,
            'PERUNTUKAN' => $PERUNTUKAN,
            'PASSWORD' => $PASSWORD
        ];
    } else {
        echo "Rekod Tidak Diisi" . $pknmgovm_klinik->error;
    }
}
?>