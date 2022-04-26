<h1 class="nombre-pagina-olvide">Olvide Contraseña</h1>
<p class="descripcion-pagina-olvide">Restablece tu contraseña escribiendo tu email a continuación</p>

<?php 
include_once  __DIR__ . "/../templates/alertas.php" //importo las alertas los templates dentro de views
?>

<form class="formulario" method="POST" action="/olvide"> <!-- le paso el metodo y la ruta -->
    <div class="campo">
        <label for="email">Email</label>
        <input 
        type="email"
        id="email"
        name="email"
        placeholder="Tu email"
        />
    </div>

    <input class="boton" type="submit" value="Enviar Instrucciones">
    </form>

    <div class="opciones">
    <a href="/crear-cuenta">Aun no tienes una cuenta? Crear una</a>
    <a href="/">Ya tienes una cuenta? Inicia sesión</a>
   
    <a href="/contacto">Contáctanos</a>
</div>