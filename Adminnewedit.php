<?php
include "pknmdb.php";

if (isset($_GET['admin_id'])) {
    $admin_id = $_GET['admin_id']; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $updateSql = "UPDATE admins SET username=?, password=? WHERE admin_id=?";
        $stmt = $pknmgovm_klinik->prepare($updateSql);
        $stmt->bind_param("ssi", $username, $password, $admin_id);

        if ($stmt->execute()) {
            echo "Rekod Dikemaskini";
            header("Location: Adminnewadmin.php");
            exit; 
        } else {
            echo "Ralat Mengemaskini Rekod " . $pknmgovm_klinik->error;
        }
    }

    $sql = "SELECT * FROM admins WHERE admin_id = ?";
    $stmt = $pknmgovm_klinik->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 
    } else {
        echo "Rekod Tidak Dijumpai"; 
    }
} else {
    echo "Invalid request. No admin id provided.";
    exit;
}

$pknmgovm_klinik->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Admin</title>
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
        input[type="text"] {
            border: none;
            background-color: transparent;
            width: 100%;
            font-size: 18px;
        }
        input[type="text"]:focus {
            outline: none;
            border-bottom: 1px solid #007bff;
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

<form method="post" action="">
    <table align="center" border="1">
        <tr>
            <td align="center" colspan="2"><h2>Kemaskini Admin</h2></td>
        </tr>
        <tr>
            <td>Admin ID :</td>
            <td><?php echo $row['admin_id']; ?></td>
        </tr>
        <tr>
            <td>Username :</td>
            <td><input type="text" name="username" value="<?php echo $row['username']; ?>" required></td>
        </tr>
        <tr>
            <td>Password :</td>
            <td><input type="text" name="password" value="<?php echo $row['password']; ?>" required></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kemaskini"></td>
        </tr>
    </table>
</form>

<form method="post" action="Adminnewadmin.php">
    <table align="center">
        <tr>
            <td colspan="2"><input type="submit" class="submit" value="Kembali"></td>
        </tr>
    </table>
</form>

</body>
</html>
