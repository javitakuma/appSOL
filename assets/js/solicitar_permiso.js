var festivosParaCalendario = [];

var diasOcupadosDesdeAjax;

var diasOcupados=[];



var diasCalendario;

//VARIABLE DONDE GUARDAMOS TODOS LOS DIAS DE t_calendario A PARTIR DEL DIA ACTUAL

//ARRAY CON EL NOMBRE DE MESES QUE PINTAREMOS DESPUES EN LA TABLA
var meses=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

$(document).ready(function() {	

	//COGEMOS EL ARRAY DE FESTIVOS QUE NOS VIENE DE PHP, PASAMOS LA FECHA AL FORMATO REQUERIDO PARA JS
	//POR CADA FECHA AÑADIMOS UN OBJETO A LA VARIABLE FESTIVOS PARA CALENDARIO QUE LUEGO RECOGERO EL OBJETO DATEPICKER PARA PINTARLOS COMO FESTIVOS
	for(i=0;i<festivosDesdePhp.length;i++)
	{
		//alert(i);
		var fechaSplit=festivosDesdePhp[i].f_dia_calendario.split("-");
		
		var fechaFormateada=fechaSplit[1]+"/"+fechaSplit[2]+"/"+fechaSplit[0]
		
		var fila={};

		fila['Date']=new Date(fechaFormateada);
		festivosParaCalendario.push(fila);
	}	
	
	if($('#habilitar_edicion').val()==1)
	{
		for(i=0;i<diasYaSolicitados.length;i++)
		{
			var fila2={};
			fila2['Date']=new Date(diasYaSolicitados[i].fecha);
			fila2['k_permisos_solic']=diasYaSolicitados[i].k_permisos_solic;
			diasOcupados.push(fila2);
		}
	}
	
	//SI HEMOS HABILITADO EDICION...VAMOS A T_PERMISOS_SOLICITADOS_DET Y COGEMOS TODOS LOS DIAS QUE TIENE DE VACACIONES SOLICITADOS
	
	/*
	if($('#habilitar_edicion').val()==1)
	{
		$.ajax({        
		       type: "POST",
		       url: BASE_URL+"general/Permisos/cargar_dias_solicitados",
		       dataType:'json',
		       success: function(respuesta) {
		    	   //RECIBE .fecha como fecha en formato mm/dd/yy y .k_permisos_solic
		    	   diasOcupadosDesdeAjax=respuesta;
		    	   	    	   
		    		for(i=0;i<respuesta.length;i++)
		    		{
		    			var fila={};
		    			fila['Date']=new Date(respuesta[i].fecha);
		    			fila['k_permisos_solic']=respuesta[i].k_permisos_solic;
		    			diasOcupados.push(fila);
		    			//diasOcupados.push(fila);
		    		}
		       }
		    }); 
	}
	*/
	
	
	//CREAMOS UNA VARIABLE CON FECHA 31 DE ENERO DEL AÑO SIGUIENTE AL ACTUAL
	var ultimaFecha=new Date();
	ultimaFecha.setYear(new Date().getFullYear()+1);
	ultimaFecha.setMonth(0);
	ultimaFecha.setDate(31);
	
	//INICIALIZAMOS EL DATEPICKER
	$(function() {
	    //$( "#datepicker" ).datepicker();
		var clickar=[];
		
	    $( "#calendario" ).multiDatesPicker({
	    	//PARAMETROS DEL OBJETO DATEPICKER
			inline: true,
			
			onSelect:function(dateText,inst)
			{
				//$(inst).addClass("pepito");
				alert (dateText);
				
				var dia=dateText.split("-")[0];
				var mes=dateText.split("-")[1];
				var year=dateText.split("-")[2];
				
				
				var selectorA=$('td[data-month="'+2016+'"][data-year="'+4+'"]').has('a:contains("'+16+'")');
				//selectorA.parent().addClass("qqqqq");
				alert(selectorA.length);
			},
			
			showOtherMonths: true,
			firstDay: 1,
			dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			numberOfMonths:2,
			dateFormat: "dd-mm-yy",
			minDate: new Date(2016,0,1),
			maxDate: new Date(2016,11,31),
			//EN onChange RECOGEMOS EL MES QUE NOS QUEDA AL CAMBIAR EN EL CALENDARIO Y SE LO PASAMOS A ESA FUNCION
			onChangeMonthYear:function (year, month, inst) {
	            cambiarMesesInferior(month);
	            //clickarFechas();
	        },	
			
			//BUSCA LOS DIAS FESTIVOS Y LOS PONE LA CLASE DE FESTIVOS
	        // FUNCIONA SIN DISCRIMINAR ENTRE DIAS DE ESTA SOLICITUD Y OTRA
	        
	        beforeShowDay: 
				function(date) {
			    var result = [true, '', null];
			    
			    var matching = $.grep(festivosParaCalendario, function(event) {
			        return (event.Date.valueOf() === date.valueOf());			        
			    });
			    
			    var matching2=-1;
			    
			    //vacaciones pedidas
			    for(i=0;i<diasOcupados.length;i++)
			    {	    	
			    	if(diasOcupados[i].Date.valueOf()==date.valueOf())
			    	{
			    		if(diasOcupados[i].k_permisos_solic==2)//vacaciones de esta solicitud   CAMBIAR POR VALOR DE BBDD
			    		{
			    			if(matching.length)//ADEMAS ES FESTIVO PERO LO HA PEDIDO
			    			{
			    				result = [true, 'clickar ui-datepicker-week-end', null];
			    			}
			    			else//vacaciones de esta solicitud QUE NO SEAN FESTIVOS
			    			{
			    				//result = [true, 'clickar', null];
			    				//result = [true, 'ui-state-highlight', null];
			    			}
			    		}
			    		else//VACACIONES DE OTRA SOLICITUD, LO DESHABILITAMOS LA EDICION   amarillo
			    		{
			    			result = [false, 'vacaciones', null];
			    		}
			    		
			    		//alert("coincide");
			    	}
			    	
			    }

			    if (matching.length)//FESTIVOS 
			    {
			        result = [true, 'ui-datepicker-week-end', null];
			    }
			    return result;
			},			
		});	 
	    
	  });
	
	
	
	//FUTURIBLE CLICKAR LOS DIAS
	
	$('#calendario').ready(function()
	{
		clickarFechas();
	});
	
	
	
	
	//PONEMOS LOS VALORES DE DIAS PENDIENTES QUE HEMOS RECOGIDOS DE BBDD
	
	$('#pendientesDebidosMostrar').html($('#diasPendientesDebidos').val());
	$('#pendientesMostrar').html($('#diasPendientes').val());
	
	
	//COGEMOS LA FECHA DE HOY Y LA FORMATEAMOS A yy-mm-dd PARA IR A LA BBDD
	var fechaActual=new Date();
	
	var fechaActualMes=fechaActual.getMonth()+1;
	
	if(fechaActualMes<10)
	{
		fechaActualMes="0"+fechaActualMes;
	}
	
	var fechaActualDia=fechaActual.getDate();
	
	if(fechaActualDia<10)
	{
		fechaActualDia="0"+fechaActualDia;
	}
	
	var fechaActualFormateada=fechaActual.getFullYear()+"-"+fechaActualMes+"-"+fechaActualDia;
	
		
	//VAMOS A T_CALENDARIO Y COGEMOS TODOS LOS DIAS A PARTIR DEL DIA DE HOY
	$.ajax({        
	       type: "POST",
	       url: BASE_URL+"general/Permisos/cargar_dias_para_horas",
	       data: { fechaActualFormateada : fechaActualFormateada},
	       dataType:'json',
	       success: function(respuesta) {
	    	   diasCalendario=respuesta;
	    	   
	    	   //POR CADA DIA QUE RECOGEMOS DEL CALENDARIO LOS HABILITAMOS, DEJANDO INHABILITADOS LOS QUE NO EXISTEN
	    	   for(i=0;i<diasCalendario.length;i++)
	    		{
	    			var dia=diasCalendario[i].f_dia_calendario;
	    			$('#'+dia).attr('disabled',false);
	    			
	    			if(diasCalendario[i].sw_laborable==0)
	    			{
	    				$('#'+dia).addClass('festivo');
	    				$('#'+dia).parent().addClass('festivo');
	    			}	    			
	    		}	            
	       }
	    }); 
	
	//PONEMOS NOMBRE EN TEXTO A LOS MESES
	var contMes=0;
	$('#celdas_horas .fila-datos').each(function()
	{
		$(this).find('td').first().html(meses[contMes]);
		contMes++;
	});
	
	//DESHABILITAMOS TODAS LAS CELDAS POR DEFECTO 
	$('#celdas_horas input').attr('disabled',true);
	
	//PONEMOS LOS DOS MESES INICIALES DE LA PARTE INFERIOR, EL MES ACUTAL Y EL QUE VIENE
	cambiarMesesInferior((new Date().getMonth())+1);
	
	
	//INTERVALO QUE ACTUALIZA LOS VALORES DE DIAS PENDIENTES
	setInterval(function(){ actualizarDiasPendientes() }, 500);
	//INTERVALO QUE ACTUALIZA LAS TABLAS DE ARRIBA Y ABAJO
	setInterval(function(){ sincronizar_superior_inferior() }, 500);
	
	
});

