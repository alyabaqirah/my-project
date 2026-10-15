<?php
session_start();

include "pknmdb.php";

if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
} else {
    session_regenerate_id(true);  // regenerate id
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: AdminLogin.php");
    exit();
}


//untuk security

function escape($data) {
    global $pknmgovm_klinik;
    return htmlspecialchars($pknmgovm_klinik->real_escape_string($data));
}

?>


<!DOCTYPE html>

<html lang="en">     

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> 

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>

<style>

    * {

        margin: 0;

        padding: 0;

        box-sizing: border-box;

        font-family: "Poppins", sans-serif;

        font-size: 18px;

    }



    body {

        display: flex;

        min-height: 100vh;

        background-color: #f5f5f5;

    }



    .container {

        display: flex;

        width: 100%;

    }



    .sidepanel {

        width: 250px;

        height: 100vh;

        background-color: #2c3e50;

        padding: 20px;

        color: white;

        position: fixed;

        top: 0;

        left: 0;

        z-index: 1000;

    }



    .sidepanel .logo {

        text-align: center;

        margin-bottom: 30px;

    }



    .sidepanel .logo img {

        width: 120px;

    }



    .sidepanel .sidenav ul {

        list-style: none;

        padding: 0;

    }



    .sidepanel .sidenav ul li {

        margin: 20px 0;

    }



    .sidepanel .sidenav ul li a {

        color: white;

        text-decoration: none;

        font-size: 18px;

        padding: 10px 15px;

        border-radius: 5px;

        display: block;

        transition: background-color 0.3s ease;

    }



    .sidepanel .sidenav ul li a:hover {

        background-color: #3498db;

    }



    .maincontent {

        flex-grow: 1;

        margin-left: 250px;

        padding: 20px;

    }



    .header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        background-color: #fff;

        padding: 20px;

        box-shadow: 0 4px 8px rgba(0,0,0,0.1);

    }



    .headerleft h1 {

        font-size: 24px;

        color: #333;

    }



    .headerright {

        display: flex;

        align-items: center;

        gap: 20px;

    }



    .headerright img {

        width: 40px;

        height: 40px;

        border-radius: 50%;

    }



    .headerright span {

        font-size: 16px;

        color: #333;

    }



    .logoutbtn {

        background-color: #e74c3c;

        color: white;

        padding: 10px 20px;

        border-radius: 5px;

        text-decoration: none;

        transition: background-color 0.3s ease;

    }



    .logoutbtn:hover {

        background-color: #c0392b;

    }



    .content {

        padding: 20px;

        background-color: #fff;

        margin-top: 20px;

        border-radius: 5px;

        box-shadow: 0 4px 8px rgba(0,0,0,0.1);

    }



    .content h2 {

        font-size: 22px;

        color: #333;

        margin-bottom: 20px;

    }



    .content p {

        font-size: 16px;

        color: #666;

    }


    .daftarmasuk {
        color: lightblue;
        margin-bottom :100px;
    }

    .daftar-masuk-container {
    position: fixed; 
    bottom: 20px; 
    left: 20px; 
    }

    .daftar-masuk {
        background-color: #007BFF; 
        color: white; 
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
    }

    .dropdown-content {
        display: none; 
        position: absolute;
        background-color: #f9f9f9;
        bottom:100%;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        border-radius: 5px;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1; 
    }

    .daftar-masuk-container:hover .dropdown-content {
        display: block;
    }
    @media only screen and (max-width:800px) {
        /* tablets */
        .main {
            width: 80%;
            padding: 0;
        }
        .right {
            width: 100%;
        }
        }
        @media only screen and (max-width:500px) {
        /* phone */
        .menu, .main, .right {
            width: 100%;
        }
    }
    </style>



<body>



    <div class="container">

        <aside class="sidepanel">

            <div class="logo">

                <a href="adminHomepage.php">

                <img src="mcorplogo.png" alt="Logo" class="logo">

                </a>            

            </div>

            <nav class="sidenav">

                <ul>

                    <li><a href="AdminHomepage.php">Laman Utama</a></li>

                    <li><a href="adminnewadmin.php">Admin</a></li>

                    <li><a href="Adminstaff.php">Senarai Staff</a></li>

                    <li><a href="AdminClinicList.php">Senarai Klinik</a></li>

                    <li><a href="AdminTransactionList.php">Senarai Transaksi</a></li>

                    <li><a href="AdminHomepage.php?logout=true" >Keluar</a></li>

                </ul>

            </nav>

            <div class="daftar-masuk-container">
            <div class="daftar-masuk"><i class='bx bx-log-in'></i> Daftar Masuk</div>
            <div class="dropdown-content">
                <a href="daftarmasukadmin.php">Daftar Masuk Admin</a>
                <a href="daftarmasukkerani.php">Daftar Masuk Kerani</a>
                <a href="daftarmasukclinic.php">Daftar Masuk Klinik</a>
                <a href="daftarmasukstaff.php">Daftar Masuk Staff</a>
            </div>
        </div>

        </aside>



<div class="maincontent">

<header class="header">

                <div class="headerleft">

                    <h1>Daftar Masuk Admin</h1>

                </div>

                <div class="headerright">

                    <i class='bx bx-user'></i> 

                    <span>Selamat Datang, Admin</span>

                    <a href="AdminHomepage.php?logout=true" class="logoutbtn">Keluar</a>

                </div>

            </header>



            <section class="content">
            <h2>Tambah Admin Baru</h2>
        <form id="addadminForm" method="POST" action="adminaddadmin.php">

        <label for="username">Username :</label><br>
        <input type="text" id="username" name="username" size="50" required><br><br>
        
        <label for="password">Password :</label><br>
        <input type="text" id="password" name="password" size="50" required><br><br>
         
        <input type="submit" value="Tambah">
        </form>
        </section>

</body>
</html>

