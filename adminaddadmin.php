<?php
include "pknmdb.php";

$newadmin = null; //declareeee
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $result = $pknmgovm_klinik->query("SELECT MAX(admin_id) AS max_admin FROM admins");
    $row = $result->fetch_assoc();
    $next_admin = $row['max_admin'] + 1;

    $insertsql = "INSERT INTO admins (admin_id, username, password) 
                  VALUES ('$next_admin', '$username', '$hashed_password')";

    if ($pknmgovm_klinik->query($insertsql) === TRUE) {
        echo "<script>alert('Rekod Telah Diisi');</script>";
        // Redirect to "Senarai Admin" page
        echo "<script>window.location.href = 'adminnewadmin.php';</script>";
    } else {
        echo "Rekod Tidak Diisi: " . $pknmgovm_klinik->error;
    }
    }
    ?>