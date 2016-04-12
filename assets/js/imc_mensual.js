
//Nuestra url base en desarrollo
//NOURLBASE BASEURL CAMBIAR
//var baseUrl='http://localhost/appSOL/';
//var baseUrl='';

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
	}
	
	//EVENTO CLICK PARA EL BOTON AGREGAR PROYECTO
	$("#agregar_proyecto").click(function() 
	{
		if($('#cod_proyecto_select').val()==0||$('#tipo_proyecto').val()==0)
		{
			alert("No has seleccionado ningún proyecto");
		}
		else
		{
			//alert(proyectosArray);
			agregar_proyecto_a_tabla();
		}
		
	});
	
	
	
	//TODO lO QUE HAREMOS CUANDO MANDEMOS DATOS
    $("#grabar").click(function(event) 
    {    
    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR    	
    	var itemsCliente = [[1,2],[3,4],[5,6]];
    	var itemsCliente2 = [[11,22],[33,44],[55,66]];
    	//alert(items[0][0]); // 1
    	
    	//NOURLBASE BASEURL CAMBIAR
    	
    	//EN DATA EL PRIMER DATO ES EL NOMBRE EN LADO SERVIDOR DE LA VARIABLE, EL SEGUNDO EN LADO CLIENTE
    	
    	//SUCCESS INDICA LA ACCION A SEGUIR DESPUES DE LA RESPUESTA
    	$.ajax({        
    	       type: "POST",
    	       url: BASE_URL+"general/Imc/mostrar_imc_mes_post",
    	       data: { itemsServidor : itemsCliente,itemsServidor2 : itemsCliente2 },
    	       success: function(respuesta) {
    	            alert(respuesta);        
    	       }
    	    });   		
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
    $('#tabla_imc').delegate(".input_horas", 'blur', function(event) {
    	//alert($(this).parent().parent().find('.total_horas_imc').html());
    	if (validarValorCelda(this))
    	{
    		actualizarTotalesHorizontal(this);
        	actualizarTotalesVertical();
    	}
    	else
    	{
    		alert("Has introducido un valor erroneo en la celda blur");
    		$(this).focus();
    	}
    	
        // ...
    });
    
    $('#tabla_imc').delegate(".eliminar_fila", 'click', function(event) {
    	//alert($(this).parent().parent().html());
    	
    	if (true)
    	{
    		$(this).parent().parent().remove();
    		actualizarTotalesVertical();
    		/*
    		actualizarTotalesHorizontal(this);
        	actualizarTotalesVertical();
        	*/
    	}
    	else
    	{
    		alert("Has introducido un valor erroneo en la celda blur");
    		$(this).focus();
    	}
    	
    	
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

function validarValorCelda(elemento)
{
	var respuesta=false;
	
	if(Number($(elemento).val())>=0&&Number($(elemento).val())<=24)
	{
		respuesta=true;
	}
	
	return respuesta;
}

function deshabilitarSelectProyecto()
{
	$('#cod_proyecto_select').html('<option value="0">Selecciona un proyecto</option>');

	$('#cod_proyecto_select').prop('disabled', 'disabled');
}

function pintarCodigosSelect(respuestaAjax)
{
	$('#cod_proyecto_select').html('<option value="0">Selecciona un proyecto</option>');
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
		fila=$('<tr id="'+k_proyecto+'" class="celda-color externo"></tr>');
	}
	if(tipo_proyecto==2)
	{
		fila=$('<tr id="'+k_proyecto+'" class="celda-color interno"></tr>');
	}
	if(tipo_proyecto==3)
	{
		fila=$('<tr id="'+k_proyecto+'" class="celda-color especial"></tr>');
	}
	
	//al ponerle clase nueva_linea lo tendremos en cuanta a la hora de insertar en la base de datos
	var primeraCelda=$('<td class="nueva color_proy">'+id_proyecto+'</td>');
	//Esto inserta la fila antes de la ultima
	fila.append(primeraCelda);	
	
	for(i=1;i<=$('#dias_mes').val();i++)
	{
		if(i<10)
		{
			i="0"+i;
		}
		var celdaNueva=$('<td class="celda_color dia'+i+'"><input type="text" class="input_horas" value="0"/></td>');
		fila.append(celdaNueva);
	}
	var ultimaCelda=$('<td class="celda_color total_horas_imc color_proy">0</td>');
	fila.append(ultimaCelda);
	
	var celda_comentarios=$('<td class="comentarios"><textarea></textarea></td>');
	fila.append(celda_comentarios);
	
	var boton=$('<td class="borde_invisible no_fondo"><input class="eliminar_fila" type="button" value="Eliminar fila"/></td>');
	fila.append(boton);
		
	fila.insertBefore($('#ultima_fila'));
}



