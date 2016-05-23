var festivosParaCalendario = [];

var diasOcupadosDesdeAjax;

var diasOcupados=[];//AQUI FINALMENTE METEMOS APROBADOS, RECHAZADOS Y PENDIENTES

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
		var fechaFormateada=diasDesdePhp[i].mes_solic+"/"+diasDesdePhp[i].dia_solic+"/"+diasDesdePhp[i].year_solic;
		
		var fecha_formato_esp=diasDesdePhp[i].dia_solic+"-"+diasDesdePhp[i].mes_solic+"-"+diasDesdePhp[i].year_solic;
		
		//var fecha_formato_esp="ee";
		
		//alert(fechaFormateada);
		
		fila2['desc_observaciones']=diasDesdePhp[i].desc_observaciones;
		//FECHA EN FORMATO ESPAÑOL QUE PONEMOS DE CLASE EN EL CALENDARIO PARA LUEGO PONER EL TAG TITLE
		fila2['fecha_formato_esp']=fecha_formato_esp;
		fila2['fecha']=new Date(fechaFormateada);
		fila2['i_autorizado_n1']=diasDesdePhp[i].i_autorizado_n1;
		fila2['i_autorizado_n2']=diasDesdePhp[i].i_autorizado_n2;
		//dias.push(fila2);
		
		//dias pendientes de aprobar
		if(diasDesdePhp[i].sw_aprobacion_N1==0&&diasDesdePhp[i].sw_aprobacion_N2==0&&diasDesdePhp[i].sw_rechazo==0)
		{
			diasPendientes.push(fila2);
			diasOcupados.push(fila2);
		}
		else//dias aprobados o rechazados
		{
			diasOcupados.push(fila2);
		}
		
	}
	    
	
	//SI HEMOS HABILITADO EDICION...VAMOS A T_PERMISOS_SOLICITADOS_DET Y COGEMOS TODOS LOS DIAS QUE TIENE DE VACACIONES SOLICITADOS
	//NO LO USARE EN PRINCIPIO
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
		
		var year_calendario=$('#year_solicitud').val();
		
		var year_siguiente=((Number)(year_calendario))+1;
		
		var fecha_limite_inicial=new Date(year_calendario-1,9,1);
		
		var fecha_limite_final;
		
		var meses_mostrar=2;
		
		var step_months=1;
		
		//SI ES KEYOTROS NO LIMITAMOS EL NUMERO DE DIAS QUE PUEDE SOLICITAR
		var diasMaximos=(Number)($('#pendientesDebidosMostrar').html())+(Number)($('#pendientesMostrar').html())+(Number)($('#pendientesFuturoMostrar').html());
		
		
		if($('#k_proyecto_solicitud').val()==468)
		{
			diasMaximos=999;
		}
		
		if($('#existe_next_year_bbdd').val()==1)
		{
			fecha_limite_final=new Date(year_siguiente,0,31);
		}
		else
		{
			fecha_limite_final=new Date(year_calendario,11,31);
		}
		
		var show_other_months=true;
		
		//PODER IR ATRAS EN EL TIEMPO PARA SELECCIONAR VACACIONES SIN LIMITE
		if($('#adm_rrhh').val()==1)
		{
			var primer=$('#primer_dia_t_calendario').val();		
			fecha_limite_inicial=new Date(primer.split("-")[0],primer.split("-")[1]-1,primer.split("-")[2]);
		}
		
		
		if($('#solovista').val()==1)
		{
			var primer=$('#primer_dia_t_calendario').val();
			var ultimo=$('#ultimo_dia_t_calendario').val();
			
			fecha_limite_inicial=new Date(primer.split("-")[0],primer.split("-")[1]-1,primer.split("-")[2]);
			fecha_limite_final=new Date(ultimo.split("-")[0],ultimo.split("-")[1]-1,ultimo.split("-")[2]);
			meses_mostrar=[3,4];
			step_months=12;
			show_other_months=false;
			
			/*$('.ui-state-disabled').css('opacity','1.0');*/
			
			$('#calendario').css('padding-bottom','100px');
			$('#calendario').css('font-size','90%');
		}
				
		
	    $( "#calendario" ).multiDatesPicker({
	    	//PARAMETROS DEL OBJETO DATEPICKER
			inline: true,
			
			onSelect:function (date) {
		        // Your CSS changes, just in case you still need them
		       // $('a.ui-state-default').removeClass('ui-state-highlight');
				
				//todo esto lo hacemos porque no bloquea los festivos cuando alcanzamos el maximo de dias
				//miramos sin ha llegado al limite de dias
				var sinDias=( ($('#pendientesDebidosMostrar').html()==0) && ($('#pendientesMostrar').html()==0) && ($('#pendientesFuturoMostrar').html()==0));
				
				//cogemos las fechas seleccionadas
				var fechas_selec=$('#calendario').val().split(', ');
				//miramos si la fecha que ha hecho click esta en el array(en este punto ya la habra añadido o eliminado del array)
				var estaDeseleccionando=fechas_selec.indexOf(date);
				
				//solo lo hacemos una vez porque da tantas vueltas por aqui como fechas haya
				var solo_una_vez=true;
				//si no tiene dias, no hemos pasado, la accion es desseleccionar(ya habra eliminado la fecha del array) y no es keyotros...
				if(sinDias && solo_una_vez && estaDeseleccionando!=-1 && ($('#k_proyecto_solicitud').val()!=468))
				{			
					solo_una_vez=false;
					$('#calendario').multiDatesPicker('toggleDate', date);
					alert("No puedes seleccionar más dias (aunque sean festivos)");
				};		
				
				sincronizar_superior_inferior();
				actualizarDiasPendientes();
			},
			
			showOtherMonths: show_other_months,
			firstDay: 1,
			maxPicks: diasMaximos,
			dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			numberOfMonths:meses_mostrar,   //[2,2]  FORMATO CUADRICULA
			stepMonths:step_months,
			dateFormat: "dd-mm-yy",
			minDate: fecha_limite_inicial,
			maxDate: fecha_limite_final,//new Date(year_siguiente,0,31),
			//EN onChange RECOGEMOS EL MES QUE NOS QUEDA AL CAMBIAR EN EL CALENDARIO Y SE LO PASAMOS A ESA FUNCION
			onChangeMonthYear:function (year, month, inst) {
				
				//FUNCION QUE PONE ETIQUETAS A LAS CELDAS DEL CALENDARIO(ESPERAMOS UN SEGUNDO PARA LLAMARLA PARA QUE ESTE LISTO EL CALENDARIO)				
				setTimeout(function(){ ponerTagsDias() },1000);
				
	        },	
			
			//BUSCA LOS DIAS FESTIVOS Y LOS PONE LA CLASE DE FESTIVOS
	        // FUNCIONA SIN DISCRIMINAR ENTRE DIAS DE ESTA SOLICITUD Y OTRA
	        
	        beforeShowDay: 
				function(date) {
			    var result = [true, '', null];
			    
			    //COMPARA LAS FECHAS DE FESTIVOS Y DEVUELVE TRUE CUANDO ENCUENTRA COINCIDENCIAS
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
			    		//DIAS ACEPTADOS
			    		if(diasOcupados[i].i_autorizado_n1==1&&diasOcupados[i].i_autorizado_n2==1)  
			    		{	
			    			//result = [false, 'dia_aceptado', null];	
			    			desbloqueado=false;
					    	clase='dia_aceptado '+diasOcupados[i].fecha_formato_esp;
			    		}
			    		//DIAS RECHAZADOS
			    		else if(diasOcupados[i].i_autorizado_n1==2||diasOcupados[i].i_autorizado_n2==2)
			    		{	
			    			//result = [false, 'dia_rechazado', null];
			    			desbloqueado=true;
					    	clase='dia_rechazado '+diasOcupados[i].fecha_formato_esp;
			    		}			    		
			    		else//DIAS PENDIENTES
			    		{
			    			desbloqueado=false;
					    	clase='dia_pendiente '+diasOcupados[i].fecha_formato_esp;
			    		}
			    		
			    	}
			    	
			    }
			    
			    //DESHABILITAMOS TODAS LAS CELDAS DEL CALENDARIO HASTA 5 DIAS ANTES DE HOY
			    var fechaActualMenosVariosDias=new Date();
				
			    fechaActualMenosVariosDias.setDate(fechaActualMenosVariosDias.getDate()-5);
			    
			    if(date.valueOf()<fechaActualMenosVariosDias.valueOf())
			    {
			    	//SOLO SI NO ES ADMIN DE PERMISOS
			    	if($('#adm_rrhh').val()!=1)
					{		
			    		desbloqueado=false;
					}
			    	
			    	
			    }
				
			  //SI ES ADMINISTRADOR NO LE LIMITAMOS LAS FECHAS
			    
				
			    
			    
			    //ESTO LO UTILIZAREMOS PARA BLOQUEAR TODAS Y CREAR LA VISTA VER EN CALENDARIO
			    if($('#solovista').val()==1)
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
	
	
	if($('#k_proyecto_solicitud').val()!=468)
	{
		$('#dias_pendientes').css('visibility','visible');
	}
	
	//PONEMOS LOS VALORES DE DIAS PENDIENTES QUE HEMOS RECOGIDOS DE BBDD
	
	$('#pendientesDebidosMostrar').html($('#diasPendientesDebidos').val());
	$('#pendientesMostrar').html($('#diasPendientes').val());
	$('#pendientesFuturoMostrar').html($('#diasPendientesFuturo').val());
	
	
	//COGEMOS LA FECHA DE HOY Y LA FORMATEAMOS A yy-mm-dd PARA IR A LA BBDD y COGER DIAS DE LA BASE DE DATOS
	var fechaActual=new Date();
	
	//CREAMOS LA FECHA A PARTIR DE LA CUAL IREMOS A LA BBDD DONDE COGEREMOS TODOS LOS DIAS TANTO FESTIVOS COMO LABORABLES
	
	//ANTES LO HACIAMOS A 1 DE ENERO POR ESO TIENE ESE NOMBRE DE VARIABLE (AHORA USAMOS DESDE 1 OCTUBRE)
	var fechaInicioYearFormateada=fechaActual.getFullYear()-1+"-"+09+"-"+30;
	
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
	    				//$('#'+dia).attr('disabled',false);
	    			}
	    			else if( dia.split("-")[1]==fechaActualMenosIntervaloFormateada.split("-")[1] && dia.split("-")[0]>fechaActualMenosIntervaloFormateada.split("-")[0])
	    			{
	    				//$('#'+dia).attr('disabled',false);
	    			}
	    			
	    			
	    			//LE DAMOS ESTAS CLASE EN LA TABLA INFERIOR(HORAS)
	    			if(diasCalendario[i].sw_laborable==0)
	    			{
	    				$('#'+dia).addClass('festivo');
	    				$('#'+dia).parent().addClass('festivo');
	    			}	    			
	    		}	            
	       }
	    }); 
	
	//PONEMOS NOMBRE EN TEXTO A LOS MESES DE LA PARTE INFERIOR
	//EMPEZAMOS EN OCTUBRE CUANDO LLEGAMOS A ENERO PONEMOS A FALSE YEARANTERIOR
	//CUANDO VOLVEMOS A PASAR POR ENERO PONEMOS A TRUE YEARSIGUIENTE
	var contMes=9;
	var yearAnterior=true;
	var yearSiguiente=false;
	
	$('#celdas_horas .fila-datos').each(function()
	{
		
		//PARTE QUE PONE NOMBRE A LOS MESES DEL AÑO ANTERIOR 
		if(yearAnterior)
		{
			var mesPintar=meses[contMes%12].substr(0,3);
			var yearPintar=$('#year_solicitud').val()-1;
			$(this).find('td').first().html(mesPintar+" "+yearPintar);
		}
		//PARTE QUE PONE NOMBRE A LOS MESES DEL AÑO SIGUIENTE SI ESTUVIERA CREADO EL CALENDARIO
		else if(yearSiguiente)
		{
			var mesPintar=meses[contMes%12].substr(0,3);
			var yearPintar=(Number)($('#year_solicitud').val()) + 1;
			$(this).find('td').first().html(mesPintar+" "+yearPintar);
		}
		else
		{
			$(this).find('td').first().html(meses[contMes%12]);
		}
		
		
		contMes++;
		//LLEGA A 12 CUANDO TERMINA CON LOS MESES DEL AÑO ANTERIOR
		if(contMes==12)
		{
			yearAnterior=false;
		}
		//LLEGA A 24 CUANDO TERMINA CON LOS MESES DEL AÑO ACTUAL
		if(contMes==24)
		{
			yearSiguiente=true;
		}
	});
	
	//DESHABILITAMOS TODAS LAS CELDAS POR DEFECTO 
	$('#celdas_horas input').attr('disabled',true);
	
	//PONEMOS LOS DOS MESES INICIALES DE LA PARTE INFERIOR, EL MES ACTUAL Y EL QUE VIENE
	//cambiarMesesInferior((new Date().getMonth())+1);
	
	
	//INTERVALO QUE ACTUALIZA LOS VALORES DE DIAS PENDIENTES
	//setInterval(function(){ actualizarDiasPendientes() }, 500);
	//INTERVALO QUE ACTUALIZA LAS TABLAS DE ARRIBA Y ABAJO(DE MOMENTO LO DEJO EN EL EVENTO ONSELECT DEL CALENDARIO)
	
	//setInterval(function(){ sincronizar_superior_inferior() }, 5000);
	
	//SINCRONIZAMOS CUANDO ESTE LISTO EL CALENDARIO
	$('#calendario').ready(function()
	{
		pintarInferiorInicial();
		sincronizar_superior_inferior();
		
		ponerTagsDias();
		
	});
	
	
	$("#grabar_solicitud").click(function(event) 
		    {  				
		    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR  		    			
				var numero_dias=$('#calendario').val().split(" ").length;
				
				if(numero_dias>0&&$('#calendario').val()!="")
				{
					//SI LOS DATOS SON INCORRECTOS NO EJECUTAREMOS EL GRABADO
			    	var cancelar_envio=false;
			    	
			    	//COMPROBAMOS QUE NO HAYA SOLICITADO MAS DIAS DE LOS QUE PUEDE,AUNQUE NUNCA DEBERIA PASAR POR AQUI
			    	if( ((Number)($('#pendientesDebidosMostrar').html())<0 ) || ((Number)($('#pendientesMostrar').html())<0) )
			    	{
			    		cancelar_envio=true;
			    		alert("No puedes pedir más días de los que tienes disponibles.");
			    	}
			    	
			    	//COMPROBAMOS QUE HAYA INTRODUCIDO CAMPO OBSERVACIONES
			    	if($('#observaciones_textarea').val()=="")
			    	{
			    		cancelar_envio=true;
			    		alert("Debes introducir el valor del campo observaciones.");
			    	}
			    	
			    	//VALIDACION TAMAÑO MAXIMO CAMPO OBSERVACIONES
			    	if($('#observaciones_textarea').val().length>150)
			    	{
			    		cancelar_envio=true;
			    		alert("Has sobrepasado el tamaño máximo del campo observaciones(Máximo 150 caracteres).");
			    	}			    	
			    	
			    	//GUARDAMOS ESTAS VARIABLES QUE NECESITAMOS LUEGO
			    	var observaciones=$('#observaciones_textarea').val();			    	
			    	var responsable_solicitud=$('#responsable_solicitud').val();
			    	var diasPendientesDebidos=$('#diasPendientesDebidos').val();
			    	var diasPendientes=$('#diasPendientes').val();
			    	
			    	var horas_jornada;
			    	
			    	//GUARDAMOS EL VALOR DE HORAS JORNADA
			    	
			    	if($('#tipo_solicitud').val()=="KEYVACACIONES")
			    	{
			    		horas_jornada=$('#horas_jornada').val();
			    	}
			    	
                    //GUARDAMOS LAS HORAS IMPUTABLES POR DIA
			    	if($('#tipo_solicitud').val()=="KEYOTROS")
			    	{
                        var horas_por_dias=comprobar_horas_keyotros();
                        
			    		if(horas_por_dias=="novalido")
                        {
                            cancelar_envio=true;
                            alert("Debes completar las horas para los días seleccionados en la parte inferior de la pantalla.");    
                        }
			    	}
                    
                    //COMPROBAMOS SI HA SELECCIONADO UN FESTIVO
			    	if(comprobar_festivos_seleccionados())
			    	{
                        var respuesta_festivos=confirm("Has seleccionado uno o más días festivos, ¿desea continuar?\n\n Si tu calendario laboral es de Madrid no deberías seleccionarlo");
                        if(!respuesta_festivos)
                        {
                            cancelar_envio=true;    
                        }                           
			    	}
			    	//alert($('#pendientesDebidosMostrar').html());
			    	
			    	//alert($('#pendientesMostrar').html());
				}	
				else
				{                    
					alert("No has seleccionado ningún día");
				}	
        
                if(!cancelar_envio)
                {
                    //formato dd-mm-yyyy, dd-mm-yyyy, dd-mm-yyyy
                   var dias_solicitados=$('#calendario').val();
                   var year_solicitud=$('#year_solicitud').val(); 
                   var k_permisos_solic=$('#k_permisos_solic').val();
                   var k_proyecto_solicitud=$('#k_proyecto_solicitud').val();
                                        
                   $.ajax({        
        	       type: "POST",
        	       url: BASE_URL+"general/Permisos/grabar_solicitud",
        	       data: { observaciones : observaciones,responsable_solicitud : responsable_solicitud,diasPendientesDebidos : diasPendientesDebidos,diasPendientes : diasPendientes,dias_solicitados : dias_solicitados,horas_jornada:horas_jornada,horas_por_dias : horas_por_dias,year_solicitud : year_solicitud, k_permisos_solic:k_permisos_solic, k_proyecto_solicitud:k_proyecto_solicitud},
        	       success: function(respuesta) {
        	    	   
        	    	   alert("Su solicitud ha sido grabada");
        	    	   
        	    	   //LE MANDAMOS AL MENU PRINCIPAL PORQUE SI NO FALLA, NO SE PUEDE ACTUALIZAR EL k_permisos_solic
        	           location.href=BASE_URL+"general/Permisos";
        	       }
        	    });     
                }
	});
	
	
	$("#enviar_solicitud").click(function(event) 
		    {    
		    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR  		    			
				var numero_dias=$('#calendario').val().split(" ").length;
				
				if(numero_dias>0&&$('#calendario').val()!="")
				{
					//SI LOS DATOS SON INCORRECTOS NO EJECUTAREMOS EL GRABADO
			    	var cancelar_envio=false;
			    	
			    	//COMPROBAMOS QUE NO HAYA SOLICITADO MAS DIAS DE LOS QUE PUEDE,AUNQUE NUNCA DEBERIA PASAR POR AQUI
			    	if( ((Number)($('#pendientesDebidosMostrar').html())<0 ) || ((Number)($('#pendientesMostrar').html())<0) )
			    	{
			    		cancelar_envio=true;
			    		alert("No puedes pedir más días de los que tienes disponibles.");
			    	}
			    	
			    	//COMPROBAMOS QUE HAYA INTRODUCIDO CAMPO OBSERVACIONES
			    	if($('#observaciones_textarea').val()=="")
			    	{
			    		cancelar_envio=true;
			    		alert("Debes introducir el valor del campo observaciones.");
			    	}
			    	
			    	//VALIDACION TAMAÑO MAXIMO CAMPO OBSERVACIONES
			    	if($('#observaciones_textarea').val().length>150)
			    	{
			    		cancelar_envio=true;
			    		alert("Has sobrepasado el tamaño máximo del campo observaciones(Máximo 150 caracteres).");
			    	}
			    	
			    	var observaciones=$('#observaciones_textarea').val();
			    	
			    	//GUARDAMOS ESTAS VARIABLES QUE NECESITAMOS LUEGO
			    	var responsable_solicitud=$('#responsable_solicitud').val();
			    	var diasPendientesDebidos=$('#diasPendientesDebidos').val();
			    	var diasPendientes=$('#diasPendientes').val();
			    	
			    	var horas_jornada;
			    	
			    	//GUARDAMOS EL VALOR DE HORAS JORNADA
			    	
			    	if($('#tipo_solicitud').val()=="KEYVACACIONES")
			    	{
			    		horas_jornada=$('#horas_jornada').val();
			    	}
			    	
                    //GUARDAMOS LAS HORAS IMPUTABLES POR DIA
			    	if($('#tipo_solicitud').val()=="KEYOTROS")
			    	{
                        var horas_por_dias=comprobar_horas_keyotros();
                        
			    		if(horas_por_dias=="novalido")
                        {
                            cancelar_envio=true;
                            alert("Debes completar las horas para los días seleccionados.");    
                        }
			    	}
                    
                    //COMPROBAMOS SI HA SELECCIONADO UN FESTIVO
			    	if(comprobar_festivos_seleccionados())
			    	{
                        var respuesta_festivos=confirm("Has seleccionado uno o más días festivos, ¿desea continuar?\n\n Si tu calendario laboral es de Madrid no deberías seleccionarlo");
                        if(!respuesta_festivos)
                        {
                            cancelar_envio=true;    
                        }                           
			    	}
			    	//alert($('#pendientesDebidosMostrar').html());
			    	
			    	//alert($('#pendientesMostrar').html());
				}	
				else
				{                    
					alert("No has seleccionado ningún día");
				}	
        
                if(!cancelar_envio)
                {
                    //formato dd-mm-yyyy, dd-mm-yyyy, dd-mm-yyyy
                   var dias_solicitados=$('#calendario').val();
                   var year_solicitud=$('#year_solicitud').val(); 
                   var k_permisos_solic=$('#k_permisos_solic').val();
                   var k_proyecto_solicitud=$('#k_proyecto_solicitud').val();
                                        
                   $.ajax({        
        	       type: "POST",
        	       url: BASE_URL+"general/Permisos/enviar_solicitud",
        	       data: { observaciones : observaciones,responsable_solicitud : responsable_solicitud,diasPendientesDebidos : diasPendientesDebidos,diasPendientes : diasPendientes,dias_solicitados : dias_solicitados,horas_jornada:horas_jornada,horas_por_dias : horas_por_dias,year_solicitud : year_solicitud, k_permisos_solic:k_permisos_solic, k_proyecto_solicitud:k_proyecto_solicitud},
        	       success: function(respuesta) {
        	            alert(respuesta); 
        	            location.href=BASE_URL+"general/Permisos";
        	       }
        	    });     
                }
	});
	
});

