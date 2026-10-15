<?php
include "pknmdb.php";
$newclinics = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NAMA_KLINIK = $_POST['NAMA_KLINIK'];
    $ALAMAT = $_POST['ALAMAT'];
    $NO_TELEFON = $_POST['NO_TELEFON'];

    $result = $pknmgovm_klinik->query("SELECT MAX(BIL) AS max_bil FROM clinics");
    $row = $result->fetch_assoc();
    $next_bil = ($row['max_bil'] !== null) ? $row['max_bil'] + 1 : 1; 

    $insertsql = "INSERT INTO clinics (BIL, NAMA_KLINIK, ALAMAT, NO_TELEFON) 
                  VALUES ('$next_bil', '$NAMA_KLINIK', '$ALAMAT', '$NO_TELEFON')";

    if ($pknmgovm_klinik->query($insertsql) === TRUE) {
        echo "<script>
                alert('Rekod Telah Diisi');
                window.location.href = 'AdminClinicList.php';
            </script>";

        $newclinics = [
            'BIL' => $next_bil,
            'NAMA_KLINIK' => $NAMA_KLINIK,
            'ALAMAT' => $ALAMAT,
            'NO_TELEFON' => $NO_TELEFON
        ];
    } else {
        echo "Error inserting record: " . $pknmgovm_klinik->error;
    }
}
?>
