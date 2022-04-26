
<?php
    include_once __DIR__ . '/../templates/barra.php';
   
?>

<h1 class="nombre-pagina-actualizar-servicios">Actualizar Servicio</h1>
<p class="descripcion-pagina-actualizar-servicios">Modifica los valores del formulario</p>

<?php
   
    include_once __DIR__ . '/../templates/alertas.php';
?>


<form method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?>
    <input type="submit" class="boton-act" value="Actualizar">
</form>