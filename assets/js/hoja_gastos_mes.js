
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
	actualizarTotales(); 	
	
	//CALENDARIO SELECCION FECHAS
	$('#tabla_gastos_pendientes_mes').delegate('.fecha_gasto input','focus',function()
	{
		$(this).datepicker(
		{			
			firstDay: 1,
			dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			numberOfMonths:1,   //[2,2]  FORMATO CUADRICULA
			dateFormat: "dd-mm-yy",
		}).datepicker("show");
		//alert("click");
	});
	
	
	//TODO lO QUE HAREMOS CUANDO MANDEMOS DATOS
    $("#grabar").click(function(event) 
    {    
    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR  
    	
    	//SI LOS DATOS SON INCORRECTOS NO EJECUTAREMOS EL GRABADO
    	var cancelar_envio=false;
    	
    	//VALIDAMOS QUE HAYA SELECCIONADO UN PROYECTO EN CADA FILA
    	    	
    	
    	//VALIDAMOS QUE HAYA VALORES CORRECTOS EN EL IMPORTE
    	$('.valor_gasto input').each(function()
    	{    		    		
    		if($(this).val()<0)
    		{
    			cancelar_envio=true;
    			alert("Tienes valores negativos en las celdas de valor.");
    			$(this).addClass('colorErrorCelda'); //NEW 20-05
    		}
    		else if(isNaN($(this).val()))
    		{
    			cancelar_envio=true;
    			alert("Tienes valores incorrecto en las celdas de valor.");
    			$(this).addClass('colorErrorCelda'); //NEW 20-05
    		}    		
    	});
    	
    	//VALIDAMOS QUE HAYA SELECCIONADO UNA FECHA CORRECTA EN CADA FILA
    	$('.fecha_gasto input').each(function()
    	    	{
		    		if (!validarFecha(this))
		        	{		
		        		cancelar_envio=true;
		        		$(this).addClass('colorErrorCelda');//NEW20-05
		            	//actualizarTotales();
		        	}
    	    	});
    	
    	//VALIDAMOS QUE HAYA SELECCIONADO UN TIPO GASTO EN CADA FILA
    	
    	
    	if(error_validar_proyecto())
    	{
    		cancelar_envio=true;
    	}
    	
    	/* OLD20-05
    	$('.select_proyecto').each(function()
    	    	{
    	    		if($(this).val()==0)
    	    		{
    	    			cancelar_envio=true;
    	    			alert("Debes seleccionar un proyecto para cada línea.");
    	    			$(this).addClass('colorErrorCelda');//NEW20-05  
    	    		}
    	});
    	*/
    	
    	if(error_validar_tipo_gasto())
    	{
    		cancelar_envio=true;
    	}
    	
    	/*
    	 $('.select_tipo_gasto').each(function()
    	    	{
    	    		if($(this).val()==0)
    	    		{
    	    			cancelar_envio=true;
    	    			alert("Debes seleccionar un tipo de gasto para cada línea.");
    	    			$(this).addClass('colorErrorCelda');//NEW20-05    	    			
    	    		}
    	});    	 
    	 */    	
    	
    	if(error_validar_comentario_linea())
    	{
    		cancelar_envio=true;
    	}
    	/*
    	var error_longitud_comentario=false;
    	
    	//VALIDAMOS EL TAMAÑO DEL CAMPO DESCRIPCION
    	$('.descripcion_gasto textarea').each(function()
    	{    		
    		//var nombre_proyecto=$(this).parent().parent().find('td:first').html();          CAMBIADO    				
    		
    		if($(this).val().length>100)
    		{
    			error_longitud_comentario=true;
    			cancelar_envio=true;
    			alert("La longitud máxima del campo comentarios es de 100 caracteres.");
    		}  
    		
    		if($(this).val()=='')
    		{
    			error_longitud_comentario=true;
    			cancelar_envio=true;
    			alert("Debe introducir una descripción para cada línea de gasto.");
    		} 
    		
    	    		
    	 });
    	*/
    	
    	//SI NO HEMOS CANCELADO ENTRAMOS AQUI
    	if(!cancelar_envio)
    	{
    		crearObjetosParaGrabar();   
    		
    		var k_hoja_gastos=($('#k_hoja_gastos').val());
        	
        	//EN DATA EL PRIMER DATO ES EL NOMBRE EN LADO SERVIDOR DE LA VARIABLE, EL SEGUNDO EN LADO CLIENTE
        	
        	//SUCCESS INDICA LA ACCION A SEGUIR DESPUES DE LA RESPUESTA
        	
        	$.ajax({        
        	       type: "POST",
        	       url: BASE_URL+"general/Gastos/grabar_gastos_mes",
        	       data: { lineasActualizadas : lineasActualizadas,lineasCreadas : lineasCreadas,lineasEliminadas : lineasEliminadas,k_hoja_gastos:k_hoja_gastos},
        	       success: function(respuesta) {
        	            alert(respuesta);    	            
        	            location.reload();
        	       }
        	    }); 
    	  }	  
      });
    
    
    
    //igual validacion que grabar pero vamos a otra funcion del servidor
    $("#enviar_gastos").click(function(event) 
    	    {    
    	    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR  
    	    	
    	    	//SI LOS DATOS SON INCORRECTOS NO EJECUTAREMOS EL GRABADO
    	    	var cancelar_envio=false;
    	    	
    	    	//VALIDAMOS QUE HAYA SELECCIONADO UN PROYECTO EN CADA FILA
    	    	
    	    	
    	    	
    	    	
    	    	//VALIDAMOS QUE HAYA VALORES CORRECTOS EN EL IMPORTE
    	    	$('.valor_gasto input').each(function()
    	    	{    		    		
    	    		if($(this).val()<0)
    	    		{
    	    			cancelar_envio=true;
    	    			alert("Tienes valores negativos en las celdas de valor.");
    	    		}
    	    		else if(isNaN($(this).val()))
    	    		{
    	    			cancelar_envio=true;
    	    			alert("Tienes valores incorrecto en las celdas de valor.");
    	    		}
    	    	});
    	    	
    	    	//VALIDAMOS QUE HAYA SELECCIONADO UNA FECHA CORRECTA EN CADA FILA
    	    	$('.fecha_gasto input').each(function()
    	    	    	{
    			    		if (!validarFecha(this))
    			        	{		        		
    			        		
    			        		cancelar_envio=true;
    			            	//actualizarTotales();
    			        	}
    	    	    	});
    	    	
    	    	if(error_validar_proyecto())
    	    	{
    	    		cancelar_envio=true;
    	    	}
    	    	
    	    	//VALIDAMOS QUE HAYA SELECCIONADO UN TIPO GASTO EN CADA FILA
    	    	if(error_validar_tipo_gasto())
    	    	{
    	    		cancelar_envio=true;
    	    	}
    	    	
    	    	var error_longitud_comentario=false;
    	    	
    	    	//VALIDAMOS EL TAMAÑO DEL CAMPO DESCRIPCION
    	    	$('.descripcion_gasto textarea').each(function()
    	    	{    		
    	    		//var nombre_proyecto=$(this).parent().parent().find('td:first').html();          CAMBIADO    				
    	    		
    	    		if($(this).val().length>100)
    	    		{
    	    			error_longitud_comentario=true;
    	    			cancelar_envio=true;
    	    			alert("La longitud máxima del campo comentarios es de 100 caracteres");
    	    		}  
    	    		
    	    		if($(this).val()=='')
    	    		{
    	    			error_longitud_comentario=true;
    	    			cancelar_envio=true;
    	    			alert("Debe introducir una descripción para cada línea de gasto.");
    	    		}
    	    		
    	    	    		
    	    	 });
    	    	
    	    	
    	    	//SI NO HEMOS CANCELADO ENTRAMOS AQUI
    	    	if(!cancelar_envio)
    	    	{
    	    		crearObjetosParaGrabar();   
    	    		
    	    		var k_hoja_gastos=($('#k_hoja_gastos').val());
    	        	
    	        	//EN DATA EL PRIMER DATO ES EL NOMBRE EN LADO SERVIDOR DE LA VARIABLE, EL SEGUNDO EN LADO CLIENTE
    	        	
    	        	//SUCCESS INDICA LA ACCION A SEGUIR DESPUES DE LA RESPUESTA
    	        	
    	        	$.ajax({        
    	        	       type: "POST",
    	        	       url: BASE_URL+"general/Gastos/enviar_hoja_gastos_mes",
    	        	       data: { lineasActualizadas : lineasActualizadas,lineasCreadas : lineasCreadas,lineasEliminadas : lineasEliminadas,k_hoja_gastos:k_hoja_gastos},
    	        	       success: function(respuesta) {
    	        	            alert(respuesta);    	            
    	        	            location.reload();
    	        	       }
    	        	    }); 
    	    	  }	  
    	      });
    
    
    
    
    $('#tabla_gastos_pendientes_mes').delegate(".select_proyecto", 'focus', function(event) 
    {    	
    	$(this).removeClass('colorErrorCelda');
    	$(this).parent().removeClass('colorErrorCelda');
    	
    });
    
    $('#tabla_gastos_pendientes_mes').delegate(".select_proyecto", 'blur', function(event) 
    {    	
    	error_validar_proyecto();
    	
    });
    
    $('#tabla_gastos_pendientes_mes').delegate(".select_tipo_gasto", 'focus', function(event) 
    {    	
    	$(this).removeClass('colorErrorCelda');
    	$(this).parent().removeClass('colorErrorCelda');
    	
    });
    
    $('#tabla_gastos_pendientes_mes').delegate(".select_tipo_gasto", 'blur', function(event) 
    {    	
    	error_validar_tipo_gasto()
    	
    });  
    
    $('#tabla_gastos_pendientes_mes').delegate(".fecha_gasto input", 'focus', function(event) 
    {    	
    	$(this).removeClass('colorErrorCelda');
    	$(this).parent().removeClass('colorErrorCelda');
    	
    });
    
    $('#tabla_gastos_pendientes_mes').delegate(".fecha_gasto input", 'change', function(event) 
    {     
    	validarFecha(this);
    });
    
    /*
    $('#tabla_gastos_pendientes_mes').delegate(".descripcion_gasto textarea", 'focus', function(event) 
    {    	
    	$(this).removeClass('colorErrorCelda');
    	$(this).parent().removeClass('colorErrorCelda');
    	
    });
    */
    $('#tabla_gastos_pendientes_mes').delegate(".descripcion_gasto textarea", 'focus', function(event) 
    {    	
    	$(this).removeClass('colorErrorCelda');  
    	$(this).parent().removeClass('colorErrorCelda');
    });
    
    $('#tabla_gastos_pendientes_mes').delegate(".descripcion_gasto textarea", 'keypress', function(event) 
    {    	
    	$(this).removeClass('colorErrorCelda');  
    	$(this).parent().removeClass('colorErrorCelda');
    });
    
    $('#tabla_gastos_pendientes_mes').delegate(".descripcion_gasto textarea", 'blur', function(event) 
    {    	
    	error_validar_comentario_linea();    	
    });
    
    
    $('#tabla_gastos_pendientes_mes').delegate(".valor_gasto input", 'focus', function(event) 
    {    	
    	//validarFecha($(this).parent().prev().find('input'));
    });
       
	
	//EVENTO BLUR PARA LAS CASILLAS DE GASTO PARA VALIDAR
	$('#tabla_gastos_pendientes_mes').delegate(".valor_gasto input", 'blur', function(event) {
    	
    	if (validarValorCelda(this))
    	{
        	actualizarTotales();
        	$(this).removeClass('colorErrorCelda'); //NEW 20-05
        	$(this).parent().removeClass('colorErrorCelda');
    	}
    	else
    	{
    		alert("Has introducido un valor numérico erroneo (Formato: 1.11)");
    		//$(this).focus();
        	actualizarTotales();
        	//$(this).focus();
        	$(this).addClass('colorErrorCelda'); //NEW 20-05
        	$(this).parent().addClass('colorErrorCelda');
    	}
    	
    });
	
	//EVENTO BLUR PARA LAS CASILLAS DE GASTO PARA VALIDAR
	$('#tabla_gastos_pendientes_mes').delegate(".valor_gasto input", 'click', function(event) {
    	
		$(this).removeClass('colorErrorCelda'); //NEW 20-05 
		$(this).parent().removeClass('colorErrorCelda');
    });
	
	//EVENTO BLUR PARA LAS CASILLAS DE LA FECHA PARA VALIDARLA
	/*
	$('#tabla_gastos_pendientes_mes').delegate(".fecha_gasto input", 'blur', function(event) {
    	if (!validarFecha(this))
    	{
    		//alert("Dia o formato de fecha incorrecto (Formato requerido: dd/mm/yyyy)");
    		$(this).focus();
        	//actualizarTotales();
    	}
    	
    	
        // ...
    });
	*/
	
	//EVENTO PARA LA ACCION CLICK DEL BOTON AGREGAR LINEA
	$("#div_agregar_fila").click(function() 
			{		
				//PONEMOS LA PROPIEDAD DISPLAY A BLOCK POR SI LA HEMOS QUITADO
				$('#tabla_gastos_pendientes_mes').css('display','block');
				$('#parrafo_sin_gastos').css('display','none');
				agregar_linea();				
			});
	
	
	//EVENTO PARA LA ACCION CLICK DE LOS BOTONES ELIMINAR
    $('#tabla_gastos_pendientes_mes').delegate(".eliminar_fila", 'click', function(event) 
    {  
    	
    	if (confirm("¿Seguro que desea eliminar esa fila?\nNo se guardaran los cambios hasta que no presiones el botón Grabar datos."))
    	{   
    		//var attr_class=$(this).parent().parent().find('td:first-child').attr('class');
    		var attr_class=$(this).parent().parent().attr('class');
    		var k_linea_gasto_borrar=attr_class.split(' ')[0];
    		
    		if(attr_class.split(' ')[0]=='nueva')
    		{
    			//borrando fila no grabada(como no estaba grabada,la borramos y nos olvidamos de ella)
    			$(this).parent().parent().remove();
    			actualizarTotales();   			
    		}
    		else
    		{
    			//borrando fila que estaba grabada(la guardamos en un array que luego usaremos para borrar esas lineas de imc con su codigo)
    			fila_eliminar={};
    			fila_eliminar['k_linea_gasto_borrar']=k_linea_gasto_borrar;
    			lineasEliminadas.push(fila_eliminar); 
    			$(this).parent().parent().remove();
    			actualizarTotales();    			
    		}
    		 		
    	}
    	
    	
    	
    });
    
});

