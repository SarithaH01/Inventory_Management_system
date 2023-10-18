<?php
// email_functions.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/Exception.php';
require 'vendor/PHPMailer/PHPMailer.php';
require'vendor/PHPMailer/SMTP.php';

send_low_stock_email($product_name) {
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration for Gmail
        $mail->isSMTP();
        $mail->Host = 'http://php.net/smtp-port';
        $mail->SMTPAuth = true;
        $mail->Username = 'www.sarithahari124@gmail.com'; // Replace with your Gmail email address
        $mail->Password = '15801580'; // Replace with your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $to = '22mp1971@rit.ac.in'; // Replace with the admin's email address
        $subject = 'Low Stock Alert';
        $message = 'Product "' . $product_name . '" is low on stock.';
        $mail->setFrom('www.sarithahari124@gmail.com', 'Saritha'); // Sender's Gmail email address and name
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }

?>