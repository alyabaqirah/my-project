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

$ID = mysqli_real_escape_string($pknmgovm_klinik, $_SESSION['username']);
$findsql = "SELECT * FROM `kerani` WHERE `username` = '$username';";
$chksql = mysqli_query($pknmgovm_klinik, $findsql);

$newTransaction = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $NO_KAD_PENGENALAN = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NO_KAD_PENGENALAN']);
    $nama_staff = mysqli_real_escape_string($pknmgovm_klinik, $_POST['NAMA']);
    $jenis = strtolower(trim(mysqli_real_escape_string($pknmgovm_klinik, $_POST['jenis'])));
    $date = mysqli_real_escape_string($pknmgovm_klinik, $_POST['date']);
    $amount = (float)mysqli_real_escape_string($pknmgovm_klinik, $_POST['amount']);

    $extraAmount = 0; 
    $availableAmount = 0;

    $insertsql = "INSERT INTO transactions (NO_KAD_PENGENALAN, nama_staff, jenis, date, amount, extraAmount, created_at) 
                  VALUES ('$NO_KAD_PENGENALAN', '$nama_staff', '$jenis', '$date', '$amount', '$extraAmount', CURRENT_TIMESTAMP)";

    if ($pknmgovm_klinik->query($insertsql) === TRUE) {
        $newTransaction = [
            'NO_KAD_PENGENALAN' => $NO_KAD_PENGENALAN,
            'nama_staff' => $nama_staff,
            'jenis' => $jenis,
            'date' => $date,
            'amount' => $amount,
            'extraAmount' => $extraAmount,
            'created_at' => date('Y-m-d H:i:s')
        ];
    } else {
        echo "Error: " . $pknmgovm_klinik->error;
    }
}

$staffnameQuery = "SELECT NAMA FROM staff";
$staffnameResult = mysqli_query($pknmgovm_klinik, $staffnameQuery);

$pendahuluanQuery = "SELECT jenis FROM pendahuluan";
$pendahuluanResult = mysqli_query($pknmgovm_klinik, $pendahuluanQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Baru</title>
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

        table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%; 
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            border: none;
            background-color: transparent;
            width: 100%;
            font-size: 18px;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            text-transform: uppercase;

        }
        input[type="text"]:focus {
            outline: none;
            border-bottom: 1px solid #007bff;
            text-transform: uppercase;

        }
        .submit {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .invoice {
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
<body>

<div class="container">

<aside class="sidepanel">

    <div class="logo">

        <a href="keranihomepage.php">

        <img src="mcorplogo.png" alt="Logo" class="logo">

        </a>            

    </div>

    <!-- sidepanel untuk nav bar -->

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

                    <h1> Dashboard Kerani</h1>

                </div>

                <div class="headerright">

                    <i class='bx bx-user'></i> 

                    <span>Selamat Datang, Kerani</span>

                    <a href="keranihomepage.php?logout=true" class="logoutbtn">Keluar</a>

                </div>

            </header>

    <!-- ni untuk tukar header -->

                <section class="content">

                <h2>Selamat Datang Ke Transaksi Baru Kerani</h2>

                <p>Di sini anda boleh menambah urus niaga staff seperti bayaran balik ubat mahupun bayaran balik cermin mata</p> 

            </section>

<section class="content">

<form method="post" action="" enctype="multipart/form-data">
    <table align="center" border="1">
        <tr>
            <td align="center" colspan="2"><h2>Transaksi Baru</h2></td>
        </tr>
        <tr>
            <td>Nombor Kad Pengenalan:</td>
            <td><input type="text" name="NO_KAD_PENGENALAN" id="ic_input" onblur="fetchName()" required></td>
        </tr>
        <tr>
             <td>Nama:</td>
             <td><input type="text" name="NAMA" id="name_input" readonly></td>
        </tr>
        <tr>
            <td>Jenis Pendahuluan:</td>
            <td>
                <input list="jenis" name="jenis" required>
                <datalist id="jenis">
                    <?php while ($row = mysqli_fetch_assoc($pendahuluanResult)) { ?>
                        <option value="<?php echo $row['jenis']; ?>"></option>
                    <?php } ?>
                </datalist>
            </td>
        </tr>
        <tr>
            <td>Jumlah:</td>
            <td><input type="text" name="amount" required></td>
        </tr>
        <tr>
            <td>Tarikh:</td>
            <td><input type="date" name="date" required></td>
        </tr>
        <tr>
            <td colspan="2"><input class="submit" type="submit" value="Tambah"></td>
        </tr>
    </table>
</form>
</section>


<script>
// panggil nama based on ic
function fetchName() {
    var NO_KAD_PENGENALAN = document.getElementById('ic_input').value;

    // untuk confirm kan yang ic tak kosong
    if (NO_KAD_PENGENALAN.trim() === '') {
        alert("Sila Isikan Nombor Kad Pengenalan.");
        return;
    }

    // guna AJAX untuk panggil nama
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_name.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('name_input').value = xhr.responseText;
        }
    };
    xhr.send('NO_KAD_PENGENALAN=' + NO_KAD_PENGENALAN);
}

