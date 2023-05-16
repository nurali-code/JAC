<?php
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

$name = $_POST['name'];
$phone = $_POST['phone_number'];

$service = $_POST['service'];
$capacity = $_POST['capacity'];
$height = $_POST['height'];
$equipment = $_POST['equipment'];
$сabin = $_POST['сabin'];
$frame = $_POST['frame'];
$offcet = $_POST['offcet'];
$type = $_POST['type'];
$hours = $_POST['hours'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->CharSet = 'utf-8';

try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'mail.hosting.reg.ru';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@jac-elektro.ru';
    $mail->Password   = 'passMail.ru';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('info@jac-elektro.ru', 'ЭЛЕКТРОМОБИЛЬНЫЙ ПОГРУЗЧИК JAC');
    $mail->addAddress('nur3.dav.97@gmail.com', 'Получатеь');
    $mail->addReplyTo('info@jac-elektro.ru', 'ЭЛЕКТРОМОБИЛЬНЫЙ ПОГРУЗЧИК JAC');

    $mail->isHTML(true);
    $mail->Subject = 'Новая заявка с сайта.';

    if (!empty($hours)) {
        $hours_row = '
        <tr>
            <td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Количество часов</td>
            <td style="border: 1px solid #bdbdbd; padding: 5px;">' . $hours . '</td>
        </tr>';
    } else {
        $hours_row = '';
    }

    $mail->Body = ' <table style="width: 100%;">
        <tr>
            <td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Услуга</td>
            <td style="border: 1px solid #bdbdbd; padding: 5px;">' . $service . '</td>
        </tr>
        <tr>
            <td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Имя</td>
            <td style="border: 1px solid #bdbdbd; padding: 5px;">' . $name . '</td>
        </tr>
        <tr>
            <td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Телефон</td>
            <td style="border: 1px solid #bdbdbd; padding: 5px;">' . $phone . '</td>
        </tr>
        ' . $hours_row . '
    </table>';


    $mail->AltBody = 'Hello World!';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
