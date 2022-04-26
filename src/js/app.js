let paso = 1;
const pasoInicial = 1; //valor del paso inicial
const pasoFinal = 3; //valor del paso final


//declracion de objeto
// en js los objetos funcionan como si fueran declarados con let, se puede reescribir en ellos sin ningun problema
const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function () { //carga del documento aqui se ejecutan los scripts dentro del html
    eventListeners(); //ejecucion de funcion
    iniciarApp();//ejecucion de funcion
});




function eventListeners() {

    const metodoContacto = document.querySelectorAll('input[name="contacto[contacto]"]');
    metodoContacto.forEach(input => input.addEventListener('click', seleccionarMetodo));
}

function seleccionarMetodo(event) {
    const contactoDiv = document.querySelector('#contacto');


    if (event.target.value === 'telefono') {
        contactoDiv.innerHTML = `
            <label for="telefono">Teléfono</label>
            <input type="tel" placeholder="Tu Teléfono" id="telefono"  name="contacto[telefono]" required>
            <label for="fecha">Fecha Llamada:</label>
            <input type="date" id="fecha"  name="contacto[fecha]" required>
            <label for="hora">Hora Llamada:</label>
            <input type="time" id="hora" min="09:00" max="18:00"  name="contacto[hora]" required>
        `;
    } else {
        contactoDiv.innerHTML = `
            <label for="email">E-mail</label>
            <input type="email" placeholder="Tu Email" id="email" name="contacto[email]" required>
        `;
    }
}



function iniciarApp() {
    mostrarSeccion(); // Muestra y oculta las secciones
    tabs(); // Cambia la sección cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // Consulta la API en el backend de PHP

    idCliente();
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    seleccionarHora(); // Añade la hora de la cita en el objeto

    mostrarResumen(); // Muestra el resumen de la cita
}

