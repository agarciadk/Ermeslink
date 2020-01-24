<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER[DOCUMENT_ROOT].'/PHPMailer/src/PHPMailer.php';
require $_SERVER[DOCUMENT_ROOT].'/PHPMailer/src/Exception.php';


// Variables iniciadas para el test
$host = "http://ermeslink.it";
if(!empty($_POST['name']) && !empty($_POST['email']) && isset($_POST['society']) && isset($_POST['address']) && isset($_POST['city']) && !empty($_POST['telno'])
  && !empty($_POST['message']) && $_POST['privacy'] && !empty($_POST['g-recaptcha-response'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$society = $_POST['society'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$telno = $_POST['telno'];
	$msg = $_POST['message'];
	$captcha= $_POST['g-recaptcha-response'];
} else {
	$jsonObject = array('code' => 500, 'msg' => 'Empty field.');
	header('Content-type: application/json');
	echo json_encode($jsonObject);
	http_response_code(500);
	exit;
}
$secretKey = "6Le8UT8UAAAAAIFMPRCh_PNDaY-SFLcLH_IwFmQU";
$ip = $_SERVER['REMOTE_ADDR'];
$response=file_get_contents(
	"https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
$responseKeys = json_decode($response, true);
if(intval($responseKeys["success"]) !== 1) {
	$jsonObject = array('code' => 500, 'msg' => 'You are spammer! Get the @$%K out');
	header('Content-type: application/json');
	echo json_encode($jsonObject);
	http_response_code(500);
	exit;
}else {
	$mail = new PHPMailer(true);
	try {
		// Recipients
		$mail->setFrom($email, $name);
		$mail->addAddress('info@ermeslink.it', 'Ermeslink');
		$mail->addReplyTo($email, $name);

		// Content
		$mail->isHTML(true);
		$mail->CharSet = 'UTF-8';
		$mail->Subject = 'ErmeslinkPrueba: Nuova richiesta ('.$email.')';
		$mail->Body = '<html lang="it">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="Ermeslink" content="true"
		<title>Ermeslink: Contatti</title>
	</head>	
	<body>
		<table height="405" style="border-spacing:0;">
		  <tr>
			<td width="155"></td>
			<td width="531" height="30">
				<img src="'.$host.'/public/img/logo_2.png" alt="logo">
			</td>
		  </tr>
		  <tr>
			<td style="vertical-align: text-top; text-align: center; background-color:aliceblue;border:5px dashed #42AADC; border-bottom: none;">
				<h1 style="color:#3489E3">Contatti</h1>
				<p><strong>Nome e Cognome</strong>: <br><small>'.$name.'</small></p>
				<p><strong>Società</strong><br><small>'.$society.'</small></p>
				<p><strong>Indirizzo</strong><br><small>'.$address.'</small></p>
				<p><strong>Città</strong><br><small>'.$city.'</small></p>
				<p><strong>E-mail</strong>: <br><small><a href="mailto:'.$email.'">'.$email.'</a></small></p>
				<p><strong>Telefono</strong>: <br><small>'.$telno.'</small></p>
			</td>
			<td style="border-bottom: 5px dashed #42AADC; padding: 0px 5px;">
				<p style="font-size:1.3em;"><strong>Richiesta</strong>: <br>'.$msg.'</p>

				<hr style="margin-top:100px;">
				<p style="font-size: 0.7em;">Questo messaggio contiene informazioni riservate e privilegiate. È indirizzato al
							destinatari solo ed esclusivamente. Se non sei il destinatario della stessa non leggi,
							copiare, o distribuire le informazioni in esso contenute o agire su di esso. Se tu
							Hai ricevuto questo messaggio per errore, distruggilo e avvisa il mittente o
							e-mail:
							<a href="mailto:info@ermeslink.it">"info@ermeslink.it"</a></p>
				<p style="font-size: 0.7em;">This message contains privileged and confidential information solely for the exclusive use 
							of the intended recipients. Copying, distribution, disclosure or any use of the information 
							contained in this document is NOT permitted without the consent of the original sender. If you 
							have received this message by error, please destroy it and notify sender or the following e-mail:
							<a href="mailto:info@ermeslink.it">"info@ermeslink.it"</a></p>
			</td>
		  </tr>
		  <tr>
			<td height="53" colspan="2" style="border-right: 5px dashed #42AADC;border-left: 5px dashed #42AADC; border-bottom: 5px solid #42AADC;background-color: aliceblue;" cellpadding="0">
				<p style="font-size:0.9em;text-align: center; padding-top:0px;padding-left:10px;">
					ERMESLINK Copyright&copy; 2010-2018. sede legale: P.zza L.A Muratori 4 50134 Firenze (FI)<br>
	Codice Fiscale e Partita IVA 06040360486 - Numero iscrizione C.C.I.A.A. FI 595286<br>
	<a href="www.ermeslink.it">www.ermeslink.it</a> - mail to: <a href="mailto:info@ermeslink.it">info@ermeslink.it</a> - Tel. <a href="tel:392-0288001">392-0288001</a>.
				</p>
			</td>
		  </tr>
		</table>
	</body>
	</html>
	';
		$mail->AltBody = 'Nome e Cognome: '.$name.'
		Societá: '.$society.'
		Indirizzo: '.$address.'
		Cittá: '.$city.'
		E-mail: '.$email.'
		Telefono: '.$telno.'
		Richiesta: '.$msg.'

		Questo messaggio contiene informazioni riservate e privilegiate. È indirizzato al
		destinatari solo ed esclusivamente. Se non sei il destinatario della stessa non leggi,
		copiare, o distribuire le informazioni in esso contenute o agire su di esso. Se tu
		Hai ricevuto questo messaggio per errore, distruggilo e avvisa il mittente o
		e-mail: info@ermeslink.it

		This message contains privileged and confidential information solely for the exclusive use 
		of the intended recipients. Copying, distribution, disclosure or any use of the information 
		contained in this document is NOT permitted without the consent of the original sender. If you 
		have received this message by error, please destroy it and notify sender or the following e-mail:
		info@ermeslink.it';

		$mail->send();

	$jsonObject = array('code' => 200, 'msg' => 'Message has been sent');
	header('Content-type: application/json');
	echo json_encode($jsonObject);
	http_response_code(200);

	} 
	catch (Exception $e) {
		$jsonObject = array('code' => 500, 'msg' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
		header('Content-type: application/json');
		echo json_encode($jsonObject);
		http_response_code(500);
	}
}
?>