<?php
session_start();
include "pknmdb.php";

if (!isset($_SESSION['username'])) {
    header("Location: keranilogin.php");
    exit();
}

if (isset($_GET['logout'])) {

    session_unset();

    session_destroy();

    header("Location: keranilogin.php");

    exit();
}

if (isset($_GET['BIL'])) {
    $bil = $_GET['BIL'];

    $sql = "SELECT * FROM staff WHERE BIL=?";
    $stmt = $pknmgovm_klinik->prepare($sql);
    $stmt->bind_param("i", $bil);
    $stmt->execute();
    $staffResult = $stmt->get_result();

    if ($staffResult->num_rows > 0) {
        $staffRow = $staffResult->fetch_assoc();
    } else {
        echo "Record not found";
        exit;
    }

    $relationQuery = "SELECT jenis FROM relative";
    $relationResult = mysqli_query($pknmgovm_klinik, $relationQuery);

    if (!$relationResult) {
        die("Error retrieving relations: " . mysqli_error($pknmgovm_klinik));
    }

    $NO_KAD_PENGENALAN = $staffRow['NO_KAD_PENGENALAN'] ?? null;

    if (!$NO_KAD_PENGENALAN) {
        die("NO_KAD_PENGENALAN is missing.");
    }

    $stmt = $pknmgovm_klinik->prepare("
        SELECT 
            NAMA, 
            peruntukan, 
            (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE transactions.nama_staff = staff.nama) AS total_spent 
        FROM staff 
        WHERE NO_KAD_PENGENALAN = ?
    ");
    $stmt->bind_param("s", $NO_KAD_PENGENALAN);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama = $row['NAMA'];
        $peruntukan = $row['peruntukan'];
        $totalSpent = $row['total_spent'];
        $balance = $peruntukan - $totalSpent;
    } else {
        $nama = "Nama tidak dijumpai";
    }

    // Output results
    echo "Nama: $nama<br>";
    echo "Peruntukan: $peruntukan<br>";
    echo "Total Spent: $totalSpent<br>";
    echo "Balance: $balance<br>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tanggungan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
         * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
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

    .content h2 {
        font-size: 30px;
    }


    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 40px;
    }

    .card {
        background-color: #fff;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .charts h2 {
        font-size: 30px;
        margin-top: 40px;
    }

    .chart-container {
        margin-top: 40px;
        background-color: #fff;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        width: 45%;
    }

    .chart-wrapper {
        display: flex;
        justify-content: space-between;
    }

    .data-table h2 {
        font-size: 30px;
        margin-top: 40px;
    }

    .data-table table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .data-table th, .data-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .widget-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 40px;
    }

    .widget {
        background-color: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .widget h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .widget p {
        font-size: 1.5rem;
        color: #111;
    }

        .button-container {
            display: flex;
            justify-content: center;
        }

        .button:hover {
            font-weight: bold;
        }

        .button {
            background-color: #F7EFEF;
            color: black;
            margin-left: 550px;
            border-radius: 50px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 18px;
        }

        h1 {
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-transform:uppercase;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        .submit {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
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
</head>

<div class="container">

<aside class="sidepanel">

    <div class="logo">

        <a href="keranihomepage.php">

        <img src="mcorplogo.png" alt="Logo" class="logo">

        </a>            

    </div>

        <nav class="sidenav">

<ul>

    <li><a href="keranihomepage.php">Laman Utama</a></li>

    <li><a href="keraninewadmin.php">Admin</a></li>

    <li><a href="keranistaff.php">Senarai Staff</a></li>

    <li><a href="keranicliniclist.php">Senarai Klinik</a></li>
   
    <li><a href="keranitransaksi.php"> Transaksi Baru</a></li>

    <li><a href="keranitransaksilist.php">Senarai Transaksi</a></li>

    <li><a href="keranilaporank.php">Laporan Klinik</a></li>

    <li><a href="keranihomepage.php?logout=true" >Keluar</a></li>

</ul>

</nav>
    </aside>

    <div class="maincontent">
        <header class="header">
            <div class="headerleft">
                <h1>Tambah Tanggungan</h1>
            </div>
            <div class="headerright">
                <i class='bx bx-user'></i> 
                <span>Selamat Datang, Kerani!</span>
                <a href="keranihomepage.php?logout=true" class="logoutbtn">Keluar</a>
            </div>
        </header>

        <section class="content">
                <h2>Selamat Datang ke Tambah Tanggungan</h2>
                <p>Di sini anda boleh menambah tanggungan anda.</p>
            </section>

<body>
<section class="content">
<form id="addtanggunganform" method="POST" action="keranitanggunganadd.php">
        <table>
            <tr>
                <td>No Kad Pengenalan Staff:</td>
                <td><input type="text" id="NO_KAD_PENGENALAN" name="NO_KAD_PENGENALAN" value="<?php echo htmlspecialchars($NO_KAD_PENGENALAN, ENT_QUOTES, 'UTF-8'); ?>" readonly></td>
            </tr>
            <tr>
                <td>Nama:</td>
                <td><input type="text" id="patient" name="patient" size="50" required></td>
            </tr>
            <tr>
                <td>No Kad Pengenalan:</td>
                <td><input type="text" id="icfamily" name="icfamily" size="50" required></td>
            </tr>
            <tr>
                <td>Hubungan:</td>
                <td>
                    <input list="relative" name="relative" required>
                    <datalist id="relative">
                        <?php 
                        if (mysqli_num_rows($relationResult) > 0) {
                            while ($row = mysqli_fetch_assoc($relationResult)) {
                                echo "<option value='" . htmlspecialchars($row['jenis'], ENT_QUOTES, 'UTF-8') . "'></option>";
                            }
                        } else {
                            echo "<option value='No data available'></option>";
                        }
                        ?>
                    </datalist>
                </td>
                <tr>
                    <td>Dokumen Sokongan:</td>
                    <td><input type="file" id="supporting_doc" name="supporting_doc" size="50"></td>
                </tr>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" class="submit" value="Tambah"></td>
            </tr>
        </table>
    </form>
    <form method="post" action="keranistaff.php">
    <table align="center">
        <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kembali"></td>
        </tr>
    </table>
</form>
</body>
</html>
