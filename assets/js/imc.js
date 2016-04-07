
//Nuestra url base en desarrollo
var baseUrl='http://localhost/appSOL/';


$(document).ready(function() {
	
	//Con esta funcion añadimos el evento onclick al boton abrir del imc
    $(".abrirCell").click(function(event) 
    {    
    	//Leemos el mes y el año de la fila de la casilla donde se ha hecho click
        var month=$(event.target).parent().children('.imcYearCell').html();
    	var year=$(event.target).parent().children('.imcMonthCell').html(); 
    	//Y reenviamos a un nuevo controlador que nos muestra el IMC de ese mes
    	location.href=baseUrl+"general/Imc/mostrarImcMes/"+month+"/"+year;    		
    });
});

