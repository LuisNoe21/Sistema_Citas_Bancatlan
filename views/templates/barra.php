<div class="barra">
    <p class="welcome">Hola: <?php echo $nombre ?? ''; ?></p>   <!-- muestra el nombre de usuario-->
    <div class="div-boton">
  <a class="boton-cerrar" href="/logout">Cerrar Sesión</a> 
  </div>  <!-- cierra la sesion del sistema -->
</div>

<?php if(isset($_SESSION['admin'])) { ?>  <!-- if para verificar si es admin o no-->
    <h1 class="nombre-pagina-admin">Panel de Administración</h1>
    <div class="barra-servicios">
        <a class="boton-panel" href="/admin">Ver Citas</a>
        <a class="boton-panel" href="/servicios">Ver Servicios</a>
        <a class="boton-panel" href="/servicios/crear">Nuevo Servicio</a>
    </div>
<?php } ?>