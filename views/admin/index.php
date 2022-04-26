
<?php 
    include_once __DIR__ . '/../templates/barra.php';
?>




<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date"
                id="fecha"
                name="fecha"
                value="<?php echo $fecha; ?>" 
            />
            <!-- imprime la fecha -->
        </div>
    </form> 
</div>

<?php
    if(count($citas) === 0) {
        echo "<h2>No Hay Citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">   
            <?php 
                $idCita = 0;
                foreach( $citas as $key => $cita ) { //itera sobre los id de la cita
   
                    if($idCita !== $cita->id) { //comprueba si el id de la cita es diferente a cita id, en caso de que los id sean iguales no o va a volver a mostrar si no que sola una vez
                        $total = 0;
            ?>
            <li>
                    <p>ID: <span><?php echo $cita->id; ?></span></p> <!-- muestra el id de la cita -->
                    <p>Hora: <span><?php echo $cita->hora; ?></span></p> <!-- muestra la hora de la cita -->
                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p> <!-- muestra el nombre de cliente de la cita -->
                    <p>Email: <span><?php echo $cita->email; ?></span></p> <!-- muestra el correo edel cliente -->
                    <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p> <!-- muestra el telefono del cliente -->

                    <h3>Servicios</h3>
            <?php 
                $idCita = $cita->id;
            } 
            ?>
                     <p>Servicio: <span><?php echo $cita->servicio; ?></span></p>
            
            <?php 
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;

                if(esUltimo($actual, $proximo)) { ?>
                    <!-- <p class="total">Total: <span>$ <?php echo $total; ?></span></p> -->

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-elim" value="Eliminar">
                    </form>

            <?php } 
          } // Fin de Foreach ?>
     </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>