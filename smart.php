<?php
$name = $_POST['name'];
$phone = $_POST['phone_number'];
$type = $_POST['type'];
$hours = $_POST['hours'];
$service = $_POST['service'];

require_once('phpmailer/PHPMailerAutoload.php');
$mail = new PHPMailer;
$mail->CharSet = 'utf-8';

// $mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; 
$mail->SMTPAuth = true;
$mail->Username = 'alinovasamira8@gmail.com'; //реальная gmail почта
$mail->Password = 'Uim$nt98CTYe!RB'; //реальный пароль от этой почты
$mail->SMTPSecure = 'ssl';
$mail->Port = 587;

$mail->setFrom('alinovasamira8@gmail.com'); //повторяем ту же gmail почту 
$mail->addAddress('nur3.dav.97@gmail.com'); // Add a recipient
// $mail->addAddress('eastashev@yandex.ru');  // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');
// $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
// $mail->addAttachment($_FILES['u-photo']['tmp_name'], $_FILES['u-photo']['name']); // Optional name
$mail->isHTML(true); // Set email format to HTML

$mail->Subject = 'Новая заявка с сайта.';
$mail->Body = '
<table style="width: 100%;">
<tr>
	<td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Имя</td>
	<td style="border: 1px solid #bdbdbd; padding: 5px;">' . $name . '</td>
</tr>
<tr>
	<td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Телефон</td>
	<td style="border: 1px solid #bdbdbd; padding: 5px;">' . $phone . '</td>
</tr>
<tr>
<td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Услуга</td>
<td style="border: 1px solid #bdbdbd; padding: 5px;">' . $service . '</td>
</tr>
<tr>
	<td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Обращение от клиента</td>
	<td style="border: 1px solid #bdbdbd; padding: 5px;">' . $type . '</td>
</tr>
<tr>
	<td style="border: 1px solid #bdbdbd; padding: 5px; width: 180px">Обращение от клиента</td>
	<td style="border: 1px solid #bdbdbd; padding: 5px;">' . $hours . '</td>
</tr>
</table>';

$mail->AltBody = 'Это альтернативный текст';

if (!$mail->send()) {
	echo 'Error';
	echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
	echo 'Good!';
}
