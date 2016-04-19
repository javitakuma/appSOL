
//Nuestra url base en desarrollo
//NOURLBASE BASEURL CAMBIAR
//var baseUrl='http://localhost/appSOL/';    YA ESTA EN HEAD.PHP

var lineasEliminadas=[];
var lineasCreadas=[];
var lineasActualizadas=[];

//EJEMPLO JSON
/*
var emple={"employees":[
             {"firstName":"John", "lastName":"Doe"},
             {"firstName":"Anna", "lastName":"Smith"},
             {"firstName":"Peter", "lastName":"Jones"}
         ]};
*/
//HASTA QUE NO SE CARGA EL DOCUMENTO NO CARGA ESTAS FUNCIONES
$(document).ready(function() {
	
	//Con esta funcion actualizamos los totales al cargar la página	
	actualizarTotalesVertical(); 
	
	if($('#celdas_deshabilitadas').val()=='deshabilitadas')
	{
		$('.input_horas').each(function()
		{
			$(this).prop('disabled', 'disabled');
		});
		
		$('.comentarios_textarea').each(function()
				{
					$(this).prop('disabled', 'disabled');
				});
	}
	
	//EVENTO CLICK PARA EL BOTON AGREGAR PROYECTO
	$("#agregar_proyecto").click(function() 
	{
		//MIRAMOS QUE HAYA SELECCIONADO UN PROYECTO
		if($('#cod_proyecto_select').val()==0||$('#tipo_proyecto').val()==0)
		{
			alert("No has seleccionado ningún proyecto");
		}
		else
		{
			agregar_proyecto_a_tabla();
		}
		
	});
	
	
	
	//TODO lO QUE HAREMOS CUANDO MANDEMOS DATOS
    $("#grabar").click(function(event) 
    {    
    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR  
    	
    	//SI LOS DATOS SON INCORRECTOS NO EJECUTAREMOS EL GRABADO
    	var cancelar_envio=false;
    	$('.total_horas_imc').each(function()
    	{
    		if($(this).html()==0||isNaN($(this).html()))
    		{
    			cancelar_envio=true;
    			alert("Tienes un total de horas de una linea de IMC con valor 0 o valores erroneos.");
    		}
    	});
    	
    	
    	$('.input_horas').each(function()
    	    	{
    	    		if($(this).val()<0||$(this).val()>8)
    	    		{
    	    			cancelar_envio=true;
    	    			alert("Tienes un total de horas de una linea de IMC con valor 0 o valores erroneos en las horas.");
    	    		}
    	    	});
    	
    	$('.comentarios_textarea').each(function()
    	{
    		
    		var nombre_proyecto=$(this).parent().parent().find('td:first').html();
    		
    		
    		
    		if(nombre_proyecto=='PRO450')//CAMBIAR PRODUCCION KEYPREVENTA
    		{  
    			var regex_preventa=/^[\w\W]+\/[\w\W]+\/[\w\W]+$/;
    			
    			if(regex_preventa.test($(this).val()))
    			{
    				//alert("valida");
    			}
    			else
    			{
    				cancelar_envio=true;
    				alert("El código de proyecto KEYPREVENTA debe incluir un comentarios con el formato:\n\n Cliente / Tecnología / Actividad \n\nEjemplo:Direct / Tableau Qlik Sense / Poc");
    			}     			
    		}
    		
    		if(nombre_proyecto=='PRO468')//CAMBIAR PRODUCCION KEYOTROS
    		{  
    			
    			//SI FALLARA QUITAR  LO QUE NO SEA TEXTO PLANO
    			
    			//PARA AGREGAR UNO NUEVO PONER EL TEXTO +  ([\s]+[\w\W]*)*$ PARA CAMPOS QUE NO REQUIERAN EXPLICACION Y TEXTO + ([\s]+[\w\W]+)+$/ PARA CAMPOS QUE SI LO REQUIERAN
    			
    			var expresiones_validas = [/^HOSPITAL FAMILIAR([\s]+[\w\W]+)+$/, /DEFUNCION FAMILIAR([\s]+[\w\W]+)+$/,  /ASUNTOS PROPIOS([\s]+[\w\W]+)+$/,/MUDANZA([\s]+[\w\W]*)*$/,/MATRIMONIO([\s]+[\w\W]*)*$/,/PATERNIDAD([\s]+[\w\W]*)*$/,/ACADEMICO([\s]+[\w\W]+)+$/,/LACTANCIA([\s]+[\w\W]*)*$/,/PRENATAL([\s]+[\w\W]*)*$/,/PERMISO KEYRUS([\s]+[\w\W]+)+$/,/PERMISO SIN SUELDO([\s]+[\w\W]+)+$/,];

    			   	var key_otros_valido=false;		    

    			    for (i=0; i < expresiones_validas.length&&!key_otros_valido; i++) 
    			    {
    			        if ($(this).val().match(expresiones_validas[i])) 
    			        {
    			        	key_otros_valido=true;
    			            //alert(expresiones_validas[i]+"Bien");
    			        }      			        
    			    }
    			    
    			    if(!key_otros_valido)
    			    {
    			    	cancelar_envio=true;
    			    	alert("El código de proyecto KEYOTROS debe comenzar con una de las siguientes expresiones:\n" +
    			    			"HOSPITAL FAMILIAR, DEFUNCION FAMILIAR, DEFUNCION FAMILIAR, ASUNTOS PROPIOS, MUDANZA, MATRIMONIO, PATERNIDAD, ACADEMICO, ACADÉMICO, LACTANCIA, PRENATAL, PERMISO KEYRUS O PERMISO SIN SUELDO/ ");
    			    }

    			    

    		};
    			/*
    			if(regex_preventa.test($(this).val()))
    			{
    				//alert("valida");
    			}
    			else
    			{
    				cancelar_envio=true;
    				alert("El código de proyecto KEYPREVENTA debe incluir un comentarios con el formato:\n\n Cliente / Tecnología / Actividad \n\nEjemplo:Direct / Tableau Qlik Sense / Poc");
    			}   
    			*/  			
    		
    	    		
    	 });
    	
    	
    	//SI NO HEMOS CANCELADO ENTRAMOS AQUI
    	if(!cancelar_envio)
    	{
    		crearObjetosParaGrabar();
        	
        	var total_horas=Number($('#horas_consultor').html());    
        	var total_horas=Number($('#k_imc').val()); 
        	//EN DATA EL PRIMER DATO ES EL NOMBRE EN LADO SERVIDOR DE LA VARIABLE, EL SEGUNDO EN LADO CLIENTE
        	
        	//SUCCESS INDICA LA ACCION A SEGUIR DESPUES DE LA RESPUESTA
        	
        	$.ajax({        
        	       type: "POST",
        	       url: BASE_URL+"general/Imc/mostrar_imc_mes_post",
        	       data: { lineasActualizadas : lineasActualizadas,lineasCreadas : lineasCreadas,lineasEliminadas : lineasEliminadas,total_horas:total_horas},
        	       success: function(respuesta) {
        	            alert(respuesta);    	            
        	            location.reload();
        	       }
        	    }); 
    	}
    	
    			
      });
    
    //EVENTOS PARA CAMBIAR TAMAÑO DEL TEXTAREA
    $('textarea').on('blur',function()
    {
    	$(this).removeClass('textareaGrande');
    });
    
    $('textarea').on('focus',function()
    {
    	$(this).addClass('textareaGrande');
    });
    
    
    
    //EVENTO CHANGE PRIMER SELECT 
    $("#tipo_proyecto").on('change',function(event) 
    	    {    
    	    	    	    	
    	    	//NOURLBASE BASEURL CAMBIAR
    	    	
    	    	//EN DATA EL PRIMER DATO ES EL NOMBRE EN LADO SERVIDOR DE LA VARIABLE, EL SEGUNDO EN LADO CLIENTE
    	    	
    	    	//SUCCESS INDICA LA ACCION A SEGUIR DESPUES DE LA RESPUESTA
    	
    			var tipoProyecto=$('#tipo_proyecto').val();
    			var mes=$('#mes_imc').html();
    			var year=$('#year_imc').html();
    			if(tipoProyecto==0)
    			{
    				deshabilitarSelectProyecto();
    			}
    			else
    			{
    				$('#cod_proyecto_select').prop('disabled', false);
    				$.ajax({        
     	    	       type: "POST",
     	    	       url: BASE_URL+"general/Imc/obtener_lista_proyectos_por_tipo",
     	    	       data: { tipoProyecto : tipoProyecto,mes : mes,year : year},
     	    	       dataType:'json',
     	    	       success: function(respuestaAjax) {
     	    	    	    proyectosArray=respuestaAjax;
     	    	            pintarCodigosSelect(respuestaAjax);        
     	    	       }
     	    	    });
    			}    	    	    		
    	      });
    
    
    //EVENTO CHANGE DELEGADO PARA LOS INPUT
    /*
    $('#tabla_imc').delegate('input', 'change', function(event) {
    	//alert($(this).parent().parent().find('.total_horas_imc').html());
    	if (validarValorCelda(this))
    	{
    		actualizarTotalesHorizontal(this);
        	actualizarTotalesVertical();
    	}
    	else
    	{
    		alert("Has introducido un valor erroneo en la celda");
    		$(this).focus();
    	}
    	
        // ...
    });
    */
    
    
    //EVENTO PARA LA ACCION BLUR DE LOS INPUT
    $('#tabla_imc').delegate(".input_horas", 'blur', function(event) {
    	//alert($(this).parent().parent().find('.total_horas_imc').html());
    	if (validarValorCelda(this))
    	{
    		actualizarTotalesHorizontal(this);
        	actualizarTotalesVertical();
    	}
    	else
    	{
    		alert("Has introducido un valor erroneo.");
    		$(this).focus();
    		actualizarTotalesHorizontal(this);
        	actualizarTotalesVertical();
        	$(this).focus();
    	}
    	
        // ...
    });
    
    
  //EVENTO PARA LA ACCION CLICK DE LOS BOTONES ELIMINAR
    $('#tabla_imc').delegate(".eliminar_fila", 'click', function(event) 
    {    	
    	if (confirm("¿Seguro que desea eliminar esa fila?\nNo se guardaran los cambios hasta que no presiones el botón Grabar datos."))
    	{   
    		var attr_class=$(this).parent().parent().find('td:first-child').attr('class');
    		var k_linea_imc_borrar=attr_class.split(' ')[0];
    		
    		if(attr_class.split(' ')[0]=='nueva')
    		{
    			//borrando fila no grabada(como no estaba grabada,la borramos y nos olvidamos de ella)
    			$(this).parent().parent().remove();
        		actualizarTotalesVertical();   			
    		}
    		else
    		{
    			//borrando fila que estaba grabada(la guardamos en un array que luego usaremos para borrar esas lineas de imc con su codigo)
    			fila_eliminar={};
    			fila_eliminar['k_linea_imc_borrar']=k_linea_imc_borrar;
    			lineasEliminadas.push(fila_eliminar); 
    			$(this).parent().parent().remove();
        		actualizarTotalesVertical();    			
    		}
    		 		
    	}
    });
    
    //EVENTO CLICK AYUDA PROYECTOS
    
    $('#ayuda_proyectos img').on('click', function(event) 
    {    	
    	if($(this).parent().hasClass('interno'))
    	{
    		window.open(BASE_URL+"assets/html/ayuda_interno.html", "", "width=700,height=700, scrollbars=yes");
    	}
    	else if($(this).parent().hasClass('externo'))
    	{
    		window.open(BASE_URL+"assets/html/ayuda_externo.html", "", "width=700,height=700, scrollbars=yes");
    	}
    	else if($(this).parent().hasClass('especial'))
    	{
    		window.open(BASE_URL+"assets/html/ayuda_especial.html", "", "width=700,height=700, scrollbars=yes");
    	}
    	
    	
    	
    	
    	/*
    		var attr_class=$(this).parent().parent().find('td:first-child').attr('class');
    		var k_linea_imc_borrar=attr_class.split(' ')[0];
    		
    		if(attr_class.split(' ')[0]=='nueva')
    		{
    			//borrando fila no grabada(como no estaba grabada,la borramos y nos olvidamos de ella)
    			$(this).parent().parent().remove();
        		actualizarTotalesVertical();   			
    		}
    		else
    		{
    			//borrando fila que estaba grabada(la guardamos en un array que luego usaremos para borrar esas lineas de imc con su codigo)
    			fila_eliminar={};
    			fila_eliminar['k_linea_imc_borrar']=k_linea_imc_borrar;
    			lineasEliminadas.push(fila_eliminar); 
    			$(this).parent().parent().remove();
        		actualizarTotalesVertical();    			
    		}
    		 		
    	*/
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
			    
			    //alert(sum);
			    if(isNaN(sum))
			    {
			    	$('#total'+i).html('##');
			    }
			    else
			    {
			    	$('#total'+i).html(sum);
			    }
			    
			    
		}
		
		//SUMAMOS LOS TOTALES Y LOS PONEMOS EN OTROS DOS CAMPOS
		var sum2 = 0;
	    $('.total_horas_imc').each(function() 
	    {
	        sum2 += Number($(this).html());
	    });
	    
	    if(isNaN(sum2))
	    {
	    	$('#horas_consultor').html('##');
		    $('#horas_totales').html('##');
		    $('#jornadas').html('##');
	    }
	    else
	    {
	    	$('#horas_consultor').html(sum2);
		    $('#horas_totales').html(sum2);
		    $('#jornadas').html(sum2/8);
	    }
	    
	    //PINTAMOS LA CASILLA DE LAS HORAS VERDE O ROJA SEGUN SEAN CORRECTAS O NO
	    if($('#horas_consultor').html()==$('#horas_previstas').html())
	    {
	    	$('#horas_consultor').css('background-color','rgb(119,255,119)');
	    }
	    else
	    {
	    	$('#horas_consultor').css('background-color','rgb(255,119,119)');//COLOR IGUAL AL F77 DE LOS CSS
	    }
	    
	    
	   
}

//ESTA FUNCION ACTUALIZA EL TOTAL DE HORAS DE LA LINEA IMC QUE HA SIDO CAMBIADA
function actualizarTotalesHorizontal(element)
{
	var sum=0;	
	$(element).parent().parent().find('td input.input_horas').each(function() {
        sum += Number($(this).val());
    });
	
	if(isNaN(sum))
    {
		$(element).parent().parent().find('.total_horas_imc').html('##');
    }
    else
    {
    	$(element).parent().parent().find('.total_horas_imc').html(sum);
    }
	
	
}

function crearObjetosParaGrabar()
{
	//CREAMOS UN ARRAY VACIO
	
	//DENTRO DE TODA ESTA FUNCION EN CADA VUELTA CREAMOS UN OBJETO JSON Y LO AÑADIMOS A FILAS
	$('tr.fila-datos').each(function()
	{
		//EN ESTA PARTE SACAMOS LA PRIMERA CLASE DE LA PRIMERA CELDA, QUE SERA NUEVA SI NO EXISTIA ANTES O EL K_IMC SI YA EXISTIA
		var attr_class=$(this).find('td:first-child').attr('class');
		var k_linea_imc=attr_class.split(' ')[0];
		
		//parseInt($("#testid").val(), 10); si nos falla la base de datos
		
		//CREAMOS UNA NUEVA VARIABLE PARA ESTA FILA
		var fila_guardar={};
		
		//EMPEZAMOS A GUARDAR DATOS
		fila_guardar['k_linea_imc']=Number(k_linea_imc);
		fila_guardar['k_imc']=Number($('#k_imc').val());
		fila_guardar['k_proyecto']=Number($(this).attr('id'));
		
		//GUARDAMOS LOS DATOS DE DIAS CON BUCLE
		for(i=1;i<=$('#dias_mes').val();i++)
		{
			
			if(i<10)
			{
				i="0"+i;
			}
			fila_guardar['i_horas_'+i]=Number($(this).find('.dia'+i).find('input').val());
			
		}
	
		fila_guardar['i_tot_horas_linea_imc']=Number($(this).find('.total_horas_imc').html());
		fila_guardar['desc_comentarios']=$(this).find('.comentarios').find('textarea').val();
		
		
		
		//SI FUERA UNA LINEA QUE HEMOS CREADO NOSOTROS LA PONEMOS EN EL ARRAY DE CREADAS, SI NO LA PONEMOS EN EL DE ACTUALIZADAS
		if(k_linea_imc=='nueva')
		{
			lineasCreadas.push(fila_guardar);
		}
		else
		{
			lineasActualizadas.push(fila_guardar);
		}
		
	});
	console.log(lineasCreadas);
	console.log(lineasActualizadas);
	console.log(lineasEliminadas);
}

//verificamos que nos introduzcan un valor correcto en la celda
function validarValorCelda(elemento)
{
	var respuesta=false;
	
	if(Number($(elemento).val())>=0&&Number($(elemento).val())<=24)
	{
		respuesta=true;
	}
	
	return respuesta;
}

//inicializamos el segundo select y lo deshabilitamos
function deshabilitarSelectProyecto()
{
	$('#cod_proyecto_select').html('<option value="0">Selecciona un proyecto</option>');

	$('#cod_proyecto_select').prop('disabled', 'disabled');
}

//pintamos las opciones del select que nos han venido por ajax
function pintarCodigosSelect(respuestaAjax)
{
	//pintamos la pirmera generica
	$('#cod_proyecto_select').html('<option value="0">Selecciona un proyecto</option>');
	//pintamos tantas como opciones vengan por ajax
	for(i=0;i<respuestaAjax.length;i++)
	{
		
		$('#cod_proyecto_select').append($('<option>', {
		    value: respuestaAjax[i].k_proyecto,
		    text: respuestaAjax[i].id_proyecto
		}));
	}
}


function agregar_proyecto_a_tabla()
{	
	
	//CON ESTO SACAMOS EL K_PROYECTO Y EL ID DEL ELEMENTO SELECT
	var tipo_proyecto=$('#tipo_proyecto').val();
	var k_proyecto=$('#cod_proyecto_select').val();
	var id_proyecto=$('#cod_proyecto_select').find('option[value|='+k_proyecto+']').html();
	
	
	//creamos el elemento fila
	var fila;
	if(tipo_proyecto==1)
	{
		fila=$('<tr id="'+k_proyecto+'" class="celda-color fila-datos externo"></tr>');
	}
	if(tipo_proyecto==2)
	{
		fila=$('<tr id="'+k_proyecto+'" class="celda-color fila-datos interno"></tr>');
	}
	if(tipo_proyecto==3)
	{
		fila=$('<tr id="'+k_proyecto+'" class="celda-color fila-datos especial"></tr>');
	}
	
	//al ponerle clase nueva_linea lo tendremos en cuenta a la hora de insertar en la base de datos
	var primeraCelda=$('<td class="nueva color_proy">'+id_proyecto+'</td>');
	
	//Esto inserta la fila antes de la ultima
	fila.append(primeraCelda);		
	
	//Aqui creamos tantas celdas como dias tenga el mes
	for(i=1;i<=$('#dias_mes').val();i++)
	{
		//GUARDAMOS EL VALOR DEL DIA CON UN DIGITO PORQUE LO NECESITAMOS DESPUES
		iCopia=i;
		
		//A LOS DIAS DE UN DIGITO LOS PASAMOS A 2 DIGITOS
		if(i<10)
		{
			i="0"+i;
		}		
		
		
		//SI ENCUENTRA EL VALOR DE i EN UN ARRAY FESTIVOS(MostrarImsMes.PHP) QUE HEMOS GUARDADO LE PONEMOS CLASE FESTIVOS
		if(jQuery.inArray(iCopia,festivos)!=-1)
		{
			var celdaNueva=$('<td class="celda-color dia'+i+' festivo"><input type="text" class="input_horas festivo" value="0"/></td>');
			fila.append(celdaNueva);
		}
		else
		{
			var celdaNueva=$('<td class="celda-color dia'+i+' laborable"><input type="text" class="input_horas laborable" value="0"/></td>');
			fila.append(celdaNueva);
		}	
	}	
	
	//creamos la celda de totales
	var ultimaCelda=$('<td class="celda-color total_horas_imc color_proy">0</td>');
	fila.append(ultimaCelda);
	
	//creamos la celda de comentarios
	var celda_comentarios=$('<td class="comentarios"><textarea></textarea></td>');
	fila.append(celda_comentarios);
	
	//creamos el boton
	var boton=$('<td class="borde_invisible no_fondo"><input class="eliminar_fila " type="image" src="'+BASE_URL+'assets/img/cross.png"/></td>');
	fila.append(boton);
	
	//agregamos la fila
	fila.insertBefore($('#ultima_fila'));	
	
}






