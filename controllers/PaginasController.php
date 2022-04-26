<?php

namespace Controllers; //creacion de namespace Controller
use MVC\Router; //importacion del Router
use PHPMailer\PHPMailer\PHPMailer;  //importacion de PHPMAILER


//clase de pagina controller
class PaginasController {
 


//creacion de la funcion de contacto
    public static function contacto( Router $router ) {
        $mensaje = null; //declaramos el mensaje nulo por defecto

        if($_SERVER['REQUEST_METHOD'] === 'POST') { //revisamos si el metodo es POST

           


            // Validar 
            $respuestas = $_POST['contacto']; // respuesta sera igual al post del formulario de contacto
        
            // create a new object
            $mail = new PHPMailer();

            // configure an SMTP
            // $mail->isSMTP();
            // $mail->Host = 'smtp.mailtrap.io';
            // $mail->SMTPAuth= true;
            // $mail->Port= 2525;
            // $mail->SMTPSecure = 'tls';
            // $mail->Username= '5d3a70f510233f';
            // $mail->Password= '250aa89e451f1f';

            //CREDENCIALES PARA EL ENVIO DE CORREOS
            $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port= 587;
        $mail->SMTPSecure='tls';
        $mail->SMTPAuth= true;
        $mail->Username= 'luisnoe.rodriguez09@gmail.com';
        $mail->Password= 'wtzoqgjsjgpjjtbo';
        
            $mail->setFrom('luisnoe.rodriguez09@gmail.com', $respuestas['nombre']); // remitente del correo
            $mail->addAddress('luisnoe.rodriguez09@gmail.com', 'BienesRaices.com'); // destinatario del correo
            $mail->Subject = 'Tienes un Nuevo Email'; //encabezado del correo

            // Set HTML 
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8'; 

           
            $contenido = "<html>";

        $contenido .= "<table style='width: 100%;
        min-width: 100%;
        background-color: #eeeeee;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
        border-color: #edeae9; 
        border-radius: 4px;
        border-style: solid;
        border-width: 1px;
        height:100%;
        margin-bottom:10px;
        '>";
        $contenido .= "<center>";
        $contenido .= "<img  style='padding: 0;
        margin: 0;
        display: block;
        margin-bottom:10px;
        margin-top:7px;
        width: 150px;
        height: 40px;
        border: none;
       ";
        $contenido .= "</center>";

       

        $contenido .= "<table style='width: 92%;
        min-width: 85%;
        margin-left:auto;
        margin-right:auto;
        background-color: #ffffff;
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
        align:center;
        border-color: #edeae9; 
        border-radius: 4px;
        border-style: solid;
        margin-bottom:10px;
        border-width: 1px;
        '>";

        $contenido .= "<center>";
            $contenido .= "<p><strong>Nueva Consulta:</strong></p>";
            $contenido .= "<p>Nombre: " . $respuestas['nombre'] . "</p>";
            $contenido .= "<p>Mensaje: " . $respuestas['mensaje'] . "</p>";
            $contenido .= "<p>Dirigido a: " . $respuestas['tipo'] . "</p>";
            $contenido .= "</center>";

            if($respuestas['contacto'] === 'telefono') {
                $contenido .= "<center>";
                $contenido .= "<p>Eligió ser Contactado por Teléfono:</p>";
                $contenido .= "<p>Su teléfono es: " .  $respuestas['telefono'] ." </p>";
                $contenido .= "<p>En la Fecha y hora: " . $respuestas['fecha'] . " - " . $respuestas['hora']  . " Horas</p>";
                $contenido .= "</center>";
            } else {
                $contenido .= "<center>";
                $contenido .= "<p>Eligio ser Contactado por Email:</p>";
                $contenido .= "<p>Su Email  es: " .  $respuestas['email'] ." </p>";
                $contenido .= "</center>";
            
            }
            $contenido .= "</table>";
            $contenido .= "</html>";
            $contenido .= '</html>';
            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo';

            

            // send the message
             if(!$mail->send()){ //verificamos si se envia o no el mensaje
                $mensaje = 'Hubo un Error... intente de nuevo';
            } else {
                $mensaje = 'Email enviado Correctamente';
            }

        }
        
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje // mostramos el mensaje en la vista
        ]);
    }
}