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
    $mail->addAddress('info@jacvostok.ru', 'Получатель');
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

// формируем URL, на который будем отправлять запрос в Битрикс24
$queryUrl = 'https://gkbig.bitrix24.ru/rest/244/ll5k3yxlfm2tf0jv/crm.lead.add.json';

// Формируем параметры для создания лида
$queryData = http_build_query(array(
    "fields" => array(
        "TITLE" => 'Заявка с сайта jac-elektro.ru',      // Имя лида
        "NAME" => $name,                 // Имя
        "PHONE" => array(array("VALUE" => $phone, "VALUE_TYPE" => "MOBILE")),   // Телефон
        "COMMENTS" => "Грузоподёмность: $capacity\nВысота: $height\nДополнительно: $equipment\n $cabin\n $frame\n $offcet",  // Текстовое поле с описанием
    ),
    'params' => array("REGISTER_SONET_EVENT" => "Y")    // Произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.
));

// отправляем запрос в Битрикс24 и обрабатываем ответ
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

// если произошла какая-то ошибка - выведем её
if (array_key_exists('error', $result)) {
    die("Ошибка при сохранении лида: " . $result['error_description']);
} else {
    echo "Лид успешно создан. ID: " . $result['result'];
}
