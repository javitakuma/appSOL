var festivosParaCalendario = [];

var diasOcupadosDesdeAjax;

var diasOcupados=[];

//todos los dias de vacaciones
var dias=[];

//dias pendientes de aprobrar
var diasPendientes=[];



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
		
		//mm-dd-yy
		var fechaFormateada=fechaSplit[1]+"/"+fechaSplit[2]+"/"+fechaSplit[0]
		//alert(fechaFormateada);
		var fila={};

		fila['Date']=new Date(fechaFormateada);
		festivosParaCalendario.push(fila);
	}	
	
	/*FORMA ANTIGUA DE COGER DIAS
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
	*/
	
	
	for(i=0;i<diasDesdePhp.length;i++)
	{
		var fila2={};
		var fechaFormateada=diasDesdePhp[i].mes+"/"+diasDesdePhp[i].dia+"/"+diasDesdePhp[i].año
		//alert(fechaFormateada);
		
		fila2['fecha']=new Date(fechaFormateada);
		fila2['sw_aprobacion_N1']=diasDesdePhp[i].sw_aprobacion_N1;
		fila2['sw_aprobacion_N2']=diasDesdePhp[i].sw_aprobacion_N2;
		fila2['sw_rechazo']=diasDesdePhp[i].sw_rechazo;
		//dias.push(fila2);
		
		//dias pendientes de aprobar
		if(diasDesdePhp[i].sw_aprobacion_N1==0&&diasDesdePhp[i].sw_aprobacion_N2==0&&diasDesdePhp[i].sw_rechazo==0)
		{
			diasPendientes.push(fila2);
		}
		else//dias aprobados o rechazados
		{
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
			
			onSelect:function (date) {
		        // Your CSS changes, just in case you still need them
		       // $('a.ui-state-default').removeClass('ui-state-highlight');
				
				//alert($(this).html());
		        if($(this).hasClass('ui-state-highlight'))
		        {
		        	alert("--");
		        };
			},
			
			showOtherMonths: true,
			firstDay: 1,
			dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			numberOfMonths:1,
			dateFormat: "dd-mm-yy",
			minDate: new Date(2016,0,1),
			maxDate: new Date(2016,11,31),
			//EN onChange RECOGEMOS EL MES QUE NOS QUEDA AL CAMBIAR EN EL CALENDARIO Y SE LO PASAMOS A ESA FUNCION
			onChangeMonthYear:function (year, month, inst) {
	            //cambiarMesesInferior(month);
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
			    
			    var desbloqueado=true;
			    var clase="";
			    
			    var matching2=-1;
			    
			    //vacaciones pedidas
			    
			    if (matching.length)//FESTIVOS 
			    {
			        //result = [true, 'ui-datepicker-week-end', null];
			    	desbloqueado=true;
			    	clase='ui-datepicker-week-end';
			    }
			    
			    for(i=0;i<diasOcupados.length;i++)
			    {	   
			    	
			    	if(diasOcupados[i].fecha.valueOf()==date.valueOf())
			    	{
			    		if(diasOcupados[i].sw_aprobacion_N1==-1&&diasOcupados[i].sw_aprobacion_N2==-1)//vacaciones de esta solicitud   CAMBIAR POR VALOR DE BBDD
			    		{	
			    			//result = [false, 'dia_aceptado', null];	
			    			desbloqueado=false;
					    	clase='dia_aceptado';
			    		}
			    		
			    		if(diasOcupados[i].sw_rechazo==-1)//vacaciones de esta solicitud   CAMBIAR POR VALOR DE BBDD
			    		{	
			    			//result = [false, 'dia_rechazado', null];
			    			desbloqueado=false;
					    	clase='dia_rechazado';
			    		}
			    		
			    		//alert("coincide");
			    	}
			    	
			    }
			    
			    //DESHABILITAMOS TODAS LAS CELDAS DEL CALENDARIO HASTA 10 DIAS ANTES DE HOY
			    var fechaActualMenos10Dias=new Date();
				
			    fechaActualMenos10Dias.setDate(fechaActualMenos10Dias.getDate()-10);
			    
			    if(date.valueOf()<fechaActualMenos10Dias.valueOf())
			    {
			    	desbloqueado=false;
			    }
				
			    
			    return [desbloqueado,clase,null];
			    //return result;
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
	
	
	//COGEMOS LA FECHA DE HOY Y LA FORMATEAMOS A yy-mm-dd PARA IR A LA BBDD y COGER DIAS DE LA BASE DE DATOS
	var fechaActual=new Date();
	
	//COGEMOS TODOS LOS FESTIVOS DEL AÑO DE LA BBDD PARA PINTARLOS
	var fechaInicioYearFormateada=fechaActual.getFullYear()-1+"-"+12+"-"+31;
	
	//CREAMOS UNA FECHA CON VALOR 10 DIAS MENOR QUE LA ACTUAL PARA HABILITAR TODOS LOS DIAS POSTERIORES A LA MISMA, YA QUE HEMOS DESHABILITADO TODOS POR DEFECTO
	var fechaActualMenosIntervalo=new Date();
	
	fechaActualMenosIntervalo.setDate(fechaActualMenosIntervalo.getDate() - 10);
	
	
	var fechaActualMenosIntervaloMes=fechaActualMenosIntervalo.getMonth()+1;
	
	if(fechaActualMenosIntervaloMes<10)
	{
		fechaActualMenosIntervaloMes="0"+fechaActualMenosIntervaloMes;
	}
	
	var fechaActualMenosIntervaloDia=fechaActualMenosIntervalo.getDate();
	
	if(fechaActualMenosIntervaloDia<10)
	{
		fechaActualMenosIntervaloDia="0"+fechaActualMenosIntervaloDia;
	}
	
	var fechaActualMenosIntervaloFormateada=fechaActualMenosIntervaloDia+"-"+fechaActualMenosIntervaloMes+"-"+fechaActual.getFullYear();
	
	
	
	
		
	//VAMOS A T_CALENDARIO Y COGEMOS TODOS LOS DIAS A PARTIR DEL DIA DE HOY
	$.ajax({        
	       type: "POST",
	       url: BASE_URL+"general/Permisos/cargar_dias_para_horas",
	       data: { fechaInicioYearFormateada : fechaInicioYearFormateada},
	       dataType:'json',
	       success: function(respuesta) {
	    	   diasCalendario=respuesta;
	    	   
	    	   //POR CADA DIA QUE RECOGEMOS DEL CALENDARIO LOS HABILITAMOS, DEJANDO INHABILITADOS LOS QUE NO EXISTEN
	    	   for(i=0;i<diasCalendario.length;i++)
	    		{
	    		   //VIENE EN FORMATO DD--MM-YYYY
	    			var dia=diasCalendario[i].f_dia_calendario;
	    			
	    			
	    			//SI LA FECHA ES MAYOR A LA ACTUAL - 10 DIAS HABILITAMOS LAS CELDAS 
	    			if(dia.split("-")[1]>fechaActualMenosIntervaloFormateada.split("-")[1])
	    			{
	    				$('#'+dia).attr('disabled',false);
	    			}
	    			else if( dia.split("-")[1]==fechaActualMenosIntervaloFormateada.split("-")[1] && dia.split("-")[0]>fechaActualMenosIntervaloFormateada.split("-")[0])
	    			{
	    				$('#'+dia).attr('disabled',false);
	    			}
	    			
	    			
	    			
	    			//alert(dia);
	    			
	    			if(diasCalendario[i].sw_laborable==0)
	    			{
	    				$('#'+dia).addClass('festivo');
	    				$('#'+dia).parent().addClass('festivo');
	    			}	    			
	    		}	            
	       }
	    }); 
	
	//PONEMOS NOMBRE EN TEXTO A LOS MESES DE LA PARTE INFERIOR
	var contMes=0;
	$('#celdas_horas .fila-datos').each(function()
	{
		$(this).find('td').first().html(meses[contMes]);
		contMes++;
	});
	
	//DESHABILITAMOS TODAS LAS CELDAS POR DEFECTO 
	$('#celdas_horas input').attr('disabled',true);
	
	//PONEMOS LOS DOS MESES INICIALES DE LA PARTE INFERIOR, EL MES ACTUAL Y EL QUE VIENE
	//cambiarMesesInferior((new Date().getMonth())+1);
	
	
	//INTERVALO QUE ACTUALIZA LOS VALORES DE DIAS PENDIENTES
	setInterval(function(){ actualizarDiasPendientes() }, 500);
	//INTERVALO QUE ACTUALIZA LAS TABLAS DE ARRIBA Y ABAJO
	setInterval(function(){ sincronizar_superior_inferior() }, 500);
	
	
});

function clickarFechas()
{
	
	//FORMATO PARA AGREGAR FECHAS SELECCIONADAS
	//$("#calendario").multiDatesPicker('addDates', [new Date()]);
	//$("#calendario").multiDatesPicker('addDates', [new Date(2016,07,08)]);
	
	if($('#habilitar_edicion').val()==1)
	{
		//POR CADA DIA QUE HEMOS RECOGIDO DE LA BBDD LO SELECCIONAMOS EN EL CALENDARIO
		var fechasPintar=[];
		for(i=0;i<diasPendientes.length;i++)
	    {  			
			var dia=(Number)(diasPendientes[i].fecha.getDate());
			var mes=(Number)(diasPendientes[i].fecha.getMonth());//devuelve el mes-1, no lo cambiamos porque lo el calendario lo pinta igual
			var year=(Number)(diasPendientes[i].fecha.getFullYear());
			
			fecha=new Date(year,mes,dia);
			
			fechasPintar.push(fecha);
	    }
		$("#calendario").multiDatesPicker('addDates', fechasPintar);
	}
}


//FUNCION QUE COLOCA EN LA PARTE INFERIOR EL MES QUE SE LE PASA POR PARAMETRO Y EL SIGUIENTE
/*
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
*/

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
