<?php

class Core
{

    private static $core;
    
	public static function getInstance()
	{
	    if ( !self::$core )
	    {
	    	$self::$core = new Core();
	    }
	    
	    return self::$core;
	}	
	
    public function __construct()
    {
    }


    public function isLogged()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        return isset($_SESSION['UsuarioID']);
    }

    public static function createPassword()
    {
        // Caracteres de cada tipo
        $combinations = array( );

        // Combinacoes do tipo letras + numeros

        $combinations[] = 'abcdefghijklmnopqrstuvwxyz';
        $combinations[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // numeros (AUTOMATIC_PASSWORD_GENERATION_SOURCE = NUMERIC)
        $combinations[] = '1234567890';

        // Agrupa todos os tipos de de caracteres
        $caracteres = implode('', $combinations);

        // Obtem o tamanho dos caracteres agrupados
        $len = strlen($caracteres);

        $return = null;

        for ( $count = 1;
              $count <= 6;
              $count++ )
        {
            // Cria um número aleatório de 1 até $len para pegar um dos caracteres
            $rand = mt_rand(1, $len);

            // Concatenado o caracteres gerado aleatório na variável $retorno
            $return .= $caracteres[$rand - 1];
        }

        return $return;
    }


    public static function enviarEmail($to, $senha)
    {

        require_once('PHPMailer-master/class.phpmailer.php');
        include("PHPMailer-master/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

        $mail             = new PHPMailer();

        //$body             = file_get_contents('contents.html');
        //$body             = eregi_replace("[\]",'',$body);
        $body = "Sua senha de acesso $senha";

        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host       = "smtp.gmail.com"; // SMTP server
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
        $mail->Username   = "smbd.ftomasini@gmail.com";  // GMAIL username
        $mail->Password   = "smbdroot";            // GMAIL password

        $mail->SetFrom('naoresponda@smbd.com', 'SMBD - Sistema de monitoramento de base de dados');

        //$mail->AddReplyTo('smbd@smbd.com',"First Last");

        $mail->Subject    = "SMBD - Sistema de monitoramento de base de dados";

        //$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

        $body = "


        <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns= \" http://www.w3.org/1999/xhtml\">
 <head>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
  <title>SMBD</title>
  Seu cadastro foi efetuado com sucesso! sua senha de acesso é $senha
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>
</head>
</html>


        ";
        $mail->MsgHTML($body);

        $address = $to;
        $mail->AddAddress($address, $to);

        //$mail->AddAttachment("../html/images/logo.png");      // attachment
        //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

        if(!$mail->Send())
        {
            return false;//echo "Mailer Error: ";// . $mail->ErrorInfo;
        }
        else
        {
            return true;
            //echo "Message sent!";
        }
    }
}
?>
