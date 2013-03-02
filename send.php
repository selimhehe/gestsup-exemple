<?php
require("components/PHPMailer_v5.1/class.phpmailer.php");
        $mail = new PHPmailer();
        $mail->CharSet = 'UTF-8'; //UTF-8 possible if characters problems
        $mail->IsSMTP();
        $mail->Host = "owa.bersay-associes.com";
        $mail->SMTPAuth = true;
        // $mail->SMTPSecure = 'ssl';
       // $mail->Port = 465;
        $mail->Username = "voeux2013";
        $mail->Password = "s9ejejatrufR";


        $mail->IsHTML(true); // Envoi en html

        $mail->From = "voeux2013@bersay-associes.com";
        $mail->FromName = utf8_decode("Bersay AssociÃ©s");

        $mail->AddAddress("mahmoud.nb@gmail.com");
	    //$mail->AddReplyTo("$rparameters[mail_from]");
        $mail->Subject = "Nouvelle entrée dans le système.";
        $bodyMSG = "Bonjour , <br /><br />
         Nous vous remercions pour votre nouvelle demande dans le système.<br />
         Celle-ci sera prise en compte dans les prochaines heures.<br /><br />
         Voila votre nom d'utilisateur et mot de passe pour accéder à la plateforme <br />";
        $mail->Body = "$bodyMSG";
        if (!$mail->Send()){
          $msg = '<div id="erreur"><img src="./images/access.png" alt="erreur" style="border-style: none" alt="img" />';
          $msg = $mail->ErrorInfo;
          $msg = '</div>';
        }else{
			echo ' c bon';
		}