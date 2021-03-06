 <?php

	include 'class.phpmailer.php';
	include 'class.smtp.php';
	include 'keys.php';
	
	require_once 'Utils.php';
	//require '../vendor/phpmailer/PHPMailerAutoLoad.php';
	date_default_timezone_set('Etc/UTC');

	class CorreoUser {
		private $host;
		private $port;
		private $usernameFrom;
		private $pass;

		public function __construct(){
			$this->host='smtp.gmail.com';
			$this->port=587;
			$this->usernameFrom=USUARIO;
			$this->pass=PASS;
		}

		/*public function enviarCorreoRegistro($nom_empresa,$nom,$app,$cont,$email,$key){
			$retVal=true;
			Utils::escribeLog("Inicio PHPMailer","debug");
			$URL=$this->getURL($email,$key,true);
			try{
				$mail = new PHPMailer();
				$mail->isSMTP();
				
				$mail->SMTPSecure = 'tls';
				$mail->Host = $this->host;
				$mail->Port = $this->port;

				$mail->SMTPAuth = true;
				$mail->Username = $this->usernameFrom;
				$mail->Password = $this->pass;

				$mail->SMTPDebug=0;
				//$mail->Debugoutput = 'html';

				$mail->addAddress($email,$nom_empresa);			
				
				$mail->From=$this->usernameFrom;
				$mail->FromName='Administrador E-ONTZIA';
				$mail->addReplyTo($this->usernameFrom,'Administrador de E-ONTZIA');
				$mail->Subject = "Bienvenido a E-ONTZIA";
				$mail->AltBody = "Mensaje de registro";
				$mail->WordWrap= 50;

				//$urlValidar=getURLValidar($correo,$key);			

				$mensaje="<h1>Bienvenido/a ".$nom." ".$ape;
				if($ape2!="")
				{
					$mensaje.=" ".$ape2;
				}
				$mensaje.=' a trackingApp</h1><br/><br/><p>Gracias por incribirse en la app <b>E-ONTZIA</b></p><br/>
					<p>Su nombre de usuario: '.$nom.'</p>Su correo: '.$email.'
					<p>Ha sido inscrito correctamente, para poder acceder a la aplicación debe validar su usuario. Para validar, pulse en el siguiente enlace para validar:</p> 
					<p><a href="'.$URL.'">'.$URL.'</a></p>';
				$mail->msgHTML($mensaje);
				
				$mail->isHTML(true);

				$mail->send();
			}catch(phpmailerException $me){
				Utils::escribeLog("Error: ".$me->getMessage()." | Fichero: ".$me->getFile()." | Línea: ".$me->getLine()." [Error al enviar correo]","debug");
				return false;
			}catch(Exception $e){
				Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al enviar correo]","debug");
			}
			
			return $retVal;
		}*/
		public static function email_confirm($nombre,$key,$correo){
        //$link='"localhost/Aitor/TRACKING%20APP/trackingapp/confirmar.php?email="'.$correo."&key="."$key"';
        	$retVal=true;
        	 $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = "ssl";
            //para debufgear SMTP
            //$mail->SMTPDebug = 1;
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            //paso los datos desde keys.php
            $mail->Username = USUARIO;
			$mail->Password = PASS;
            $mail->From = "residuoszubiri@gmail.com";
            $mail->FromName = "Tracking App";
            $mail->Subject = "Bienvenido a E-ONTZIA";
            $mail->AltBody = "Mensaje de prueba";
            $mail->msgHTML("<h1>Mensaje de Bienvenida</h1><br/><br/><p>
                Hola <b>".$nombre."</b><br>Gracias por incribirse en la <b>E-ONTZIA</b></p><br/>
                <p>Debes activar tu cuenta pulsando este enlace 
                :<a href='http://localhost/Aitor/TRACKING%20APP/trackingapp/confirmar.php?email=".$correo."&key=".$key."'>pulsa aqui para activar la cuenta</a></p>");
                         //<a href="http://www.w3schools.com">Visit W3Schools</a>
                        // echo '<td>'."<a href=map.php?action=mapamostrar&datos=".$obj."><input type='button' value=' MAP' ></a>".'</td>';
                    //echo "<a href='".$link_address."'>Link</a>";
            $mail->addAddress($correo,$nombre);
            $mail->isHTML(true);
            if (!$mail->send()) {
                echo "Error: ".$mail->ErrorInfo;
            }
                   return $retVal; }    

		public function enviarConfirmValidacion($Nombre,$ape1,$ape2="",$correo){
			$retVal=true;
			Utils::escribeLog("Inicio PHPMailer confirmValidar","debug");
			$URL=$this->getURL();			
			try{
				$mail = new PHPMailer();
				$mail->isSMTP();
				
				$mail->SMTPSecure = 'tls';
				$mail->Host = $this->host;
				$mail->Port = $this->port;

				$mail->SMTPAuth = true;
				$mail->Username = $this->usernameFrom;
				$mail->Password = $this->pass;

				$mail->SMTPDebug=0;
				//$mail->Debugoutput = 'html';

				$mail->addAddress($correo,$Nombre);			
				
				$mail->From=$this->usernameFrom;
				$mail->FromName='Administrador TrackingApp';
				$mail->addReplyTo($this->usernameFrom,'Administrador de TrackingApp');
				$mail->Subject = "Validacion de usuario realizado correctamente";
				$mail->AltBody = "Validación correcta";
				$mail->WordWrap= 50;

				//$urlValidar=getURLValidar($correo,$key);			

				$mensaje="<h2>Bienvenido/a ".$Nombre." ".$ape1;
				if($ape2!="")
				{
					$mensaje.=" ".$ape2;
				}
				$mensaje.=' a trackingApp</h2><p>Se ha confirmado correctamente su solicitud de validación de usuario en <b>TrackingApp</b></p>					
					<p>Puede iniciar sesión y acceder a la aplicación desde aquí:</p> 
					<p><a href="'.$URL.'">'.$URL.'</a></p>';
				$mail->msgHTML($mensaje);
				
				$mail->isHTML(true);

				$mail->send();
			}catch(phpmailerException $me){
				Utils::escribeLog("Error: ".$me->getMessage()." | Fichero: ".$me->getFile()." | Línea: ".$me->getLine()." [Error al enviar correo]","debug");
				return false;
			}catch(Exception $e){
				Utils::escribeLog("Error: ".$e->getMessage()." | Fichero: ".$e->getFile()." | Línea: ".$e->getLine()." [Error al enviar correo]","debug");
			}
			
			return $retVal;

		}

		function getURL($correo="",$key="",$validar=false){
			require_once 'Control/BD/mysql_login.php';
			$strURL="http://localhost:".PUERTO;
			
			if(PUERTO==="80")//clase
			{
				$strURL.="/workspace/Servidor/PHP";
			}
			if(!$validar){
				$strURL.="/trackingapp/";
			}else{
				$strURL.="/trackingapp/usuario/validar/".$correo."/".$key;
			}

			return $strURL;
		}

		
	}

	


?>