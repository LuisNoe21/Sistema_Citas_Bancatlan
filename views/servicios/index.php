

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<ul class="servicios">

<h1 class="nombre-pagina-servicios">Servicios</h1>
<p class="descripcion-pagina-servicios">Administración de Servicios</p>
    <?php foreach($servicios as $servicio) { ?>
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre; ?></span> </p>
           

            <div class="acciones">
                <a class="boton-act" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar</a>

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">

                    <input type="submit" value="Borrar" class="boton-elim">
                </form>
            </div>
        </li>
    <?php } ?>
</ul>