
//Nuestra url base en desarrollo
var baseUrl='http://localhost/appSOL/';


$(document).ready(function() {
	
	//Con esta funcion añadimos el evento onclick al boton abrir del imc
	/*
    $(".abrirCell").click(function(event) 
    {    
    	//Leemos el mes y el año de la fila de la casilla donde se ha hecho click
        var month=$(event.target).parent().children('.imcYearCell').html();
    	var year=$(event.target).parent().children('.imcMonthCell').html(); 
    	//Y reenviamos a un nuevo controlador que nos muestra el IMC de ese mes
    	location.href=baseUrl+"general/Imc/mostrarImcMes/"+month+"/"+year;    		
    });
    */
	
	var condicion=$('#condicion').val();
	
	if(condicion==1)
	{
		$('#enviadosImc').css('font-weight','bold');
		$('#enviadosImc').css('color','black');
		$('#enviadosImc').css('background-color','#0f7c77');
		//background: rgb(15,124,119)==#0f7c77; 
	}
	if(condicion==2)
	{
		$('#noEnviadosImc').css('font-weight','bold');
		$('#noEnviadosImc').css('color','black');
		$('#noEnviadosImc').css('background-color','#0f7c77');
	}
	if(condicion==3)
	{
		$('#todosImc').css('font-weight','bold');
		$('#todosImc').css('color','black');
		$('#todosImc').css('background-color','#0f7c77');
	}
});

