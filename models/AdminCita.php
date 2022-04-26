<?php

namespace Model; //namespace

class AdminCita extends ActiveRecord { //heredamos de active record
    protected static $tabla = 'citasServicios'; // metodo con la tabla citaservicios
    protected static $columnasDB = ['id', 'hora', 'cliente', 'email', 'telefono', 'servicio']; //metodo con los campos de la tabla


    //declaracion de atributos de la tabla
    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    

//creacion de contructor
    public function __construct()
    {
        $this->id = $args['id'] ?? null; //si no esta presente sera null
        $this->hora = $args['hora'] ?? '';//si no esta presente sera string vacio 
        $this->cliente = $args['cliente'] ?? '';//si no esta presente sera string vacio 
        $this->email = $args['email'] ?? '';//si no esta presente sera string vacio 
        $this->telefono = $args['telefono'] ?? '';//si no esta presente sera string vacio 
        $this->servicio = $args['servicio'] ?? '';//si no esta presente sera string vacio 
        
    }
}