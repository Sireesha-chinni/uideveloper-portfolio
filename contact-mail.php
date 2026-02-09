<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

/* ----------------------------------------
   GET POST DATA
---------------------------------------- */
$name       = $_POST['cname'] ?? '';
$email      = $_POST['cemail'] ?? '';
$message    = $_POST['cmeesgae'] ?? ''; 

/* ----------------------------------------
   VALIDATION
---------------------------------------- */
if (empty($name) || empty($email)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields'
    ]);
    exit;
}
/* ----------------------------------------
   MAILER CONFIG FUNCTION
---------------------------------------- */
function configureMailer(): PHPMailer {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'saisireesha.chinni@gmail.com';
    $mail->Password   = 'dotzmwmfxmzoovsj'; // ✅ NO SPACES
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->isHTML(true);
    $mail->CharSet    = 'UTF-8';
    return $mail;
}

/* ----------------------------------------
   USER EMAIL BODY
---------------------------------------- */
$userBody = "
<html>
<body>
<p>Dear {$name},</p>

<p>Thank you for contacting .</p>

<p>I have received your enquiry and i will contact you shortly.</p>

<p>
Regards,<br>
<strong>Sai Sireesha Chinni</strong>
</p>
</body>
</html>
";

/* ----------------------------------------
   ADMIN EMAIL BODY
---------------------------------------- */
$adminBody = "
<html>
<body>
<p><strong>New Enquiry from Website for Protfolio</strong></p>

<p>
<strong>Name:</strong> {$name}<br>
<strong>Email:</strong> {$email}<br>
<strong>Message:</strong> {$message}<br>

<p>Regards,<br>Sireesha</p>
</body>
</html>
";

/* ----------------------------------------
   SEND USER EMAIL
---------------------------------------- */
try {
    $mailUser = configureMailer();
    $mailUser->setFrom('saisireesha.chinni@gmail.com', 'Sai Sireesha');
    $mailUser->addAddress($email);
    $mailUser->addReplyTo('saisireesha.chinni@gmail.com');
    $mailUser->Subject = "Thank you for contacting Sireesha";
    $mailUser->Body    = $userBody;
    $mailUser->send();
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User mail failed',
        'error' => $mailUser->ErrorInfo
    ]);
    exit;
}

/* ----------------------------------------
   SEND ADMIN EMAIL
---------------------------------------- */
try {
    $mailAdmin = configureMailer();
    $mailAdmin->setFrom('saisireesha.chinni@gmail.com', 'Sai Sireesha');
    $mailAdmin->addReplyTo($email, $name);
    $mailAdmin->Subject = "New Enquiry from Portfolio";
    $mailAdmin->Body    = $adminBody;

    $adminEmails = "saisireesha.chinni@gmail.com";
    foreach (explode(",", $adminEmails) as $adminEmail) {
        $mailAdmin->addAddress(trim($adminEmail));
    }

    $mailAdmin->send();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Admin mail failed',
        'error' => $mailAdmin->ErrorInfo
    ]);
    exit;
}

/* ----------------------------------------
   FINAL RESPONSE
---------------------------------------- */
echo json_encode([
    'status'  => 'success',
    'message' => 'Contact request submitted successfully'
]);
exit;
