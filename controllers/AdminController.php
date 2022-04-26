<?php 

namespace Controllers; //namespace

use Model\AdminCita; //import del modelo de admincitas
use MVC\Router; //import del modelo de router

//clase admincontroller
//usa routinng por que consulta la base de datos y muestra las citas
class AdminController {
    public static function index( Router $router ) {
        session_start();

        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d'); //obtiene la fecha del dia de hoy
        $fechas = explode('-', $fecha);

        if( !checkdate( $fechas[1], $fechas[2], $fechas[0]) ) {
            header('Location: /404');
        }

        // Consultar la base de datos
        //este query muestra los datos de la cita  
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        

        $citas = AdminCita::SQL($consulta); //muestra el resultado de la consulta select

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas, //pasamos las citas hacia la vista
            'fecha' => $fecha //pasamos la fecha hacia la vista e index
        ]);
    }
}