function clickarFechas()
{
	//$('#calendario').multiDatesPicker("setDate", new Date(2016,07,08) );
	//$('#calendario').multiDatesPicker("setDate", new Date(2016,07,09) );
	
	//$("#calendario").multiDatesPicker('addDates', [new Date()]);
	//$("#calendario").multiDatesPicker('addDates', [new Date(2016,07,08)]);
	//$("#calendario").multiDatesPicker('addDates', [new Date(),(new Date()).setDate(22)]);
	
	if($('#habilitar_edicion').val()==1)
	{
		var fechas=[];
		for(i=0;i<diasOcupados.length;i++)
	    {
				
	    		if(diasOcupados[i].k_permisos_solic==2)//vacaciones de esta solicitud   CAMBIAR POR VALOR DE BBDD
	    		{    			
	    			var dia=(Number)(diasOcupados[i].Date.getDate());
	    			var mes=(Number)(diasOcupados[i].Date.getMonth());//devuelve el mes-1, no lo cambiamos porque lo el calendario lo pinta igual
	    			var year=(Number)(diasOcupados[i].Date.getFullYear());
	    			
	    			
	    			
	    			fecha=new Date(year,mes,dia);
	    			
	    			fechas.push(fecha);
	    			
	    			//alert(dia+" "+mes+" "+year);
	    			/*
	    			dia=7;
	    			mes=4;
	    			year=2016;
	    			*/
	    			
	    			//$("#calendario").multiDatesPicker('addDates', [fecha]);
	    			    			
	    		}
	    		
	    }
		$("#calendario").multiDatesPicker('addDates', fechas);
	}
	
	
    
}


