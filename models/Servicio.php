<?php 

namespace Model; //declaracion de su namespace o alias

class Servicio extends ActiveRecord { //caslse que hereda de active record
    // Base de datos
    protected static $tabla = 'servicios'; //declaracion  de la tabla que contiene los servicios
    protected static $columnasDB = ['id', 'nombre']; // columnas de la tabla servicios

    //registro de atributos
    public $id;
    public $nombre;
    
//creacion del contructor
    public function __construct($args = []) //arreglo vacio
    {
        $this->id = $args['id'] ?? null;  //arreglo asociativo, si no esta presente sera null
        $this->nombre = $args['nombre'] ?? '';//arreglo asociativo, si no esta presente sera un string vacio
    
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Servicio es Obligatorio';
        }
       

        return self::$alertas;
    }
}