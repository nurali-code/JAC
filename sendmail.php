<?php
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



$name = $_POST['name'];
$phone = $_POST['phone_number'];
$capacity = $_POST['capacity'];
$height = $_POST['height'];
$equipment = $_POST['equipment'];
$cabin = $_POST['сabin'];
$frame = $_POST['frame'];
$offcet = $_POST['offcet'];
$hours = $_POST['hours'];

// https://api.telegram.org/bot5879635069:AAHmcN8v54NR6aXfdCaS4UM_Xzut9XJNtqc/getUpdates
$token = "5879635069:AAHmcN8v54NR6aXfdCaS4UM_Xzut9XJNtqc";
$chat_id = "-1001869762500";

$arr = array(
    'Имя: ' => $name,
    'Телефон: ' => $phone
);
if (!empty($hours)) {
    $arr['Грузоподъемность: '] = $capacity;
    $arr['Высота: '] = $height;
    $arr['Дополнительно: '] = $frame;
    $arr[''] = $equipment;
    $arr[''] = $cabin;
    $arr[''] = $offcet;
}

foreach ($arr as $key => $value) {
    $txt .= "<b>" . $key . "</b> " . $value . "%0A";
};

$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}", "r");

if ($sendToTelegram) {
    exit;
} else {
    echo 'Error';
}


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
    $mail->addAddress('nur3.dav.97@gmail.com', 'Получатель');
    // $mail->addAddress('info@jacvostok.ru', 'Получатель');
    $mail->addReplyTo('info@jac-elektro.ru', 'ЭЛЕКТРОМОБИЛЬНЫЙ ПОГРУЗЧИК JAC');

    $mail->isHTML(true);
    $mail->Subject = 'Новая заявка с сайта';

    if (!empty($hours)) {
        $hours_row = '
        <tr>
            <td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Грузоподъемность</td>
            <td style="border: 1px solid #bdbdbd; padding: 5px;">' . $capacity . '</td>
        </tr>
        <tr>
            <td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Высота</td>
            <td style="border: 1px solid #bdbdbd; padding: 5px;">' . $height . '</td>
        </tr>
        <tr>
            <td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Дополнительно</td>
            <td style="border: 1px solid #bdbdbd; padding: 5px;">' . $frame . '<br>' . $equipment .  '<br>' . $cabin . '<br>' . $offcet . '</td>
        </tr>';
    } else {
        $hours_row = '';
    }

    $mail->Body .= '
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

$queryUrl = 'https://gkbig.bitrix24.ru/rest/8/1jazdcwxku6t582n/crm.lead.add.json';

$queryData = http_build_query(array(
    "fields" => array(
        "TITLE" => 'Заявка с сайта jac-elektro.ru',
        "NAME" => $name,
        "PHONE" => array(array("VALUE" => $phone, "VALUE_TYPE" => "MOBILE")),
        "COMMENTS" => "Грузоподёмность: $capacity\nВысота: $height\nДополнительно: $equipment\n $cabin\n $frame\n $offcet",
    ),
    'params' => array("REGISTER_SONET_EVENT" => "Y")
));

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
));

$result = curl_exec($curl);
curl_close($curl);
$result = json_decode($result, true);

if (array_key_exists('error', $result)) {
    die("Ошибка при сохранении лида: " . $result['error_description']);
} else {
    echo "Лид успешно создан. ID: " . $result['result'];
}
