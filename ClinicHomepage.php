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
if (!isset($_SESSION['ID'])) {
    header("Location: ClinicLogin.php");
    exit();
} 

session_regenerate_id(true);

// panggil user id dari session
$ID = $_SESSION['ID'];

// panggil dari database
$nama = "Nama tidak dijumpai";
$stmt = $pknmgovm_klinik->prepare("SELECT NAMA_KLINIK FROM clinics WHERE ID = ?");
$stmt->bind_param("s", $ID); 
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama = $row['NAMA_KLINIK']; 
} else {
    $nama = "Nama tidak dijumpai";
}

if (!isset($_SESSION['log_logged'])) {
    logActivity($ID, 'Log Masuk', "$nama Log Masuk Ke Dalam Sistem");
    $_SESSION['log_logged'] = true; // untuk prevent duplicate masa login
}

$log_sql = "SELECT * FROM log_aktiviti ORDER BY timestamp DESC";
$log_result = mysqli_query($pknmgovm_klinik, $log_sql);

if (isset($_GET['logout'])) {
    logActivity($ID, 'Log Keluar', "$nama Log Keluar Dari Sistem"); // log keluar

    session_unset();
    session_destroy();
    header("Location: ClinicLogin.php");
    exit();
}

// panggil count transaksi
$stmt = $pknmgovm_klinik->prepare("SELECT COUNT(transaction_id) AS total_transactions FROM transactions WHERE clinic_name = ?");
$stmt->bind_param("s", $nama); 
$stmt->execute();

$result = $stmt->get_result();
$total_transactions = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_transactions = $row['total_transactions'];
}

//panggil claim
$stmt = $pknmgovm_klinik->prepare("SELECT SUM(amount) AS total_claims FROM transactions WHERE clinic_name = ?");
$stmt->bind_param("s", $nama); 
$stmt->execute();
$result = $stmt->get_result();
$total_claims = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_claims = $row['total_claims'];
}

// panggil data transaksi (total amount clinic)
$stmt = $pknmgovm_klinik->prepare("SELECT SUM(amount) AS total_amount FROM transactions WHERE clinic_name = ? GROUP BY clinic_name");
$stmt->bind_param("s", $nama); 
$stmt->execute();
$result = $stmt->get_result();
$total_amount = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_amount = $row['total_amount'];
}

//panggil freq orang datang
$stmt = $pknmgovm_klinik->prepare("SELECT COUNT(DISTINCT nama_staff) AS total_visitors FROM transactions WHERE clinic_name = ?");
$stmt->bind_param("s", $nama);
$stmt->execute();
$result = $stmt->get_result();
$total_visitors = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_visitors = $row['total_visitors'];
}

$pknmgovm_klinik->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Homepage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <a href="ClinicHomepage.php">
        <img src="mcorplogo.png" alt="Logo" class="logo">
            </a>            
        </div>
        <nav class="sidenav">
            <ul>
                <li><a href="ClinicHomepage.php">Laman Utama</a></li>
                <li><a href="Clinictransactionlist.php">Senarai Transaksi</a></li>
                <li><a href="Clinictransaction.php">Transaksi Baru</a></li>
                <li><a href="ClinicHomepage.php?logout=true" >Keluar</a></li>
                </ul>
        </nav>
    </aside>

    <div class="maincontent">
        <header class="header">
            <div class="headerleft">
                <h1>Dashboard Klinik</h1>
            </div>
            <div class="headerright">
                <i class='bx bx-user'></i> 
                <span><?php echo $nama; ?>!</span>
                <a href="ClinicHomepage.php?logout=true" class="logoutbtn">Keluar</a>
                </div>
        </header>

        <section class="content">
                <h2>Selamat Datang Ke <?php echo $nama; ?></h2>
                <p>Di sini anda boleh mengurus urus niaga dan tetapan lain.</p>
            </section>
            
        <div class="cards">
            <div class="card">
                <h3>Jumlah Transaksi </h3>
                <?php echo $total_transactions; ?>  
            </div>
            <div class="card">
                <h3>Jumlah Penggunaan</h3>
                <p>RM<?php echo number_format($total_claims, 2); ?></p>
                </div>
        </div>
        <div class = "content">
        <h2>Statistik <?php echo $nama; ?></h2>

        <div class="chart-wrapper">
          <div class="chart-container">
              <canvas id="totalTransactionChart"></canvas>
          </div>

          <div class="chart-container">
              <canvas id="frequencyVisitsChart"></canvas>
          </div>
          </div>

 <script>
    var totalTransactionCtx = document.getElementById('totalTransactionChart').getContext('2d');
    var totalTransactionChart = new Chart(totalTransactionCtx, {
        type: 'bar',
        data: {
            labels: ['Jumlah Transaksi Klinik'],
            datasets: [{
                label: 'Jumlah Transaksi (RM)',
                data: [<?php echo $total_amount; ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    var frequencyVisitsCtx = document.getElementById('frequencyVisitsChart').getContext('2d');
    var frequencyVisitsChart = new Chart(frequencyVisitsCtx, {
        type: 'bar',
        data: {
            labels: ['Jumlah Pengunjung Klinik'],
            datasets: [{
                label: 'Jumlah Pengunjung Klinik',
                data: [<?php echo $total_visitors; ?>],
                backgroundColor: ['rgba(75, 192, 192, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });

    </script>

</body>
</html>
