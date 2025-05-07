<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

//Server settings
function sendMail($to, $subject, $content)
{
    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        // Redirect to index.html if not
        header("Location: index.html");
        exit();
    }

    // Check for empty fields or potential script tags in POST data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $firm = filter_input(INPUT_POST, 'firm', FILTER_SANITIZE_STRING);
    $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Handle invalid email
        echo "Invalid email address";
        exit();
    }

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "ssl";
        $mail->Port       = 465;
        $mail->CharSet    = "UTF-8";
        $mail->Username   = '';
        $mail->Password   = '';

        // Recipients
        $mail->setFrom('test@gmail.com');
        $mail->addAddress($to);

        // Content
        $mail->Subject = $subject;
        $mail->Body = '
            <html>
            <head>
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        padding: 8px;
                        text-align: right;
                        border-bottom: 1px solid #ddd;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                </style>
            </head>
            <body dir="rtl"> 
                <table>
                    <tr>
                        <th>العنوان</th> 
                        <th>الرسالة</th>
                    </tr>
                    <tr>
                        <td>الاسم والكنية: </td>
                        <td>' . $name . '</td>
                    </tr>
                    <tr>
                        <td>البريد الإلكتروني: </td>
                        <td>' . $email . '</td>
                    </tr>
                    <tr>
                        <td>اسم الشركة: </td>
                        <td>' . $firm . '</td>
                    </tr>
                    <tr>
                        <td>رقم الهاتف: </td>
                        <td>' . $mobile . '</td>
                    </tr>
                    <tr>
                        <td>الرسالة: </td>
                        <td>' . nl2br($message) . '</td>
                    </tr>
                </table>
            </body>
            </html>
        ';

        // Send email
        $mail->send();

        // Redirect after successful send
        header("Location: index.html");
        exit();
    } catch (Exception $e) {
        // Redirect to index.html on error
        header("Location: index.html");
        exit();
    }
}

$to = 'isa430758@gmail.com';
$subject = 'رسالة جديدة من موقعك';
$content = '';

sendMail($to, $subject, $content);

?>
