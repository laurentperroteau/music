<?php
/**
 * Form Error - Clase PHP para mostrar errores en un formulario
 * @author Santi Bartolome y Laurent Perroteau
 * @version 0.2 (2012_07_17)
 * @package Enfusion Theme 
 */

include('class/FormError.php');
session_start();

//Creacion de un nuevo objecto de la clase. Se le pasamos el vector con los campos del formulario.
$validar = new FormError($_POST);

//Definicion de los mensajes de error (opcional, por defecto "esp")
$validar -> set_error("name","Your name is compulsory");
$validar -> set_error("email","You must enter your correct email");
$validar -> set_error("privacy", "You need accept the Privacy Conditions");

$validar -> set_error("name","Your name is compulsory", "eng");
$validar -> set_error("email","You must enter your correct email", "eng");
$validar -> set_error("privacy", "You need accept the Privacy Conditions", "eng");

//insertamos los resultados de la validacion un array
$errores = array();
$errores [] = $validar -> validar_campos(array('name'));
$errores [] = $validar -> validar_mail('email');
$errores [] = $validar -> validar_campos(array('privacy'));.

//return (con ancla a los mensajes de errores)
$return = $_SERVER['HTTP_REFERER'] . '#form-error';

//si no se encuentra false en el array, todo correcto
if(!in_array(false, $errores)) {
    
	//creamos las variables de todos los campos name autmaticamente
    foreach ($_POST as $key => $value) {
        $var_name = $key;
        $$var_name = $value;
    }
    
    //escribir el mensaje (asegurarse que existen todos estos campos name)
    $msg  = "<p><strong><em>$name</em> ha dejado un mensaje desde la pagina '$page'</strong></p>";
    $msg .= "<p>Name: $name</p>";
    $msg .= "<p>Email: $email</p>";
    $msg .= "<p>Comments: $comments</p>";
    
    if(isset($newsletter)):
        $msg .= "<p>Y se ha suscrito al boletin</p>";
    else: 
        $msg .= "<p>Y no se ha suscrito al boletin</p>";
    endif;
    
    //envio del mensaje
    $subject = "Solicitud de contacto desde la miniweb en ingles de FX Animation";
    $target = 'laurent@enfusion.es';
    $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
    $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $cabeceras .= 'From: '.$email."\r\n";
    //$cabeceras .= "Bcc:nicolas@enfusion.es, enfusioncomunicacion@gmail.com, laurent@enfusion.es\n"; 

    //si no error, redireccionamos a la pagina de gracias (enviado en un campo hidden)
    $return = $gracias;

    //enviamos si no spam
    if(empty($spam)):
        mail($target,$subject,$msg,$cabeceras);
    endif;

    ?>
    <!-- Google Code for Contacto Conversion Page -->
    <!--<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1064986471;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "UQPCCLWj4AIQ587p-wM";
    var google_conversion_value = 0;
    /* ]]> */
    </script>
    <script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
    </script>
    <noscript>
    <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1064986471/?value=0&amp;label=UQPCCLWj4AIQ587p-wM&amp;guid=ON&amp;script=0"/>
    </div>
    </noscript>-->
    
    <?php //envio a pombobox (uso de la class Boletin)
    if(isset($newsletter)) {
        $boletin = new Boletin('11254','97051');
        $boletin->send($name,$email,$return);
    } 
    else { ?>
        <script>window.location='<?=$return?>'</script>
        <!--si no tiene javascript activado-->
        <noscript>
            <h3>Form successfully sent</h3>
            <p>Return to the previous <a href="<?php echo $return ?>">page</a></p>
        </noscript>
    <?php   
    } //end if newsletter

}
//si un campo no es valido
else {
	//Se guardan los errores en una variable de sesion, y se vuelve a la pagina anterior.
    $_SESSION['error'] = serialize($validar);
	header("Location:".$_SERVER['HTTP_REFERER'] . '#form-error');
}
?>