function error_validar_proyecto()
{
	var error_validar=false;
	
	$('.select_proyecto').each(function()
	{
		if($(this).val()==0)
		{
			error_validar=true;
			alert("Debes seleccionar un proyecto para cada línea.");
			$(this).addClass('colorErrorCelda');//NEW20-05  
			$(this).parent().addClass('colorErrorCelda');//NEW20-05  
		}
	});
	
	return error_validar;
}

function error_validar_tipo_gasto()
{
	var error_validar=false;
	
	$('.select_tipo_gasto').each(function()
	{
		if($(this).val()==0)
		{
			error_validar=true;
			alert("Debes seleccionar un tipo de gasto para cada línea.");
			$(this).addClass('colorErrorCelda');//NEW20-05 
			$(this).parent().addClass('colorErrorCelda');//NEW20-05
		}
	});
	return error_validar;
}

//VALIDACION FECHA
function validarFecha(elemento)
{
	
	var respuesta=false;
	
	//CAMBIAMOS LA FECHA DE FORMATO yyyy-mm-dd A dd-mm-yyyy PARA VALIDARLA (input date cambia formato)
	//var cambioFormato=$(elemento).val().split("-");
	
	//var fechaNuevoFormato=cambioFormato[2]+"-"+cambioFormato[1]+"-"+cambioFormato[0];
	
	var fechaNuevoFormato=$(elemento).val();   //para formato dd-mm-yyyy
	
	//alert(fechaNuevoFormato);
	
	
	//VALIDA LA FECHA EN FORMATO DD-MM-YYYY
	var regexFecha=/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
	
	//COMPROBAMOS EL FORMATO	
	if(regexFecha.test(fechaNuevoFormato))
	{
		respuesta=true;
	}
	else
	{
		alert("Dia o formato de fecha incorrecto (Formato requerido: dd/mm/yyyy)");
	}
	
	var mes_gasto=fechaNuevoFormato.split("-")[1];
	
	var year_gasto=fechaNuevoFormato.split("-")[2];
	
	var number_mes_hoja=(Number)($('#mes_hoja').val());
	
	var number_año_hoja=(Number)($('#año_hoja').val());
	
	//CONTINUAR HACIA ABAJO
	
	//AQUI COMPROBAMOS QUE EL MES Y AÑO SEAN LOS DE LA HOJA DE GASTOS
	if(fechaNuevoFormato.split("-")[2]==$('#año_hoja').val() && fechaNuevoFormato.split("-")[1]==$('#mes_hoja').val())
	{	
		//CORRECTO		
	}  //MES ANTERIOR MISMO AÑO 
	else if(fechaNuevoFormato.split("-")[2]==$('#año_hoja').val() && fechaNuevoFormato.split("-")[1]==number_mes_hoja-1)
	{
		//CORRECTO
	}// 2 MESES ANTERIOR MISMO AÑO
	else if(fechaNuevoFormato.split("-")[2]==$('#año_hoja').val() && fechaNuevoFormato.split("-")[1]==number_mes_hoja-2)
	{
		//CORRECTO
	}//MES ANTERIOR AÑO ANTERIOR(GASTOS DICIEMBRE EN HOJA ENERO) 
	else if(fechaNuevoFormato.split("-")[2]==number_año_hoja-1 && fechaNuevoFormato.split("-")[1]==number_mes_hoja+11)
	{
		//CORRECTO
	}//MES 2 ANTERIOR AÑO ANTERIOR(GASTOS DICIEMBRE EN HOJA FEBRERO O DE NOVIEMNBRE EN ENERO) 
	else if(fechaNuevoFormato.split("-")[2]==number_año_hoja-1 && fechaNuevoFormato.split("-")[1]==number_mes_hoja+10)
	{
		//CORRECTO
	}
	else
	{			
		respuesta=false;
		alert("Solo se admiten gastos para el mes en curso de la hoja("+($('#mes_hoja').val())+"/"+($('#año_hoja').val())+") y los dos meses anteriores.");
		$(elemento).addClass('colorErrorCelda');
		$(elemento).parent().addClass('colorErrorCelda');
	}
	
	
	//AQUI COMPROBAMOS QUE EL MES Y AÑO SEAN LOS DE LA HOJA DE GASTOS(PARA VERSION YYYY-MM-DD
	/*)
	if(cambioFormato[0]!=$('#año_hoja').val()||cambioFormato[1]!=$('#mes_hoja').val())
	{
		respuesta=false;
		alert("Solo se admiten gastos para el mes en curso de la hoja("+($('#mes_hoja').val())+"/"+($('#año_hoja').val())+")");
	}
	*/
	return respuesta;
}

