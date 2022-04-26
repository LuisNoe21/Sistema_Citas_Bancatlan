<?php

namespace Controllers;

use Model\Cita; // importacion del modelo de cita
use Model\CitaServicio; // importacion del modelo de citaservicios
use Model\Servicio; // importacion del modelo de servicios

class APIController {
    //no lleva routing por que esta separado del backend y no lo necesita para mostrar los datos ya que regresa una respuesta JSON
    public static function index() {
        $servicios = Servicio::all(); //nos trae todos los registro de esta tabla y sanitiza , este es un metodo estatico por lo que lleva 2 puntos
        echo json_encode($servicios); //json con todos los servicios //json encode es lo mismo que un objeto en JS
    }

    public static function guardar() { //metodo de guardar cuando accedo a api/cita se ejecuta este metodo
        
        // Almacena la Cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar(); //insercion en la base de datos

        $id = $resultado['id'];

        // Almacena la Cita y el Servicio

        // Almacena los Servicios con el ID de la Cita
        $idServicios = explode(",", $_POST['servicios']); //post de servicios, explode sirve para tomar la coma como el separador
        foreach($idServicios as $idServicio) { //itera sobre los idservicios
            //contructor de cita servicio
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args); //nueva instancia de cita servicios
            $citaServicio->guardar(); //insercecion de la base de datos// el foreach va ir guardando cada uno de los servicios con la refrencia de la cita
        }

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}