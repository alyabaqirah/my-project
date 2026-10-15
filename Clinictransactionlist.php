        <?php
        session_start(); //session kena buat kat atas

        include "pknmdb.php";

        if (!isset($_SESSION['ID'])) {

            header("Location: ClinicLogin.php");

            exit();

        } else {

            session_regenerate_id(true);  // regenerate id

        }

        if (isset($_GET['logout'])) {

            session_unset();

            session_destroy();

            header("Location: ClinicLogin.php");

            exit();
        }

        $ID = mysqli_real_escape_string($pknmgovm_klinik, $_SESSION['ID']);
        $findsql = "SELECT * FROM `clinics` WHERE `ID` = '$ID';";
        $chksql = mysqli_query($pknmgovm_klinik, $findsql);


        $nama = "Nama tidak dijumpai";

        $stmt = $pknmgovm_klinik->prepare("SELECT NAMA_KLINIK FROM clinics WHERE ID = ?");
        $stmt->bind_param("s", $ID); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nama = $row['NAMA_KLINIK']; 
        } else {
            $nama = "Nama tidak dijumpai";
        }

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

        h1 {
            color: #333;
            margin-top: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="submit"] {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
        font-style: normal;
        font-size: 18px;
        
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-transform: uppercase;

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

        
        button[type="submit"]  {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
            margin-left:5px;
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
                <h1> Senarai Transaksi Klinik </h1>
            </div>
            <div class="headerright">
                <i class='bx bx-user'></i> 
                <span><?php echo $nama?>!</span>
                <a href="ClinicHomepage.php?logout=true" class="logoutbtn">Keluar</a>
            </div>
        </header>

        <section class="content">
                <h2>Selamat Datang Ke <?php echo $nama?></h2>
                <p>Di sini anda boleh mengurus urus niaga dan tetapan lain.</p>
            </section>
            
<section class="content">
<h1>Senarai Transaksi</h1>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Carian Nama" size="100" />
    <input type="submit" value="Cari" />
</form>

<table>
    <thead>
        <tr>
            <th>NAMA STAFF</th>
            <th>NAMA PESAKIT</th>
            <th>HUBUNGAN</th>
            <th>NAMA KLINIK</th>
            <th>JENIS RAWATAN</th>
            <th>TARIKH</th>
            <th>DIBUAT PADA</th>
            <th>JUMLAH</th>
            <th>BAKI PERUNTUKAN</th>
            <th>JUMLAH PERLU DIBAYAR SENDIRI</th>
            <th>Cetak Invois</th>
        </tr>
    </thead>
    <tbody>
    </section>

    <?php
    include "pknmdb.php";

    $ID = $_SESSION['ID'];

    $searchQuery = "";
    if (isset($_GET['search'])) {
        $search = $pknmgovm_klinik->real_escape_string($_GET['search']);
        $searchQuery = "AND transactions.nama_staff LIKE '%$search%'";
    }

    $sql = "SELECT transactions.*, staff.peruntukan, 
            (SELECT SUM(amount) FROM transactions WHERE transactions.nama_staff = staff.nama) AS total_spent,
            CASE
                WHEN transactions.relation IN ('ibu', 'bapa') AND transactions.amount > 500 THEN transactions.amount - 500
                ELSE 0
            END AS excess_amount
            FROM transactions 
            INNER JOIN staff ON transactions.nama_staff = staff.nama
            INNER JOIN clinics ON clinics.NAMA_KLINIK = transactions.clinic_name
            WHERE clinics.ID = '$ID'
            $searchQuery
            ORDER BY transactions.transaction_id ASC";


    $result = $pknmgovm_klinik->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $amount = $row['amount']; 
            $peruntukan = $row['peruntukan'];
            $totalSpent = $row['total_spent'];

            $balance = $peruntukan - $totalSpent;

            $updateBalanceSQL = "UPDATE transactions SET balance = ? WHERE transaction_id = ?";
            $stmt = $pknmgovm_klinik->prepare($updateBalanceSQL);
            
            if (!$stmt) {
                die('Prepare failed: ' . $pknmgovm_klinik->error);  
            }
            
            $balance = $peruntukan - $totalSpent;
            $transaction_id = $row['transaction_id']; 
            
            $stmt->bind_param("di", $balance, $transaction_id); // guna "di" untuk double
            if (!$stmt->execute()) {
                die('Execute failed: ' . $stmt->error);  
            }
            
            $stmt->close(); 
            
            echo "<tr>";
            echo "<td>" . $row['nama_staff'] . "</td>";
            echo "<td>" . $row['patient'] . "</td>";
            echo "<td>" . $row['relation'] . "</td>";
            echo "<td>" . $row['clinic_name'] . "</td>";
            echo "<td>" . $row['rawatan'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>RM " . number_format($amount, 2) . "</td>"; 
            echo "<td>RM " . number_format($balance, 2) . "</td>"; 
            echo "<td>RM " . number_format($row['extraAmount'], 2) . "</td>"; 
            echo "<td>
                            <form action='GenerateInvoice.php' method='post' target='_blank'>
                                <input type='hidden' name='NO_KAD_PENGENALAN' value='" . $row['NO_KAD_PENGENALAN'] . "'>
                                <input type='hidden' name='NAMA' value='" . $row['nama_staff'] . "'>
                                <input type='hidden' name='patient' value='" . $row['patient'] . "'>
                                <input type='hidden' name='relation' value='" . $row['relation'] . "'>
                                <input type='hidden' name='NAMA_KLINIK' value='" . $row['clinic_name'] . "'>
                                <input type='hidden' name='date' value='" . $row['date'] . "'>
                                <input type='hidden' name='created_at' value='" . $row['created_at'] . "'>
                                <input type='hidden' name='amount' value='" . $row['amount'] . "'>
                                <input type='hidden' name='extraAmount' value='" . $row['extraAmount'] . "'>
                                <button type='submit' class='invoice'>Invois</button>
                            </form>
                          </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>Rekod Tidak Dijumpai</td></tr>";
    }

    $pknmgovm_klinik->close();
    ?>

    </tbody>
    </table>
    </body>
    </html>