function error_validar_comentario_linea()
{	
	//VALIDAMOS EL TAMAÑO DEL CAMPO DESCRIPCION
	
	var error_validar=false;
	$('.descripcion_gasto textarea').each(function()
	{    		
		//var nombre_proyecto=$(this).parent().parent().find('td:first').html();          CAMBIADO    				
		
		if($(this).val().length>100)
		{
			error_validar=true;
			alert("La longitud máxima del campo comentarios es de 100 caracteres.");
			$(this).addClass('colorErrorCelda');//NEW20-05
			$(this).parent().addClass('colorErrorCelda');//NEW20-05 
		} 		
		if($(this).val()=='')
		{
			error_validar=true;
			alert("Debe introducir una descripción para cada línea de gasto.");
			$(this).addClass('colorErrorCelda');//NEW20-05 
			$(this).parent().addClass('colorErrorCelda');//NEW20-05 
		} 	    		
	 });
	
	return error_validar;
}

function confirmar_boton_volver()
{
	var respuesta_volver=confirm("¿Seguro que deseas volver? Asegurate de salvar tus cambios si así lo deseas.");
	
	if(respuesta_volver)
	{
		onclick=location.href=BASE_URL+"general/Gastos";
	}	
}

function pintar()
{
	$('.fecha_gasto input').each(function()
	{
		alert($(this).val());
	});
}