//FUNCION QUE COLOCA EN LA PARTE INFERIOR EL MES QUE SE LE PASA POR PARAMETRO Y EL SIGUIENTE
function cambiarMesesInferior(numeroMes)
{
	var mesActual=numeroMes;
	var mesSiguiente=numeroMes+1;
	//OCULTAR TODAS LAS FILAS DE MESES
	for(i=1;i<=12;i++)
	{
		if(i<10)
		{
			i="0"+i;
		}
		$('#fila_mes_'+i).addClass('ocultar-fila');
	}
	
	if(numeroMes<10)
	{
		mesActual="0"+numeroMes;
		mesSiguiente=numeroMes+1;
		if(mesSiguiente<10)
		{
			mesSiguiente="0"+mesSiguiente;
		}
	}
	
	//mesActual="0"+numeroMes;
	//mesSiguiente="0"+((new Date().getMonth())+2);
	
	$('#fila_mes_'+mesActual).removeClass('ocultar-fila');
	$('#fila_mes_'+mesSiguiente).removeClass('ocultar-fila');
}


//PODRIAMOS AÑADIR AQUI EL HABILITAR LAS CELDAS Y QUITALO DE ARRIBA DEL AJAX
function actualizarDiasPendientes()
{
	//SELECCIONAMOS LOS DIAS SELECCIONADOS QUE NO SEAN FESTIVOS O ESTEN DESHABILITADOS PARA QUE NO DUPLIQUE
	//var seleccionados=$('td.ui-state-highlight').not(".ui-state-disabled").length
	var seleccionados=$('#calendario').val().split(" ").length;
	
	if($('#calendario').val()=="")
	{
		//alert("Ningún dia seleccionado");
		seleccionados=0;
	}
	
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
function sincronizar_superior_inferior()
{	
	$('#celdas_horas td input').removeClass('seleccionado_inferior');
	
	var fechasSeleccionadas=$('#calendario').val().split(", ");
	
	for(i=0;i<fechasSeleccionadas.length;i++)
	{
		$('#'+fechasSeleccionadas[i]).addClass('seleccionado_inferior');
	}
	
	
	$('#celdas_horas td input').not('.seleccionado_inferior').each(function()
	{
		$(this).val('0');
	});
	
	
	
}

//PARA PRUEBAS CON EVENTO CLICK EN EL TITULO
function pintar()
{
	
	//seleccionar una celda
	/*
	var selectorA=$('td[data-month="5"][data-year="2016"]').has('a:contains("15")');	
	selectorA.click();
	*/
	
	//alert(cantidad);
	
	/*
	$( 'td.ui-state-highlight').each(function()
			{
				$(this).find('a').addClass('ui-stateaaaaaaaaaaa-active');
				$(this).addClass('ui-stateaaaaaaaaaaa-active');
				
			});
	*/
	
	
	//alert($( 'td.ui-state-highlight').length);
	
	/*
	$( 'td.ui-state-highlight').each(function()
	{
		alert("---");
		$(this).click();
	});
	*/
	
	//alert($('#calendario').multiDatesPicker('value'));
	
	
	//sincronizar_superior_inferior();
	
	alert($('#calendario').val());
	
	/*
	var diasSeleccionados=$('#calendario').val().split(" ");
	
	if($('#calendario').val()=="")
	{
		alert("Ningún dia seleccionado");
	}
	
	alert(dias.length);
	*/
}
