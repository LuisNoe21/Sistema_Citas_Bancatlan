<?php

namespace Controllers; //namespace o alias

use MVC\Router; //impport del modelo de router

class CitaController {
    public static function index( Router $router ) {

        session_start();

        isAuth();// ejecuta la funcion que verifica si esta autenticado

        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'], // pasamos la variable de nombre de cliente a la vista
            'id' => $_SESSION['id']// de esta forma esta variable esta disponible en la vsita
        ]);
    }
}