function mostrarSeccion() {

    // Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar'); //selecciona la seccion que tenga la clase de mostrar
    if (seccionAnterior) { // si la primera vez existe una seccion con la clase de mostrar se ejecuta la funcion de lo contrario que no haga nada
        seccionAnterior.classList.remove('mostrar'); //una vez seleccionada le quita o remueve la clase de mostrar a la que la tiene
    }

    // Seleccionar la sección con el paso...
    const pasoSelector = `#paso-${paso}`; //le asigno a la variable el elemento con el id paso
    const seccion = document.querySelector(pasoSelector); //selecciona el elemento con el id paso
    seccion.classList.add('mostrar'); //le agrego a ese elemento la clase de mostrar

    //ESTE CODIGO DEBE IR ARRIBA DE TABS
    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual'); //selecciona la clase que tiene actual
    if (tabAnterior) { // si la primera vez no hay elementos con la clase actual entonces ejecuta la funcion si no no hace nada
        tabAnterior.classList.remove('actual'); //remueve la clase de actual al elemento que la posea
    }


    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`); //selecciona el elemento del html con este atributo
    tab.classList.add('actual'); //luego de seleccionarlo le agrega la clase actual
}

//funcion que determina a que tab doy clic y en base a ello ejecuta otras funciones
function tabs() {

    // Agrega y cambia la variable de paso según el tab seleccionado
    const botones = document.querySelectorAll('.tabs button'); //seleciona todos los botones
    botones.forEach(boton => { //iteracion en cada uno de los botones
        //no se pueden usar un addEventListener pero si se puede iterar sobre ella y asociarlo a cada uno de los elementos
        boton.addEventListener('click', function (e) {  //registro de clic a cada uno de los botones
            e.preventDefault(); //evento que se va registrar 

            paso = parseInt(e.target.dataset.paso); //target.dataset.paso a que le dimos clic // parseint me convierte el string a entero
            mostrarSeccion(); //llamado de funcion cada vez que se presione cualquiera de los tabs o botones

            botonesPaginador(); //ejecucion de funcion
        });
    });
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior'); //selecciona botones con el id de anterior
    const paginaSiguiente = document.querySelector('#siguiente'); //selecciona botones con el id de siguiente

    if (paso === 1) { // si estamos en el paso = 1
        paginaAnterior.classList.add('ocultar'); //ocultamos la pagina anterior agregandole la clase de ocultar, la clase ocultar tendra un display none
        paginaSiguiente.classList.remove('ocultar'); // removemos a la pagina siguiente la clase de ocultar
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');//ocultamos la pagina anterior agregandole la clase de ocultar, la clase ocultar tendra un display none
        paginaSiguiente.classList.add('ocultar'); //removemos a la pagina siguiente la clase de ocultar

        mostrarResumen(); //refrescar para consultar el objeto de cita
    } else {
        paginaAnterior.classList.remove('ocultar');//removemos a la pagina siguiente la clase de ocultar para que se muestren ambos botones
        paginaSiguiente.classList.remove('ocultar');//removemos a la pagina siguiente la clase de ocultar para que se muestren ambos botones
    }

    mostrarSeccion(); //muestra la seccion en base a los pasos
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior'); //selecciona el boton con el id de anterior
    paginaAnterior.addEventListener('click', function () { //si da clic en el boton anterior ejecuta esta funcion

        if (paso <= pasoInicial) return; //if para comparar el valor del paso con el paso inicial que es 1
        paso--; // se restara de 1 en 1

        botonesPaginador(); //llamado de la funcion que valida la paginacion
    })
}
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente'); //selecciona el boton con el id de siguiente
    paginaSiguiente.addEventListener('click', function () { //si da clic en el boton siguiente ejecuta esta funcion

        if (paso >= pasoFinal) return; //if para comparar el valor del paso con el paso final que es 3
        paso++; //se incrementara de 1 en 1

        botonesPaginador(); //llamado de la funcion que valida la paginacion
    })
}

async function consultarAPI() { // async para ejecutar funciones al mismo tiempo

    try {
        const url = 'http://localhost:3000/api/servicios'; //ruta de la api
        const resultado = await fetch(url); //funcion que nos permite consumir la api // fetch es lo que se conocia como ajax
        const servicios = await resultado.json(); //utilizacion de metodo json dentro de los prototype
        mostrarServicios(servicios); //muestra todos los servicios de la base de datos

    } catch (error) {
        console.log(error); //muestra mensaje de error si no se puede ejecutar lo que esta dentro del try
    }
}


//Funcion que me muestra todos los servicios
function mostrarServicios(servicios) {
    servicios.forEach(servicio => { //iterar sobre cada uno de los servicios
        const { id, nombre } = servicio; //Destructuring de servicios

        const nombreServicio = document.createElement('P'); //Me crea un parrafo en el html
        nombreServicio.classList.add('nombre-servicio'); //agrego la clase de nombre servicio al parrafo para darle estilos con SASS
        nombreServicio.textContent = nombre; //ese parrafo tendra en su contenido el nombre del servicio

       
       
        const servicioDiv = document.createElement('DIV'); //creacion de un div para que contenga cada uno de los servicios
        servicioDiv.classList.add('servicio'); //le agrego la clase de servicio para darle estilos con SASS
        servicioDiv.dataset.idServicio = id; //le agrego un atributo personalizado que es dataset

        //le asocion un evento de clic a los divs
        servicioDiv.onclick = function () {
            seleccionarServicio(servicio); //ejecucion de la funcion al darle click
        }

        servicioDiv.appendChild(nombreServicio); //inserto el div al dom
        

        document.querySelector('#servicios').appendChild(servicioDiv); //selecciona el div con la clase servicios del view index.php y le inserto el div que ya contiene el nombre del servicio

    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio; //extraigo el id del servicio 
    const { servicios } = cita; //tomamos los servicios de la cita

    // Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`); //selecciono el div con un atributo personalizado

    // Comprobar si un servicio ya fue agregado 
    if (servicios.some(agregado => agregado.id === id)) { //con some itero sobre todo el arreglo y retorno true o false en caso de que un elemento y exista en el arreglo
        // si el id del servicio al que le doy click es igual al id de servicio entonces ya esta agregado
        //agregado.id es lo que esta en memoria
        // Eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id); //filtro los id diferente al id de servicios
        divServicio.classList.remove('seleccionado');// remueve la clase de seleccionado al div
    } else { // si no esta agregado
        // Agregarlo
        cita.servicios = [...servicios, servicio]; //toma una copia de lo que hay en el arreglo de servicios del objeto de citas declarado al inicio con spred y agrega un nuevo servicio
        divServicio.classList.add('seleccionado'); // le agrego la clase de seleccionado al div para darle estilos con sass
    }
    
}

