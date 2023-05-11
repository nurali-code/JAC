<?php
// Получение данных из POST-запроса
$name = $_POST['name'];
$phone = $_POST['phone_number'];
$service = $_POST['service'];
$type = $_POST['type'];
$hours = $_POST['hours'];

// Определение адреса получателя
if ($type == 1) {
    $to = 'log_davlatov97@mail.ru';
} elseif ($type == 2) {
    $to = 'nur3.dav.97@gmail.com';
} else {
    // Если значение $type не равно 1 или 2, используем адрес по умолчанию
    $to = 'nur3.dav.97@gmail.com';
}

// Формирование сообщения
$message = "Услуга: $service\n";
$message .= "Имя: $name\n";
$message .= "Телефон: $phone\n";
if (!empty($hours)) {
    $message .= "Часы: $hours\n";
}


// Отправка сообщения на электронную почту
$subject = 'Новое сообщение с сайта';
$headers = 'From: aerobarnaul@example.com' . "\r\n" .
    'Reply-To: aerobarnaul@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

$mail_sent = mail($to, $subject, $message, $headers);
if (!$mail_sent) {
    echo "Email not sent";
} else {
    echo "Email sent";
}