</script>

    <!-- untuk display lepas submit--> 
    <?php if ($newTransaction): ?>
        <h2>Transaksi Baru:</h2>
        <table border="1">
            <tr>
                <th>Nombor Kad Pengenalan</th>
                <th>Nama Staff</th>
                <th>Jenis Pendahuluan</th>
                <th>Tarikh</th>
                <th>Jumlah</th>
                <th>Dibuat Pada</th>
                <th>Jumlah Perlu Dibayar (Jika Berlebihan)</th>
            </tr>
            <tr>
                <td><?php echo $newTransaction['NO_KAD_PENGENALAN']; ?></td>
                <td><?php echo $newTransaction['nama_staff']; ?></td>
                <td><?php echo $newTransaction['jenis']; ?></td>
                <td><?php echo $newTransaction['date']; ?></td>
                <td><?php echo number_format($newTransaction['amount'], 2); ?></td>
                <td><?php echo $newTransaction['created_at']; ?></td>
                <td>
                    <?php if ($newTransaction['extraAmount'] > 0): ?>
                        RM <?php echo number_format($newTransaction['extraAmount'], 2); ?>
                    <?php else: ?>
                        Tiada Lebihan
                    <?php endif; ?>
                </td>
            </tr>
        </table>
          <!-- ni untuk generate invoice -->
          <form action="GenerateInvoiceKerani.php" method="post">
          <input type="hidden" name="NO_KAD_PENGENALAN" value="<?php echo $newTransaction['NO_KAD_PENGENALAN']; ?>">
            <input type="hidden" name="NAMA" value="<?php echo $newTransaction['nama_staff']; ?>">
            <input type="hidden" name="patient" value="<?php echo $newTransaction['patient']; ?>">
            <input type="hidden" name="relative" value="<?php echo $newTransaction['relation']; ?>">
            <input type="hidden" name="NAMA_KLINIK" value="<?php echo $newTransaction['clinic_name']; ?>">
            <input type="hidden" name="jenis" value="<?php echo $newTransaction['jenis']; ?>">
            <input type="hidden" name="rawatan" value="<?php echo $newTransaction['rawatan']; ?>">
            <input type="hidden" name="date" value="<?php echo $newTransaction['date']; ?>">
            <input type="hidden" name="amount" value="<?php echo $newTransaction['amount']; ?>">
            <input type="hidden" name="extraAmount" value="<?php echo $newTransaction['extraAmount']; ?>">
            <input type="submit" class="invoice" value="Invois">
        </form>
    <?php endif; ?>

</body>
</html>

<?php
mysqli_close($pknmgovm_klinik);
?>
