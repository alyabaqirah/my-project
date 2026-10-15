<?php
include "pknmdb.php";
$newclinics = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $BIL = $_POST['BIL'];
    $NAMA_KLINIK = $_POST['NAMA_KLINIK'];
    $ALAMAT = $_POST['ALAMAT'];
    $NO_TELEFON = $_POST['NO_TELEFON'];

    $insertsql = "INSERT INTO clinics (BIL, NAMA_KLINIK, ALAMAT, NO_TELEFON) 
                  VALUES ('$BIL', '$NAMA_KLINIK', '$ALAMAT', '$NO_TELEFON')";

    if ($pknmgovm_klinik->query($insertsql) === TRUE) {
        echo "<script>alert('Rekod Telah Diisi');</script>";

        $newclinics = [
            'BIL' => $BIL,
            'NAMA_KLINIK' => $NAMA_KLINIK,
            'ALAMAT' => $ALAMAT,
            'NO_TELEFON' => $NO_TELEFON
        ];
    } else {
        echo "Error inserting record: " . $pknmgovm_klinik->error;
    }
}
?>