function idCliente() {
    cita.id = document.querySelector('#id').value; //asigna el id al objeto de cita que esta arriba
}
function nombreCliente() { //funcion para asignar el nombre del cliente
    cita.nombre = document.querySelector('#nombre').value; //selecciono el elemnto del formuario de index.php de citas con el id nombre y tomo su value y se lo asigno al nombre del objeto de citas
}

//FUNCION PARA SELECCIONAR LA FECHA DE LA CITA
function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha'); //selecciono el objeto del formulario con el id de fecha
    inputFecha.addEventListener('input', function (e) { //agreo el evento input

        const dia = new Date(e.target.value).getUTCDay(); //getUTC arroja el numero del dia de hoy, siendo domingo el numero cero.//target es el elemento que dispara este evento

        if ([6, 0].includes(dia)) { //si el dia seleccionado es sabado o domingo, include lo busca
            e.target.value = ''; //fecha vacia ya que el formulario lo toma y puede generar la idea al usuario de que se guardo y no es asi
            mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');  //muestra alerta si el dia seleccionado es sabado o domingo
        } else {
            cita.fecha = e.target.value; //lenamos la fecha de la cita con el target value
        }

    });
}

//FUNCION PARA SELECCIONAR LA HORA DE LA CITA
function seleccionarHora() {
    const inputHora = document.querySelector('#hora'); //selecciono el elemento con el id de hora
    inputHora.addEventListener('input', function (e) { //creo un callback y le agreo un evento


        const horaCita = e.target.value; //accedo al valor que retorna este target
        const hora = horaCita.split(":")[0]; //separo el formato de hora que en este caso es 00:00 con split en 2 elementos difenentes y solo accedo al de la posicion 0 que en este caso sera la hora
        if (hora < 10 || hora > 18) { //si la hora es menor que 10 o mayor que 18 
            e.target.value = ''; //si se cumple la condicion vacia el valor de la hora y no selecciona nada
            mostrarAlerta('Hora No Válida', 'error', '.formulario'); //muestra un error si la hora no es valida
        } else {
            cita.hora = e.target.value; // si todo corre bien le asigna la hora seleccionada a la hora de la cita

            
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) { //desaprece = true es para que no desaparezca

    // Previene que se generen más de 1 alerta
    const alertaPrevia = document.querySelector('.alerta'); //selecciono el elemento con la clase de alerta
    if (alertaPrevia) { //si ya existe la remueve
        alertaPrevia.remove(); //remueve la alerta
    }

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV'); //creo un div 
    alerta.textContent = mensaje; //dentro  del div ira el mensaje
    alerta.classList.add('alerta'); //le agrego la clase de alerta al div
    alerta.classList.add(tipo); //le agrego el tipo de alerta

    const referencia = document.querySelector(elemento); //selecciona el elemento
    referencia.appendChild(alerta);

    if (desaparece) { // si desaparece es igual a true
        // Eliminar la alerta
        setTimeout(() => {
            alerta.remove(); //remueve la alerta
        }, 3000);  //despues de 3 segundos
    }

}


//FUNCION PARA MOSTRAR EL RESUMEN DE LA CITA
function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen'); //seleccionamos el elemento con la clase contenido-resumen 

    // Limpiar el Contenido de Resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild); //remueve lo que habia en resumen cada vez que mando llamar resumen
    }
//verificamos que la cita incluye un string vacio OR si incluye algun servicio seleccionado
    if (Object.values(cita).includes('') || cita.servicios.length === 0) {// object es un metodo especifico para objetoss
       
        mostrarAlerta('Faltan datos de Servicios, Fecha Y Hora', 'error', '.contenido-resumen', false); //muestra mensaje de error

        return;
    }

    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita; //extraemos de cita



    // Heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');// creo un H3
    headingServicios.textContent = 'Resumen de Servicios'; //Lo que va decir el h3
    resumen.appendChild(headingServicios); //para mostrar en pantalla

    // Iterando y mostrando los servicios
    servicios.forEach(servicio => { //itero sobre cada uno de los servicios
        const { id, nombre } = servicio; //destructuring de los servicios
        const contenedorServicio = document.createElement('DIV'); //creo un div
        contenedorServicio.classList.add('contenedor-servicio'); //agrego una clase para darle estilos

        const textoServicio = document.createElement('P'); //creo un parrafo
        textoServicio.innerHTML = `<span>Servicio:</span> ${nombre}`; //meto el nombre del servicio en el parrafo
        

        contenedorServicio.appendChild(textoServicio); //para mostrarloen pantalla
        

        resumen.appendChild(contenedorServicio); //para mostrarloen pantalla
    });

    // Heading para Cita en Resumen
    const headingCita = document.createElement('H3');// creo un H3
    headingCita.textContent = 'Resumen de Cita'; //agrego texto al h3
    resumen.appendChild(headingCita); //para mostrar en pantalla

    const nombreCliente = document.createElement('P'); //crea un parrafo
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`; //meto el nombre del cliente dentro del parrafo

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);//instanciamos y le pasamos la fecha que eligio el usuario
    const mes = fechaObj.getMonth(); //obtenemos el mes
    // const dia = fechaObj.getDate();
    const dia = fechaObj.getDate() + 2; //obtenemos el dia, le pongo mas 2 ya que por alguna razon tiene un desfase de un dia cada vez que utilizo date
    const year = fechaObj.getFullYear(); //obtenemos el year año

    const fechaUTC = new Date(Date.UTC(year, mes, dia)); //retorna la fecha, toma los valores por separado

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' } //muestra el nombre largo de dias y meses
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones); //formateo la hora en espaniol y con los nombres largos de dias y meses pasandole las opciones

    const fechaCita = document.createElement('P'); //creo un parrafo
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;//meto la fecha dentro del parrafo

    const horaCita = document.createElement('P'); //creo un parrafo
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`; //meto la hora dentro del parrafo

    // Boton para Crear una cita
    const botonReservar = document.createElement('BUTTON');//creo un boton
    botonReservar.classList.add('boton'); //le agrego una clase para darle estilos
    botonReservar.textContent = 'Reservar Cita'; //agrego texto al boton
    botonReservar.onclick = reservarCita; //cuando de click se va ejecutar esta funcion

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonReservar); //para mostrar en pantalla
}