function confirmar_boton_volver()
{
	if($('#solovista').val()!=1)
	{
		var respuesta_volver=confirm("¿Seguro que deseas volver? Asegurate de salvar tus cambios si así lo deseas.");
		
		if(respuesta_volver)
		{
			onclick=location.href=BASE_URL+"general/Permisos";
		}
	}	
	else
	{
		onclick=location.href=BASE_URL+"general/Permisos";
	}
		
}

function comprobar_horas_keyotros()
{	
	var dias=$('#calendario').val().split(", ");
    
    var valido=true;
    var respuesta="";
	
	for(i=0;i<dias.length;i++)
	{
        respuesta+=$('#'+dias[i]).val()+" ";
        
        if($('#'+dias[i]).val()==0)
        {
            valido=false;        
        }
    }    
    
    if(!valido)
    {
        respuesta="novalido";    
    }
    
    return respuesta;
    
}


function comprobar_festivos_seleccionados()
{	
	var dias=$('#calendario').val().split(", ");
    
    var festivo_seleccionado=false;
	
	for(i=0;i<dias.length;i++)
	{        
        if($('#'+dias[i]).hasClass("festivo"))
        {
            festivo_seleccionado=true;        
        }        
    }    
    
    return festivo_seleccionado;
    
}


function ponerTagsDias()
{
	/*
	$('#calendario').ready(function()
	{
		
		$(".dia_rechazado").each(function()
		{
			$(this).attr('title','Dia rechazado');
			
		});
		
		$(".dia_pendiente").each(function()
		{
			$(this).attr('title','Dia pendiente');
		});
		
		$(".dia_aceptado").each(function()
		{
			$(this).attr('title','Dia aceptado');
		});
		
	});
	*/
	
	 for(i=0;i<diasOcupados.length;i++)
	    {	   		 
		 	var fecha_esp_buscar=diasOcupados[i].fecha_formato_esp;
		 	$('#calendario').find('.'+fecha_esp_buscar).attr('title',diasOcupados[i].desc_observaciones);
		 	$('#'+fecha_esp_buscar).attr('title',diasOcupados[i].desc_observaciones);
		 	
		 	/*$('#calendario').find('.'+fecha_esp_buscar).css('outline','solid 1px red');*/
		 	/*
		 	var long=$('#calendario').find('.'+fecha_esp_buscar).length;
		 	alert(long);
		 	*/
	    }
	
	
}

