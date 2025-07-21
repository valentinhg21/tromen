<?php
/*
 * Template Name: Test PHPMailer
 */
?>

<?php
global $mail; // Define the global variable

if (!class_exists('PHPMailer')) {
    require_once ABSPATH . WPINC . '/class-phpmailer.php';
    require_once ABSPATH . WPINC . '/class-smtp.php';
}

$messageHTML = "TEST";
$subject = "Consulta de Nombre y Apellido recibida desde tromen.com";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 4;
$mail->SetFrom('no-reply@tromen.com', 'Tromen'); // Remitente, cambiar según sea necesario
$mail->AddAddress("federico@zetenta.com");
$mail->addBCC("mdager@tromen.com");
$mail->isHTML(true);
$mail->CharSet = "UTF-8";
$mail->Subject = $subject;
$mail->Body = $messageHTML;
// $mail->Debugoutput = 'error_log';

if (!$mail->Send()) {
    error_log("Mailer Error: " . $mail->ErrorInfo); // Registrar el error en el log
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo 'ok';
}

?>