//TODO
function agregar_linea()
{	
	
	
	//creamos el elemento fila
	var fila;
	//al ponerle clase nueva_linea lo tendremos en cuenta a la hora de insertar en la base de datos
	fila=$('<tr id="nuevo" class="nueva celda-color fila-datos"></tr>');
		
	
	//Creamos la primera celda con el select y la agregamos a la fila
	var selectProyecto='<td class="nueva"><select class="select_proyecto"><option value="0">Elige una opción</option>';
	
	//CREAMOS UNA OPCION POR CADA PROYECTO ASIGNADO AL CONSULTOR QUE LO TENEMOS EN UN ARRAY EN JSON
	for(i=0;i<proyectos_consultor.length;i++)
	{
		selectProyecto+='<option value="'+proyectos_consultor[i].k_proyecto+'">'+proyectos_consultor[i].id_proyecto+'</option>'
	}
	//CERRAMOS EL SELECT
	selectProyecto+="</select></td>";			
	
	//Esto inserta la celda en la fila
	fila.append(selectProyecto);		
	
	
	//Creamos la segunda celda con el select y la agregamos a la fila
	var selectTipoGasto='<td class="tipo_gasto"><select class="select_tipo_gasto"><option value="0">Elige una opción</option>';
	
	//CREAMOS UNA OPCION POR CADA PROYECTO ASIGNADO AL CONSULTOR QUE LO TENEMOS EN UN ARRAY EN JSON
	for(i=0;i<tipos_gasto.length;i++)
	{
		selectTipoGasto+='<option value="'+tipos_gasto[i].k_tipo_linea_gasto+'">'+tipos_gasto[i].nom_tipo_linea_gasto+'</option>'
	}
	
	//CERRAMOS EL SELECT
	selectTipoGasto+="</select></td>";
	
	//Esto inserta la celda en la fila
	fila.append(selectTipoGasto);
	
	
	//CREAMOS LAS OTRAS 3 CASILLAS Y EL BOTON ELIMINAR Y LOS AGREGAMOS
	
	var fechaGasto=$('<td class="fecha_gasto"><input class="input_datos" type="text" placeholder="dd-mm-yyyy" value=""/></td>');
	
	var valorGasto=$('<td class="valor_gasto"><input class="input_datos" type="text" value="0.00"/></td>');	
		
	var descripcionGasto=$('<td class="descripcion_gasto"><textarea maxlength="100"></textarea></td>');
	
	var botonEliminar=$('<td class="borde_invisible no_fondo"><img title="Eliminar fila" class="eliminar_fila " src="'+BASE_URL+'assets/img/cross.png"/></td>');
 
	fila.append(fechaGasto);
	fila.append(valorGasto);
	fila.append(descripcionGasto);
	fila.append(botonEliminar);
           
		
	//agregamos la fila
	fila.appendTo($('#tabla_gastos_pendientes_mes'));	
	
}