//ESTO EN EDITAR PERMISO
function clickarFechas()
{
	
	//FORMATO PARA AGREGAR FECHAS SELECCIONADAS
	//$("#calendario").multiDatesPicker('addDates', [new Date()]);
	//$("#calendario").multiDatesPicker('addDates', [new Date(2016,07,08)]);
	
	
	/*
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
	*/
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
	
	//SI LE QUEDAN DIAS DEL AÑO PASADO DESPUES DE LA SELECCION PINTAMOS AQUI
	
	if($('#diasPendientesDebidos').val()>seleccionados)
	{		
		$('#pendientesDebidosMostrar').html($('#diasPendientesDebidos').val()-seleccionados);
		$('#pendientesMostrar').html($('#diasPendientes').val());
		$('#pendientesFuturoMostrar').html($('#diasPendientesFuturo').val());
	}
	//SI HA CONSUMIDO TODOS LOS DEL AÑO PASADO PASAMOS POR AQUI
	else
	{
		//SI LE QUEDAN DIAS DE ESTE AÑO DESPUES DE LA SELECCION PINTAMOS AQUI
		if( ( (Number)($('#diasPendientesDebidos').val()) + (Number)($('#diasPendientes').val())) > seleccionados)
		{			
			diasPendientes=(Number)($('#diasPendientes').val());
			diasPendientesDebidos=(Number)($('#diasPendientesDebidos').val());		
			
			$('#pendientesDebidosMostrar').html(0);
			$('#pendientesMostrar').html(diasPendientes+diasPendientesDebidos-seleccionados);
			$('#pendientesFuturoMostrar').html($('#diasPendientesFuturo').val());
		}
		else
		{			
			diasPendientes=(Number)($('#diasPendientes').val());
			diasPendientesDebidos=(Number)($('#diasPendientesDebidos').val());	
			diasPendientesFuturo=(Number)($('#diasPendientesFuturo').val());
			
			$('#pendientesDebidosMostrar').html(0);
			$('#pendientesMostrar').html(0);
			$('#pendientesFuturoMostrar').html(diasPendientes+diasPendientesDebidos+diasPendientesFuturo-seleccionados);
		}
		
	}
	
	/*
	if($('#pendientesMostrar').html()==0)
	{
		alert("No te quedan días");
	}
	*/
	
	
}