async function reservarCita() { //funcion asincrona

    const { nombre, fecha, hora, servicios, id } = cita; //destructuring de cita

    const idServicios = servicios.map(servicio => servicio.id);  //itera sobre los servicios y cloca las coincidencias en la variable idservicios
    // console.log(idServicios);

    const datos = new FormData(); //instanciamos el FormData

// de esta manera agregamos datos al FORMDATA
    datos.append('fecha', fecha); //coloco la fecha el formdata
    datos.append('hora', hora); //coloco la hora el formdata
    datos.append('usuarioId', id); //coloco el id el formdata
    datos.append('servicios', idServicios); //coloco los servicios el formdata

    // console.log([...datos]);

    try {
        // Petición hacia la api
        const url = 'http://localhost:3000/api/citas' //url de la api
        const respuesta = await fetch(url, { //MUESTRA LA RESPUESTA de la solicitud a la api
            method: 'POST', //utilizando metodo POST
            body: datos //body es el cuerpo de la peticion que se envia, de esta forma detecta los datos que seleccionamos y los envia como parte de la peticion hacia la URl
        });

        const resultado = await respuesta.json(); //pasando la respuesta a JSON
        console.log(resultado); //muestra el resultado en json

        if (resultado.resultado) { // si el resultado es true
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue creada correctamente',
                button: 'OK'
            }).then(() => {
                setTimeout(() => {
                    window.location.reload(); //para recargar la pagina despues de 3 segundos
                }, 3000);
                 
            })
        }
    } catch (error) {
        //muestra alerta de error si no se pudo guardar la cita
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita'
        })
    }


    // console.log([...datos]);

}

//HOLA  A TODOS
