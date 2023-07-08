<?php

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
require_once('/home/creditoparaviaje/certiviaje.com/wp-content/plugins/license-manager-for-woocommerce/email/PHPMailer/src/Exception.php');
require_once('/home/creditoparaviaje/certiviaje.com/wp-content/plugins/license-manager-for-woocommerce/email/PHPMailer/src/PHPMailer.php');

require_once('/home/creditoparaviaje/certiviaje.com/wp-content/plugins/license-manager-for-woocommerce/email/PHPMailer/src/SMTP.php');
$data = json_decode(file_get_contents('php://input'), true);

$destinatario = $data['destinatario'];
$from = $data['from'];
$to = $data['to'];
$message = $data['message'];
$url = $data['url'];
$licencia = $data['licencia'];
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = 'mail.certiviaje.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'notificaciones@certiviaje.com';
  $mail->Password = 'D-i?hD;lY0}F';
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;
  $mail->setFrom('notificaciones@certiviaje.com');
  $mail->addAddress($destinatario);
  $mail->isHTML(true);
  $mail->Subject = 'Certiviaje GiftCard!';
  $mail->Body = '
    <html>
    <head>
      <style>
        .gift-card {
          background-color: #f9f9f9;
          border: 1px solid #ddd;
          border-radius: 4px;
          padding: 20px;
          text-align: center;
          font-family: Arial, sans-serif;
          font-size: 16px;
        }
        
        .gift-card-header {
          margin-bottom: 20px;
        }
        
        .gift-card-content {
          margin-bottom: 20px;
        }
        
        .gift-card-button {
          background-color: #FE4F08;
          border: none;
          color: #FFFFFF;
          padding: 10px 20px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
          border-radius: 4px;
        }
        .gift-card-button.custom-color {
          color: #FFFFFF; /* Cambiar el color del texto */
        }
        .gift-card ol {
          text-align: center;
          padding-left: 0;
          list-style-position: inside;
        }
        
       .gift-card li {
        text-align: center;
        margin-left: 2px;
       }
      </style>
    </head>
    <body>
      <div class="gift-card" style="text-align:center; margin :auto;"> 
        <img style="width: 100%; max-width: 400px;" src="'.$url.'" alt="Gift Card"/>
        <h2 class="gift-card-header">¡Felicidades!</h2>
        <p class="gift-card-content">Has recibido una tarjeta de regalo.</p>
        <p class="gift-card-content">De:'. $from.'</p>
        <p class="gift-card-content">Para:'.$to.'</p>
        <p class="gift-card-content">'.$message.'</p>
        <p class="gift-card-content">Para redimir la tarjeta de regalo, sigue las siguientes instrucciones:</p>
        <ol>
          <li>Inicia sesión en nuestro sitio web.</li>
          <li>Dirígete a la sección de "Tarjetas de regalo".</li>
          <li>Ingresa el código de la tarjeta de regalo: '.$licencia.'.</li>
          <li>Haz clic en el botón "Canjear".</li>
        </ol>
        <p class="gift-card-content">¡Disfruta de tu regalo!</p>
        <a href="https://certiviaje.com" style="color:#FFFFFF"; class="gift-card-button">Canjear</a>
      </div>
    </body>
    </html>';
    
  $mail->send();
?>
