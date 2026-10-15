<?php

$EMAIL = $_POST["EMAIL"];

$token = bin2hex(random_bytes(16));

$token_hash = hash("sha256", $token);

$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = require __DIR__ . "/pknmdb.php";

$sql = "UPDATE staff
        SET reset_token= ?,
            token_expiry = ?
        WHERE EMAIL = ?";

$stmt = $pknmgovm_klinik->prepare($sql);

$stmt->bind_param("sss", $token_hash, $expiry, $EMAIL);

$stmt->execute();

if ($pknmgovm_klinik->affected_rows) {

    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($EMAIL);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="http://example.com/reset-password.php?token=$token">here</a> 
    to reset your password.

    END;

    try {

        $mail->send();

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";

    }

}

echo "Message sent, please check your inbox.";