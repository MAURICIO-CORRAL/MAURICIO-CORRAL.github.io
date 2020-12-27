<?php
/*
Name: 			Contact Form
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version:	5.7.1
*/

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

require_once('php-mailer/PHPMailerAutoload.php');

// Step 1 - Enter your email address below.
$email = 'contacto@flptgic.org';

// If the e-mail is not working, change the debug option to 2 | $debug = 2;
$debug = 0;

$subject = "Mensaje de contacto: ".$_POST['budget'];

$fields = array(
	0 => array(
		'text' => 'Name',
		'val' => $_POST['name']
	),
	1 => array(
		'text' => 'Email',
		'val' => $_POST['email']
	),
	2 => array(
		'text' => 'Teléfono',
		'val' => $_POST['phone']
	),
	3 => array(
		'text' => 'Asunto',
		'val' => $_POST['budget']
	),
	4 => array(
		'text' => 'Mensaje',
		'val' => $_POST['comment']
	)
);

$message = '';

foreach($fields as $field) {
	$message .= $field['text'].": " . htmlspecialchars($field['val'], ENT_QUOTES) . "<br>\n";
}

$mail = new PHPMailer(true);

try {

	$mail->SMTPDebug = $debug;                                 // Debug Mode

	// Step 2 (Optional) - If you don't receive the email, try to configure the parameters below:

	//$mail->IsSMTP();                                         // Set mailer to use SMTP
	// $mail->Host = 'ssl://smtp.gmail.com:465';				       // Specify main and backup server
	// $mail->SMTPAuth = true;                                  // Enable SMTP authentication
	// $mail->Username = 'registrobrb@gmail.com';                    // SMTP username
	// $mail->Password = 'BrbMexico1;';                              // SMTP password
	// $mail->SMTPSecure = 'ssl';                               // Enable encryption, 'ssl' also accepted
	// $mail->Port = 465;   								       // TCP port to connect to



	$mail->AddAddress($email);	 						       // Add another recipient
	$mail->AddBCC('registrobrb@gmail.com');
	//$mail->AddAddress('person2@domain.com', 'Person 2');     // Add a secondary recipient
	//$mail->AddCC('person3@domain.com', 'Person 3');          // Add a "Cc" address. 
	//$mail->AddBCC('person4@domain.com', 'Person 4');         // Add a "Bcc" address. 

	$mail->SetFrom($email);
	$mail->AddReplyTo($_POST['email'], $_POST['name']);

	$mail->IsHTML(true);                                  // Set email format to HTML

	$mail->CharSet = 'UTF-8';

	$mail->Subject = $subject;
	$mail->Body    = $message;

	if ($mail->Send()) {
		$arrResult = array ('response'=>'success', 'message'=>"Su mensaje se ha enviado corretamente");
	}else{
		$arrResult = array ('response'=>'error', 'message'=>"Ocurrio un error al enviar el mensaje");
	}

} catch (phpmailerException $e) {
	$arrResult = array ('response'=>'error','message'=>"Error al enviar: ".$e->errorMessage());
} catch (Exception $e) {
	$arrResult = array ('response'=>'error','message'=>"Error al enviar: ".$e->getMessage());
}

if ($debug == 0) {
	echo json_encode($arrResult);
}
