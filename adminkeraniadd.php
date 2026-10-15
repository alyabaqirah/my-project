<?php
include "pknmdb.php";

$newkerani = null; //declareeee
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $result = $pknmgovm_klinik->query("SELECT MAX(kerani_id) AS max_kerani FROM kerani");
    $row = $result->fetch_assoc();
    $next_kerani = $row['max_kerani'] + 1;

    $insertsql = "INSERT INTO kerani (kerani_id, username, password) 
                  VALUES ('$next_kerani', '$username', '$hashed_password')";

    if ($pknmgovm_klinik->query($insertsql) === TRUE) {
        echo "<script>alert('Rekod Telah Diisi');</script>";
        echo "<script>window.location.href = 'daftarmasukkerani.php';</script>";
    } else {
        echo "Rekod Tidak Diisi: " . $pknmgovm_klinik->error;
    }
    }
    ?>