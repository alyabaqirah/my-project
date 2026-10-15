<?php
$NO_KAD_PENGENALAN = $_POST['NO_KAD_PENGENALAN'];
$nama_staff = $_POST['NAMA'];
$patient = $_POST['patient'];
$relation = $_POST['relative'];
$clinic_name = $_POST['NAMA_KLINIK'];
$date = $_POST['date'];
$amount = $_POST['amount'];
$jenis = $_POST ['jenis'];
$rawatan = $_POST['rawatan'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invois Klinik</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: "Poppins", sans-serif;
            font-style: normal;
            font-size: 18px;
            margin: 0;
            padding: 20px;
        }
        .invoiscontainer {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            text-transform: uppercase;
        }
        .table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        .printbutton {
            text-align: center;
            margin-top: 20px;
        }
        .printbutton button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .backbutton {
            text-align: center;
            margin-top: 20px;
        }
        .backbutton button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

         /* hide dari website */
    .print-only {
        display: none;
    }

    /* ade masa print */
    @media print {
        .print-only {
            display: block;
            margin-top:100px;
        }
        .backbutton, .printbutton {
            display: none;
        }
        .maincontent {
            margin-left: 0;
        }
    }

    .logo {
        width: 100px;
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

<div class="invoiscontainer">
        <div class="header">
        <div class="logo">
        <img src="mcorplogo.png" alt="Logo" class="logo">          
        </div>
        <h1>Invois</h1>
        <p><?php echo $clinic_name; ?>
        
    </div>

    <table class="table">
    <tr>
            <th>Nombor Kad Pengenalan</th>
            <td><?php echo $NO_KAD_PENGENALAN; ?></td>
        </tr>
        <tr>
            <th>Nama Staff</th>
            <td><?php echo $nama_staff; ?></td>
        </tr>
        <tr>
            <th>Nama Pesakit</th>
            <td><?php echo $patient; ?></td>
        </tr>
        <tr>
            <th>Hubungan</th>
            <td><?php echo $relation; ?></td>
        </tr>
        <tr>
            <th>Klinik</th>
            <td><?php echo $clinic_name; ?></td>
        </tr>
        <tr>
            <th>Jenis</th>
            <td><?php echo $jenis; ?></td>
        </tr>
        <tr>
            <th>Rawatan</th>
            <td><?php echo $rawatan; ?></td>
        </tr>
        <tr>
            <th>Tarikh</th>
            <td><?php echo $date; ?></td>
        </tr>
        <tr>
            <th>Jumlah Bayaran (RM)</th>
            <td><?php echo number_format($amount, 2); ?></td>
        </tr>
    </table>

    <div class="total">Total: RM<?php echo number_format($amount, 2); ?></div>

    <div class="printbutton">
        <button onclick="window.print()">Cetak Invois</button>
    </div>

<form name= back class= backbutton action="keranitransaksilist.php">
    <div class="backbutton">
        <button onclick >Kembali</button>
    </div>
</div>

</body>
</html>