function pintar_aceptados()
{

	//LO HACEMOS CUANDO HAYA BBDD
	
}

//FUNCION QUE IGUALA LAS TABLAS SUPERIOR E INFERIOR
function sincronizar_superior_inferior()
{	
	$('#celdas_horas td input').removeClass('seleccionado_inferior');
	
	//COGE LOS VALORES SELECCIONADOS
	//FORMATO DD-MM-YYYY
	var fechasSeleccionadas=$('#calendario').val().split(", ");
	
	for(i=0;i<fechasSeleccionadas.length;i++)
	{
		//LES AÑADE UNA CLASE PARA DAR COLOR A TODAS LAS CELDAS INFERIORES QUE EQUIVALEN A LOS SELECCIONADOS
		$('#'+fechasSeleccionadas[i]).addClass('seleccionado_inferior');
	}
	
	
	$('#celdas_horas td').has('input.seleccionado_inferior').each(function()
	{
		//SI ES KEYOTROS HABILITAMOS LAS CELDAS SELECCIONADAS
		if($('#tipo_solicitud').val()=='KEYOTROS')
		{
			$(this).find('input').prop('disabled',false);
		}
		//SI ES KEYVACACIONES LE PONEMOS EL VALOR QUE INTRODUJO EN LA PANTALLA ANTERIOR
		if($('#tipo_solicitud').val()=='KEYVACACIONES')
		{
			var horas_jornada=(Number)($('#horas_jornada').val());
			$(this).find('input').attr('value',horas_jornada);
			$(this).find('input').val(horas_jornada);
		}				
	});
	
	//LAS CELDAS QUE NO ESTEN SELECCIONADAS (LAS HA DESELECCIONADO EL USUARIO) LAS DESHABILITAMOS Y PONEMOS VALOR A 0
	//TAMPOCO CAMBIAMOS A 0 LAS ACEPTADAS O RECHAZADAS PORQUE VIENEN CON INFORMACION DE BBDD
	$('#celdas_horas td input').not('.seleccionado_inferior').not('.rechazado_inferior').not('.aceptado_inferior').not('.pendiente_inferior').each(function()
	{
		//estaba habilitado
		
		$(this).attr('value','0');
		
		if($('#tipo_solicitud').val()=='KEYOTROS')
		{
			$(this).val('0');
			$(this).attr('value',0);
		}
		
		if($('#tipo_solicitud').val()=='KEYVACACIONES')
		{
			$(this).val('0');
			$(this).attr('value',0);
		}
		
		
		
		//deshabilitar la celda
		$(this).prop('disabled',true);
	});
	ocultarMostrarFilas();
}

