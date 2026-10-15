<?php
include "pknmdb.php";
$transaction_id = $_GET['transaction_id'];

if (isset($_GET['transaction_id']) && isset($_GET['reason'])) {
    $transaction_id = $_GET['transaction_id'];
    $reason = $_GET['reason'];

    echo "Transaction ID: " . htmlspecialchars($transaction_id) . "<br>";
    echo "Reason: " . htmlspecialchars($reason) . "<br>";

    $logQuery = "INSERT INTO deletion_log (transaction_id, reason) VALUES (?, ?)";
    $stmt = $pknmgovm_klinik->prepare($logQuery);
    $stmt->bind_param("ss", $transaction_id, $reason);

    if (!$stmt->execute()) {
        echo "<script>alert('Ralat');</script>". $stmt->error;
    } else {
        echo "<script>alert('Rekod Staff Berjaya Dihapus');</script>";
    }
    $stmt->close();

    $deleteQuery = "DELETE FROM transactions WHERE transaction_id = ?";
    $stmt = $pknmgovm_klinik->prepare($deleteQuery);
    $stmt->bind_param("s", $transaction_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Rekod Staff Berjaya Dihapus');</script>";
        header("Location: AdminTransactionList.php");
        exit();
    } else {
        echo "Ralat: " . $stmt->error;
    }
    $stmt->close();
}

