
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
	
	//TODO lO QUE HAREMOS CUANDO MANDEMOS DATOS
    $("#grabar").click(function(event) 
    {    
    	//AÑADIMOS EVENTO CLICK AL BOTON GRABAR  
    	
    	//SI LOS DATOS SON INCORRECTOS NO EJECUTAREMOS EL GRABADO
    	var cancelar_envio=false;
    	
    	$('.select_proyecto').each(function()
    	    	{
    	    		if($(this).val()==0)
    	    		{
    	    			cancelar_envio=true;
    	    			alert("Debes seleccionar un proyecto para cada línea.");
    	    		}
    	});
    	
    	$('.select_tipo_gasto').each(function()
    	    	{
    	    		if($(this).val()==0)
    	    		{
    	    			cancelar_envio=true;
    	    			alert("Debes seleccionar un tipo de gasto para cada línea.");
    	    		}
    	});
    	
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
    	
    	
    	$('.fecha_gasto input').each(function()
    	    	{
		    		if (!validarFecha(this))
		        	{
		        		alert("Dia o formato de fecha incorrecto (Formato requerido: yyyy-mm-dd)");
		        		$(this).focus();
		            	//actualizarTotales();
		        	}
    	    	});
    	
    	var error_longitud_comentario=false;
    	
    	$('.descripcion_gasto textarea').each(function()
    	{
    		
    		var nombre_proyecto=$(this).parent().parent().find('td:first').html();
    		
    		
    		
    		if($(this).val().length>100)
    		{
    			error_longitud_comentario=true;
    			cancelar_envio=true;
    			alert("La longitud máxima del campo comentarios es de 100 caracteres");
    		}   
    		
    	    		
    	 });
    	
    	
    	//SI NO HEMOS CANCELADO ENTRAMOS AQUI
    	if(!cancelar_envio)
    	{
    		crearObjetosParaGrabar();        	
        	
        	//EN DATA EL PRIMER DATO ES EL NOMBRE EN LADO SERVIDOR DE LA VARIABLE, EL SEGUNDO EN LADO CLIENTE
        	
        	//SUCCESS INDICA LA ACCION A SEGUIR DESPUES DE LA RESPUESTA
        	
        	$.ajax({        
        	       type: "POST",
        	       url: BASE_URL+"general/Imc/mostrar_iaamc_mes_post",
        	       data: { lineasActualizadas : lineasActualizadas,lineasCreadas : lineasCreadas,lineasEliminadas : lineasEliminadas},
        	       success: function(respuesta) {
        	            alert(respuesta);    	            
        	            location.reload();
        	       }
        	    }); 
    	  }	
    	
    			
      });
	
	
	
	
	$('#tabla_gastos_pendientes_mes').delegate(".valor_gasto input", 'blur', function(event) {
    	
    	if (validarValorCelda(this))
    	{
        	actualizarTotales();
    	}
    	else
    	{
    		alert("Has introducido un valor numérico erroneo (Formato: 1.11)");
    		$(this).focus();
        	actualizarTotales();
        	$(this).focus();
    	}
    	
        // ...
    });
	
	$('#tabla_gastos_pendientes_mes').delegate(".fecha_gasto input", 'blur', function(event) {
    	//alert($(this).parent().parent().find('.total_horas_imc').html());
    	if (!validarFecha(this))
    	{
    		alert("Dia o formato de fecha incorrecto (Formato requerido: yyyy-mm-dd)");
    		$(this).focus();
        	//actualizarTotales();
    	}
    	
    	
        // ...
    });
    
});

//TODO
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
			alert("---");
			lineasActualizadas.push(fila_guardar);
		}
		
	});
	//console.log(lineasCreadas);
	console.log(lineasActualizadas);
	//console.log(lineasEliminadas);
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

function validarFecha(elemento)
{
	var respuesta=false;
	
	//CAMBIAMOS LA FECHA DE FORMATO yyyy-mm-dd A dd-mm-yyyy PARA VALIDARLA
	var cambioFormato=$(elemento).val().split("-");
	
	var fechaNuevoFormato=cambioFormato[2]+"-"+cambioFormato[1]+"-"+cambioFormato[0];
	
	//alert(fechaNuevoFormato);
	
	var regexFecha=/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
	
		
	if(regexFecha.test(fechaNuevoFormato))
	{
		respuesta=true;
	}
	
	return respuesta;
}

//ESTA FUNCION ACTUALIZA LA FILA DE TOTALES
function actualizarTotales()
{		
		//SUMAMOS LOS TOTALES Y LOS PONEMOS EN OTROS DOS CAMPOS
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
	    	$('#pendientes_hoja').html(sum);
	    }	    
	   
}