function ocultarMostrarFilas()
{
	var alternarColor=true;
	
	$('.fila-datos').each(function()
	{
		//alert("fila");
		var ocultar=true;
		var suma=0;
		$(this).find('.input-datos').each(function()
		{
			//alert($(this).val());
			
			if($(this).val()!=0)
			{
				ocultar=false;	
			}
			
			if($(this).hasClass('seleccionado_inferior'))
			{
				ocultar=false;	
			}
			
		});		
		
		if(ocultar)
		{
			$(this).hide();
		}
		else
		{	
			$(this).show();
			
			if(alternarColor)
			{
				$(this).find('td').css('background-color','#EEE');
				alternarColor=false;
			}
			else
			{
				$(this).find('td').css('background-color','#CCC');
				alternarColor=true;
			}
		}
		
	});
	
	
	//quitamos el borde a la ultima fila 
	$('.fila-datos:visible').last().css('border-bottom','solid 0px red');
	$('.fila-datos:visible').last().find('td').css('border-bottom','solid 0px red');
	$('.fila-datos:visible').last().find('td').first().css('border-radius','0 0 0 5px');
	$('.fila-datos:visible').last().find('td').last().css('border-radius','0 0 5px 0');
}

function pintarInferiorInicial()
{
	
	//PINTA EN LA PARTE INFERIOR LOS DIAS ACEPTADOS Y RECHAZADOS
	for(i=0;i<diasDesdePhp.length;i++)
	{
		if(diasDesdePhp[i].dia<10)
		{
			diasDesdePhp[i].dia="0"+diasDesdePhp[i].dia;
		}
		
		if(diasDesdePhp[i].mes<10)
		{
			diasDesdePhp[i].mes="0"+diasDesdePhp[i].mes;
		}
		
		var fecha=diasDesdePhp[i].dia_solic+"-"+diasDesdePhp[i].mes_solic+"-"+diasDesdePhp[i].year_solic;
		
		
        var rechazado=diasDesdePhp[i].i_autorizado_n1==2||diasDesdePhp[i].i_autorizado_n2==2;
        
		if((diasDesdePhp[i].i_autorizado_n1==1)&&(diasDesdePhp[i].i_autorizado_n2==1))
		{
			$('#'+fecha).addClass('aceptado_inferior');	
			$('#'+fecha).attr('value','8');	//CAMBIAR BBDD REAL
		}
		
		/*  DE MOMENTO NO PINTAMOS RECHAZADOS ABAJO
		else if(diasDesdePhp[i].sw_rechazo==-1)
		{
			$('#'+fecha).addClass('rechazado_inferior');	
			$('#'+fecha).attr('value','8');		//CAMBIAR BBDD REAL
		}
		*/
		else if(!rechazado)//DIAS PENDIENTES
		{
			$('#'+fecha).addClass('pendiente_inferior');
			$('#'+fecha).attr('value','8');		//CAMBIAR BBDD REAL
		}
			
	}
	
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
	
	alert($('#10-05-2016').val());
	
	
	/*
	var diasSeleccionados=$('#calendario').val().split(" ");
	
	if($('#calendario').val()=="")
	{
		alert("Ningún dia seleccionado");
	}
	
	alert(dias.length);
	*/
}