function crearObjetosParaGrabar()
{
	//CREAMOS UN ARRAY VACIO
	
	//DENTRO DE TODA ESTA FUNCION EN CADA VUELTA CREAMOS UN OBJETO JSON Y LO AÑADIMOS A FILAS
	$('#tabla_gastos_pendientes_mes tr.fila-datos').each(function()
	{
		//EN ESTA PARTE SACAMOS LA PRIMERA CLASE DE LA PRIMERA CELDA, QUE SERA NUEVA SI NO EXISTIA ANTES O EL K_IMC SI YA EXISTIA
		var attr_class=$(this).find('td:first-child').attr('class');
		
		var attr_id=$(this).attr('id');
		var k_linea_imc=attr_class.split(' ')[0];
		
		
		
		//CREAMOS UNA NUEVA VARIABLE PARA ESTA FILA
		var fila_guardar={};
		
		//EMPEZAMOS A GUARDAR DATOS
		fila_guardar['k_linea_gasto']=Number(attr_id);
		fila_guardar['k_hoja_gasto']=Number($('#k_hoja_gastos').val());
		fila_guardar['k_proyecto']=Number($(this).find('.select_proyecto').val());
		fila_guardar['k_tipo_linea_gasto']=Number($(this).find('.select_tipo_gasto').val());
		fila_guardar['f_linea_gasto']=$(this).find('.fecha_gasto input').val();
		fila_guardar['i_imp_linea_gasto']=Number($(this).find('.valor_gasto input').val());
		fila_guardar['desc_linea_gasto']=$(this).find('.descripcion_gasto textarea').val();	
		
		
		
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
	
	/*		
	console.log(lineasCreadas);
	console.log(lineasActualizadas);
	console.log(lineasEliminadas);
	*/
}

//VALIDAMOS EL VALOR EN EUROS DE LA CELDA VALOR
function validarValorCelda(elemento)
{
	var respuesta=false;
	
	if(Number($(elemento).val())>=0)
	{
		respuesta=true;
	}
	
	//EXPRESION REGULAR PARA CERTIFICAR QUE PONGA EL VALOR EN NUMERO ENTERO O DECIMAL CON UN DECIMAL
	var regexGastoUnDecimal=/^[0-9]+(.[0-9]{1})+$/;
	
	if(regexGastoUnDecimal.test($(elemento).val()))
	{
		//FORMATO VALIDO
		//AÑADIMOS UN CERO PARA CONVERTIR A DOS CIFRAS DECIMALES
		var valor=$(elemento).val();
		$(elemento).val(valor+"0");		
	}
	else
	{
		//respuesta=false;
		//EXPRESION REGULAR PARA CERTIFICAR QUE PONGA EL VALOR EN NUMERO ENTERO O DECIMAL CON DOS DECIMALES
		var regexGasto=/^[0-9]+(.[0-9]{2})?$/;
		
		if(regexGasto.test($(elemento).val()))
		{
			//FORMATO VALIDO
		}
		else
		{
			respuesta=false;
		}	
		
	}		
	
	
	return respuesta;
}



//ESTA FUNCION ACTUALIZA LA FILA DE TOTALES Y VALORA SI EXISTEN LINEAS DE GASTO O NO PARA PINTAR LA TABLA O LA ADVERTENCIA DE QUE NO EXISTEN
function actualizarTotales()
{		
		//SUMAMOS LOS TOTALES Y LOS PONEMOS EN EL CAMPO DE PENDIENTES
		var sum = 0;
	    $('.valor_gasto input').each(function() 
	    {
	        sum += Number($(this).val());
	    });
	    
	    
	    if(isNaN(sum))
	    {
	    	$('#pendientes_hoja').html('##');
	    }
	    else
	    {
	    	//ASI LO PONEMOS EN FORMATO CON DOS DECIMALES
	    	$('#pendientes_hoja').html(parseFloat(Math.round(sum * 100) / 100).toFixed(2)+"€");
	    }	
	    	    
	    var numeroFilas=$('.fila-datos').length;
	    
	    //SI NO EXISTEN FILAS MOSTRAMOS UN PARRAFO QUE LO INDICA Y OCULTAMOS LA TABLAS
	    if(numeroFilas==0)
	    {
	    	$('#parrafo_sin_gastos').css('display','block');
	    	$('#tabla_gastos_pendientes_mes').css('display','none');
	    }
	    else//SI EXISTEN FILAS MOSTRAMOS LA TABLA
	    {
	    	$('#tabla_gastos_pendientes_mes').css('display','block');
	    	$('#parrafo_sin_gastos').css('display','none');
	    }
	    
	   
}







