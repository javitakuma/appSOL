
//Nuestra url base en desarrollo
//var baseUrl='http://localhost/appSOL/';
var baseUrl='';


$(document).ready(function() {
	
	//Con esta funcion actualizamos los totales al cargar la página	
	actualizarTotalesVertical(); 
	
	//TODO lO QUE HAREMOS CUANDO MANDEMOS DATOS
    $("#grabar").click(function(event) 
    {    
    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR    	
    	var itemsCliente = [[1,2],[3,4],[5,6]];
    	var itemsCliente2 = [[11,22],[33,44],[55,66]];
    	//alert(items[0][0]); // 1
    	
    	//NOURLBASE BASEURL
    	
    	//EN DATA EL PRIMER DATO ES EL NOMBRE EN LADO SERVIDOR DE LA VARIABLE, EL SEGUNDO EN LADO CLIENTE
    	
    	//SUCCESS INDICA LA ACCION A SEGUIR DESPUES DE LA RESPUESTA
    	$.ajax({        
    	       type: "POST",
    	       url: baseUrl+"http://localhost/appSOL/general/Imc/mostrar_imc_mes_post",
    	       data: { itemsServidor : itemsCliente,itemsServidor2 : itemsCliente2 },
    	       success: function(respuesta) {
    	            alert(respuesta);        
    	       }
    	    });
    	//Y reenviamos a un nuevo controlador que nos muestra el IMC de ese mes
    	//location.href=baseUrl+"general/Imc/mostrarImcMes/"+month+"/"+year;    		
      });
    
    
    //EVENTO CHANGE DELEGADO PARA LOS INPUT
    $('#tabla_imc').delegate('input', 'change', function(event) {
    	//alert($(this).parent().parent().find('.total_horas_imc').html());
    	actualizarTotalesHorizontal(this);
    	actualizarTotalesVertical();
    	
        // ...
    });
    
});

//ESTA FUNCION ACTUALIZA LA FILA INFERIOR DE LA TABLA EN CADA CAMBIO DE DATO
function actualizarTotalesVertical()
{
		//RECORREMOS TODAS LAS COLUMNAS UNA A UNA ACTUALIZANDO
		for(i=1;i<32	;i++)
		{	
			//SI I ES MENOR DE 10 LE PONEMOS UN 0 DELANTE PARA FORMATO 01,02,ETC
			if(i<10)
			{
				i="0"+i;
			}
			//SUMAMOS TODOS LOS VALORES DE LA COLUMNA Y LO AÑADIMOS
			 var sum = 0;
			    $('.dia'+i).each(function() {
			        sum += Number($(this).find('input').val());
			    });
			    $('#total'+i).html(sum);
		}
		
		//SUMAMOS LOS TOTALES Y LOS PONEMOS EN OTROS DOS CAMPOS
		var sum2 = 0;
	    $('.total_horas_imc').each(function() 
	    {
	        sum2 += Number($(this).html());
	    });
	    $('#horas_consultor').html(sum2);
	    $('#horas_totales').html(sum2);
	   
}

//ESTA FUNCION ACTUALIZA EL TOTAL DE HORAS DE LA LINEA IMC QUE HA SIDO CAMBIADA
function actualizarTotalesHorizontal(element)
{
	var sum=0;	
	$(element).parent().parent().find('td input.input_horas').each(function() {
        sum += Number($(this).val());
    });
	$(element).parent().parent().find('.total_horas_imc').html(sum);
}
