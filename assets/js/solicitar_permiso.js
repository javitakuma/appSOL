var festivosParaCalendario = [];
var fila={};

$(document).ready(function() {	

	//COGEMOS EL ARRAY DE FESTIVOS QUE NOS VIENE DE PHP, PASAMOS LA FEHCA AL FORMATO REQUERIDO PARA JS
	//POR CADA FECHA AÑADIMOS UN OBJETO A LA VARIABLE FESTIVOS PARA CALENDARIO QUE LUEGO RECOGERO EL OBJETO DATEPICKER PARA PINTARLOS COMO FESTIVOS
	for(i=0;i<festivosDesdePhp.length;i++)
	{
		var fechaSplit=festivosDesdePhp[i].f_dia_calendario.split("-");
		
		var fechaFormateada=fechaSplit[1]+"/"+fechaSplit[2]+"/"+fechaSplit[0]
		
		//var fechaFormateada=festivosDesdePhp[0].f_dia_calendario.split("-")[1]+"/"+festivosDesdePhp[0].f_dia_calendario.split("-")[2]+"/"+festivosDesdePhp[0].f_dia_calendario.split("-")[0];
		var fila={};

		fila['Date']=new Date(fechaFormateada);
		festivosParaCalendario.push(fila);
	}	

	
	//CREAMOS UNA VARIABLE CON FECHA 31 DE ENERO DEL AÑO SIGUIENTE AL ACTUAL
	var ultimaFecha=new Date();
	ultimaFecha.setYear(new Date().getFullYear()+1);
	ultimaFecha.setMonth(0);
	ultimaFecha.setDate(31);
	
	//INICIALIZAMOS EL DATEPICKER
	$(function() {
	    $( "#datepicker" ).datepicker();
	    $( ".datepicker" ).multiDatesPicker({
	    	//PARAMETROS DEL OBJETO DATEPICKER
			inline: true,
			showOtherMonths: true,
			firstDay: 1,
			dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			numberOfMonths:2,
			minDate: new Date(),
			maxDate: ultimaFecha,
			
			
			//BUSCA LOS DIAS FESTIVOS Y LOS PONE LA CLASE DE FESTIVOS
			beforeShowDay: function(date) {
			    var result = [true, '', null];
			    
			    var matching = $.grep(festivosParaCalendario, function(event) {
			    	//alert(event.Date.valueOf() === date.valueOf());
			        return (event.Date.valueOf() === date.valueOf());			        
			    });

			    if (matching.length) {
			        result = [true, 'ui-datepicker-week-end', null];
			    }
			    return result;
			},
			
		});
	  });
	
	
	$('#pendientesDebidosMostrar').html($('#diasPendientesDebidos').val());
	$('#pendientesMostrar').html($('#diasPendientes').val());
	
	
	setInterval(function(){ actualizarDiasPendientes() }, 500);
	  
		
});

function actualizarDiasPendientes()
{
	//SELECCIONAMOS LOS DIAS SELECCIONADOS QUE NO SEAN FESTIVOS O ESTEN DESHABILITADOS PARA QUE NO DUPLIQUE
	var seleccionados=$('td.ui-state-highlight').not(".ui-state-disabled").not('.ui-datepicker-week-end').length

	
	//SI LE QUEDANO DIAS DEL AÑO PASADO DESPUES DE LA SELECCION PINTAMOS AQUI
	
	if($('#diasPendientesDebidos').val()>seleccionados)
	{
		$('#pendientesDebidosMostrar').html($('#diasPendientesDebidos').val()-seleccionados);
		$('#pendientesMostrar').html($('#diasPendientes').val());
	}
	//SI HA CONSUMIDO TODOS LOS DEL AÑO PASADO PASAMOS POR AQUI
	else
	{
		diasPendientes=(Number)($('#diasPendientes').val());
		diasPendientesDebidos=(Number)($('#diasPendientesDebidos').val());		
		
		$('#pendientesDebidosMostrar').html(0);
		$('#pendientesMostrar').html(diasPendientes+diasPendientesDebidos-seleccionados);
		
	}
	
	
	
	
}

//PARA PRUEBAS CON EBENTO CLICK EN EL TITULO
function pintar()
{
	
	alert($('#calendario').val());
	
}
