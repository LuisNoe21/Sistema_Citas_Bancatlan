<?php

namespace Model; //namespace o alias

class CitaServicio extends ActiveRecord { //heredamos de active record
    protected static $tabla = 'citasServicios'; //metodo con la tabla de citaservicios
    protected static $columnasDB = ['id', 'citaId', 'servicioId']; //columnas de la tabla

    //declarando los atributos
    public $id;
    public $citaId;
    public $servicioId;

    //creacion de contructor
    public function __construct($args = []) //areglo vacio de argumentos
    {
       $this->id = $args['id'] ?? null; //arreglo asociativo, si no esta presente sera null
       $this->citaId = $args['citaId'] ?? '';//arreglo asociativo, si no esta presente sera null
       $this->servicioId = $args['servicioId'] ?? ''; //arreglo asociativo, si no esta presente sera null
    }
}