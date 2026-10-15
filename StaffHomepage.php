<?php
session_start(); 
include "pknmdb.php";

function logActivity($userId, $activityType, $details) {
    global $pknmgovm_klinik;
    $userId = htmlspecialchars($userId, ENT_QUOTES, 'UTF-8');
    $activityType = htmlspecialchars($activityType, ENT_QUOTES, 'UTF-8');
    $details = htmlspecialchars($details, ENT_QUOTES, 'UTF-8');

    $sql = "INSERT INTO log_aktiviti (user_id, activity_type, details) VALUES ('$userId', '$activityType', '$details')";
    mysqli_query($pknmgovm_klinik, $sql);
}

// check user login ke tak
if (!isset($_SESSION['NO_KAD_PENGENALAN'])) {
    header("Location: StaffLogin.php");
    exit();
}

session_regenerate_id(true);

// panggil user id dari session
$no_kad_pengenalan = $_SESSION['NO_KAD_PENGENALAN'];

// panggil dari database
$nama = "Nama tidak dijumpai";
$stmt = $pknmgovm_klinik->prepare("SELECT NAMA, peruntukan, 
    (SELECT SUM(amount) FROM transactions WHERE transactions.nama_staff = staff.nama) AS total_spent 
    FROM staff 
    WHERE NO_KAD_PENGENALAN = ?");
$stmt->bind_param("s", $no_kad_pengenalan); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama = $row['NAMA']; 
    $peruntukan = $row['peruntukan'];
    $totalSpent = $row['total_spent'];
    $balance = $peruntukan - $totalSpent;
} 


if (!isset($_SESSION['log_logged'])) {
    logActivity($no_kad_pengenalan, 'Log Masuk', "$nama Log Masuk Ke Dalam Sistem");
    $_SESSION['log_logged'] = true; // untuk prevent duplicate masa login
}

// retrieve log aktiviti
$log_sql = "SELECT * FROM log_aktiviti ORDER BY timestamp DESC";
$log_result = mysqli_query($pknmgovm_klinik, $log_sql);

if (isset($_GET['logout'])) {
    logActivity($no_kad_pengenalan, 'Log Keluar', "$nama Log Keluar Dari Sistem");
 //unset
    session_unset();
    session_destroy();
    header("Location: StaffLogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Homepage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
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
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
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

<body>
<div class="container">
    <aside class="sidepanel">
        <div class="logo">
        <a href="StaffHomepage.php">
        <img src="mcorplogo.png" alt="Logo" class="logo">
            </a>            
        </div>
        <nav class="sidenav">
            <ul>
                <li><a href="StaffHomepage.php">Laman Utama</a></li>
                <li><a href="StaffProfile.php"> Staff Profil</a></li>
                <li><a href="staffclinic.php"> Senarai Klinik Panel</a></li>
                <li><a href="StaffTransaction.php">Transaksi Staff</a></li>
                <li><a href="StaffHomepage.php?logout=true" >Keluar</a></li>
            </ul>
        </nav>
    </aside>

    <div class="maincontent">
        <header class="header">
            <div class="headerleft">
                <h1>Dashboard Staff</h1>
            </div>
            <div class="headerright">
                <i class='bx bx-user'></i> 
                <span><?php echo $nama; ?>!</span>
                <a href="StaffHomepage.php?logout=true" class="logoutbtn">Keluar</a>

            </div>
        </header>

        <section class="content">
                <h2>Selamat Datang <?php echo $nama; ?></h2>
                <p>Di sini anda boleh melihat baki peruntukan dan transaksi terkini.</p>
            </section>

<section class="content">

<table>
    <thead>
        <tr>
        <section class="data-table">
        <h2>Baki Peruntukan</h2>
        <p>Anda boleh melihat baki peruntukan dan status.</p>
            <th>BAKI PERUNTUKAN (RM)</th>
            <th>STATUS</th>
    </tr>
            <td>RM <?php echo number_format($balance, 2); ?></td>
            <?php
                $status = '';
                if ($balance >= 500) {
                    $status = "<button style='background-color: green; color: white; border: none; border-radius: 15px;'>Baki Lebih RM 500</button>";
                } elseif ($balance < 500 && $balance >= 200) {
                    $status = "<button style='background-color: yellow; color: black; border: none; border-radius: 15px;'>Baki Kurang RM 500</button>";
                } elseif ($balance < 200) {
                    $status = "<button style='background-color: red; color: white; border: none; border-radius: 15px;'>Baki Kurang RM 200</button>";
                }
            ?>
            <td><?php echo $status; ?></td>
    </tbody>
</table>
</section>

  <!-- transaksi table -->
  <section class="content">
  <section class="data-table">
            <h2>Transaksi Terkini</h2>
            <p>Hanya transaksi terbaru akan dipaparkan disini.</p>
            <table>
                <thead>
                    <tr>
                        <th>Tarikh</th>
                        <th>Nama</th>
                        <th>Nama Pesakit</th>
                        <th>Hubungan</th>
                        <th>JENIS PENDAHULUAN</th>
                        <th>Jumlah</th>
                        <th>Baki Peruntukan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($pknmgovm_klinik, "SELECT * FROM transactions WHERE nama_staff = '$nama'  ORDER BY date DESC LIMIT 5");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "
                        <tr>
                            <td>{$row['date']}</td>
                            <td>{$row['nama_staff']}</td>     
                            <td>{$row['patient']}</td>
                            <td>{$row['relation']}</td>
                            <td>{$row['jenis']}</td>
                            <td>RM {$row['amount']}</td>
                            <td>RM " . number_format($balance, 2) . "</td> <!-- untuk display baki -->

                        </tr>";
                    }
                    $pknmgovm_klinik->close();
                    ?>
                </tbody>
            </table>
        </section>
    </div>

</div>
</section>

</body>
</html>


