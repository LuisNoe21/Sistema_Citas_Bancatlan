<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    // Verificar el password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true; //variable de inicio de session

                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    //funcion para cerrar session
    public static function logout()
    {
        session_start(); //inicia la session
        $_SESSION = []; //elimina los valores de la session
        header('Location: /'); //redirecciona al home y cierra la session
    }

    public static function olvide(Router $router) //creacion de funcion olvide mi password
    {

        $usuario = new Usuario(); //nuevo objeto de usuario

        $alertas = []; //arrgelo vacio de alertas
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //validamos que el metodo sea post
            $auth = new Usuario($_POST); //le pasamos los datos que escribe el usuario
            $alertas = $auth->validarEmail(); // llenamos las alertas

            if (empty($alertas)) { //validamos si las alertas estan o no vacias
                $usuario = Usuario::where('email', $auth->email);  // si estan vacias hacemos una busuqeda del email

                if ($usuario && $usuario->confirmado === "1") { // si el usuario existe y esta confirmado
                    //Generar token

                    $usuario->crearToken(); //llmado del metodo que genera un token
                    $usuario->guardar();  //gurdamos el nuevo token creado

                    // ENVIAR EL EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones(); // LE PASO la funcion de enviar instrucciones

                    header('Location: /mensaje-recuperar');
                    // Usuario::setAlerta('exito', 'Revisa tu Correo'); //muestra alerta exitosa
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado'); //muestra alerta de error

                }
            }
        }
        $alertas = Usuario::getAlertas(); //obtiene las alertas 
        $router->render('auth/olvide-password', [
            'alertas' => $alertas //pasamos las alertas a la vista
        ]); //renderizo la vista de olvide mi password que se encuentra en views/auth

    }

    public static function recuperar(Router $router) //creacion de funcion recuperar password y le pasamos el router
    {
        $alertas = []; //arreglo vacio de alertas
        $error = false;


        $token = s($_GET['token']); //obtencion del token

        //Buscar usuario por su token
        $usuario = Usuario::where('token', $token); //busca el token

        if (empty($usuario)) { //revisamos si el usuario esta vacio o no
            Usuario::setAlerta('error-token', 'Token no valido'); // mensaje de alerta en caso de no encontrar nada

            $error = true; // error cambia true en caso de no hallar nada
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //verifica si el metodo es POST

            $password = new Usuario($_POST); //sincronizamos Con los datos del POST
            $alertas = $password->validarPassword(); //le pasamos el metodo que valida el password

            if (empty($alertas)) { //VALIDAMOS SI LAS ALERTAS ESTAN VACIAS
                $usuario->password = ''; //vaciamos el password
                $usuario->password =   $password->password; //le pasamos el nuevo password
                $usuario->hashPassword(); //hash del password
                $usuario->token = ''; //vaciamos el token

                $resultado = $usuario->guardar();

                if ($resultado) {
                    //    header('Location: /');
                    header('Location: /mensaje-restablecer');
                }
            }
        }



        // debuguear($usuario);

        $alertas = Usuario::getAlertas(); //obtenemos las alertas
        $router->render('auth/recuperar-password', [ //renderizacion de las vistas
            //le pasamos las alertas a la visa
            'alertas' => $alertas,
            'error' => $error

        ]);
    }


    //funcion que muestra el mensaje de contasenia restablecida
    public static function mensajerestablecer(Router $router)
    {

        $alertas = []; //arreglo vacio de alertas

        Usuario::setAlerta('exito', 'Contraseña restablecida con exito'); //mensaje de exito de contrasenia restablecida con exito

        $alertas = Usuario::getAlertas(); //obtenemos las alertas

        $router->render('auth/mensaje-restablecer', [
            'alertas' => $alertas // pasamos las alertas a la vista
        ]);
    }

    //funcion que muestra mensaje despues de ingresar el email para restablecer password
    public static function mensajererecuperar(Router $router)
    {

        $alertas = []; //arreglo vacio de alertas

        Usuario::setAlerta('exito', 'Hemos enviado las intrucciones para restablecer tu contraseña, a tu correo.'); //mostramos alertas de exito

        $alertas = Usuario::getAlertas(); //obtencion de las alertas

        $router->render('auth/mensaje-recuperar', [
            'alertas' => $alertas
        ]); // renderizacion de la vista del mensaje
    }


    //Nos permite visualizar la vista de crear cuenta
    public static function crear(Router $router) //creacion de funcion crear cuenta
    {

        $usuario = new Usuario; //asignando la clase Usuario de usuarios.php a la variable usuarios
        //Alertas Vacias
        $alertas =  []; //declarando alertas como un arreglo vacio


        //revisa que el metodo sea post
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //verifica si el metodo es POST

            $usuario->sincronizar($_POST); //sincroniza con los datos de post
            $alertas =  $usuario->validarNuevaCuenta(); //si el metodo requeste es post entonces alertas sera igual a lo contenido dentro de la funcion validar nueva cuenta de usuarios.php

            //REVISAR QUE ALERTAS ESTE VACIO
            if (empty($alertas)) { //verificamos si las alertas estan vacias o no

                $resultado =  $usuario->existeUsuario(); //pasamos el metodo que valida si existe un usuario o no
                if ($resultado->num_rows) { //valida si hay usuarios
                    $alertas = Usuario::getAlertas(); //en caso de haber resultados llena alertas y lo manda hacia la vista
                } else {

                    $usuario->hashPassword(); //instanciando funcion que hashea el password

                    //generar un token unico
                    $usuario->crearToken(); //llamado de la funcion que crea el token de usuario


                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion(); //funcion que envia el correo 

                    //CREAR EL USUARIO
                    $resultado = $usuario->guardar(); //gurdado de los datos de usuario
                    if ($resultado) {
                        header('Location: /mensaje'); // si resultados se llena, me redirecciona a /mensaje
                    }
                }
            }
        }


        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {

        $alertas = []; // arreglo vacio de alertas

        Usuario::setAlerta('notificacion', 'Hemos enviado las intrucciones para confirmar tu cuenta, a tu email.');

        $alertas = Usuario::getAlertas(); //obtencion de las alertas 

        $router->render('auth/mensaje', [
            'alertas' => $alertas //pasamos las alertas a la vista
        ]); // renderizacion de la vista del mensaje
    }


    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error-token', 'Token No Válido');
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
