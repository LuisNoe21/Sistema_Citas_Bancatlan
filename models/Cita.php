<?php

namespace Model;

class Cita extends ActiveRecord { //heredamos de active record
    // Base de datos
    protected static $tabla = 'citas'; //metodo con la tabla de citas
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId']; //objeto con las columnas de la cita

    //instanciamos los datos
    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;


    public function __construct($args = []) //arteglo vacio
    {
        $this->id = $args['id'] ?? null; //arreglo asociativo, si no esta presente sera null
        $this->fecha = $args['fecha'] ?? '';//arreglo asociativo, si no esta presente sera null
        $this->hora = $args['hora'] ?? '';//arreglo asociativo, si no esta presente sera null
        $this->usuarioId = $args['usuarioId'] ?? '';//arreglo asociativo, si no esta presente sera null
    }
}