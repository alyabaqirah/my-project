<?php
include "pknmdb.php";

if (isset($_GET['BIL'])) {
    $bil = $_GET['BIL'];

    $sql = "SELECT * FROM familystaff WHERE BIL=?";
    $stmt = $pknmgovm_klinik->prepare($sql);
    $stmt->bind_param("i", $bil);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Rekod Tidak Dijumpai";
    }
} else {
    echo "Invalid request";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient = $_POST['patient'];
    $icfamily = $_POST['icfamily'];
    $relation = $_POST['relation']; 

    $sql = "UPDATE familystaff SET patient=?, icfamily=?, relation=? WHERE BIL=?";
    echo "Rekod Dikemaskini";
    header("Location: StaffProfile.php");
    $stmt = $pknmgovm_klinik->prepare($sql);
    $stmt->bind_param("sssi", $patient, $icfamily, $relation, $bil);
    $stmt->execute();

    $stmt->close();

}
$pknmgovm_klinik->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Staff</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: "Poppins", sans-serif;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        input[type="text"], input[type="file"] {
            border: none;
            background-color: transparent;
            width: 100%;
            font-family: "Poppins", sans-serif;
            font-size: 18px;
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
<body>

<form method="post" action="" enctype="multipart/form-data">
        <table align="center" border="1">
            <tr>
                <td align="center" colspan="2"><h2> Staff Profil</h2></td>
            </tr>
            <tr>
                <td>BIL :</td>
                <td><?php echo $row['BIL']; ?></td>
            </tr>
            <tr>
                <td>NAMA :</td>
                <td>
                    <input type="text" name="patient" value="<?php echo $row['patient']; ?>" />
                </td>           
             </tr>
            <tr>
                <td>NO KAD PENGENALAN :</td>
                <td>
                    <input type="text" name="icfamily" value="<?php echo $row['icfamily']; ?>"  />
                </td>
            </tr>
            <tr>
                <td>HUBUNGAN  :</td>
                <td>
                    <input type="text" name="relation" value="<?php echo $row['relation']; ?>" disabled />
                </td>
            </tr>
            <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kemaskini Tanggungan"></td>
        </tr>
        </table>
        </form>

        <form method="post" action="StaffProfile.php">
        <table align="center">
        <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kembali"></td>
        </tr>
    </table>
</form>
</body>
</html>
