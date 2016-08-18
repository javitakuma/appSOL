
$(document).ready(function() 
{		
	$("#dialog").css('display','none');
	
	$("#sombra").css('display','none');			
	
	//EVENTO CLICK
	$('#ayuda_permisos').on('click',function()
	{
		//LE DAMOS EL TAMAÑO DE LA PANTALLA A LA SOMBRA
		$("#sombra").css('width',$(document).width()+"px");
		$("#sombra").css('height',$(document).height()+"px");
		
		
		//LOS HACEMOS VISIBLES
		$("#dialog").css('display','block');
		$("#sombra").css('display','block');
	});	
	
	//LOS CERRAMOS Y HACEMOS INVISIBLES
	$('#imagen_cierre_popup').on('click',function()
	{
		$("#dialog").css('display','none');
		$("#sombra").css('display','none');
	});
	
	$('#sombra').on('click',function()
	{
    		$("#dialog").css('display','none');
    		$("#sombra").css('display','none');		
	});	
	
	$( window ).resize(function() {
		$("#sombra").css('width',$(document).width()+"px");
		$("#sombra").css('height',$(document).height()+"px");
	});
	
	//FINAL EVENTOS POP UPS
	
	//EVENTO BOTON NUEVA SOLICITUD
	$('#seleccionar_dias').on('click',function()
	{
		var cancelar_accion=false;
		
		if($('#tipo_solicitud').val()==0)
		{
			cancelar_accion=true;			
			alert("Selecciona un tipo de solicitud");			
		}
		
		if($('#responsable_solicitud').val()==0 && $('#tipo_solicitud').val()!=468)
		{
			cancelar_accion=true;			
			alert("Selecciona un responsable");			
		}		
		
		//COMPROBAMOS QUE INTRODUZCA HORAS SOLO SI ES KEYVACACIONES
		if($('#tipo_solicitud').val()==450)
		{
			if($('#pendientesDebidosMostrar').html()==0&&$('#pendientesMostrar').html()==0&&$('#pendientesFuturoMostrar').html()==0)
			{
				cancelar_accion=true;
				alert("No dispones de días de vacaciones");
			}
			else if(isNaN($('#horas_jornada').val()) || $('#horas_jornada').val()>10 || $('#horas_jornada').val()<=0)
			{
				cancelar_accion=true;
				alert("Selecciona un número de horas correcto (Formato: 6.5).");
			}
			
		}
		
		
		
		
		//SI NO HEMOS CANCELADO ENTRAMOS A LA SIGUIENTE PANTALLA
		if(!cancelar_accion)
		{
			//location.href=BASE_URL+"general/Permisos/solicitar_permiso";
			$('#responsable_solicitud').prop('disabled',false);
			$('#form_nueva_solicitud').submit();
		}
	});
	
	
	$('#tipo_solicitud').on('change',function()
	{
		//SI ES KEYVACACIONES HABILITAMOS LA CASILLA DE HORAS
		if($(this).val()==450)
		{
			$('#horas_jornada').prop('disabled',false);
			$('#responsable_solicitud').prop('disabled',false);
			$('#responsable_solicitud').prop('value',0);
		}
		//SI ES KEYOTROS DESHABILITAMOS LA CASILLA DE HORAS
		if($(this).val()==468)
		{
			$('#horas_jornada').prop('disabled',true);
			$('#horas_jornada').prop('value',null);
			$('#responsable_solicitud').prop('disabled',true);
			//$('#responsable_solicitud').prop('value',50);
			
			var k_proyecto=$(this).val();
			$.ajax({        
     	       type: "POST",
     	       url: BASE_URL+"general/Permisos/get_resp_aut_rrhh",
     	       data: { k_proyecto : k_proyecto},
     	       success: function(respuesta) {
     	    	  $('#responsable_solicitud').prop('value',respuesta);
     	    	   //alert(respuesta);     	    	  
     	       }
     	    })
		}
		if($(this).val()==0)
		{
			$('#responsable_solicitud').prop('disabled',false);
			$('#responsable_solicitud').prop('value',0);
			$('#horas_jornada').prop('disabled',false);
			$('#horas_jornada').prop('value',null);
		}
	});	
	
	
	
	$('.eliminar_fila_img').on('click',function()
			{
				var k_permiso_solic=$(this).parent().parent().attr('id');
				
				var aceptado=confirm("Deseas eliminar esa petición de vacaciones");
				
				if(aceptado)
				{
					$('#enviando').css('display','block');
					$.ajax({        
		        	       type: "POST",
		        	       url: BASE_URL+"general/Permisos/eliminar_solicitud",
		        	       data: { k_permiso_solic : k_permiso_solic},
		        	       success: function(respuesta) {
		        	    	   
		        	    	   $('#enviando').css('display','none');
		        	    	   //alert("Su solicitud ha sido grabada");
		        	    	   
		        	    	   //LE MANDAMOS AL MENU PRINCIPAL PORQUE SI NO FALLA, NO SE PUEDE ACTUALIZAR EL k_permisos_solic
		        	           location.reload();
		        	       }
		        	    })
				}	        	    
			});
	
	/*
	//PONEMOS LOS VALORES DE DIAS PENDIENTES
	$('#pendientesDebidosMostrar').html($('#diasPendientesDebidos').val());
	$('#pendientesMostrar').html($('#diasPendientes').val());
	*/
});

function editar_solicitud(k_permisos_solic)
{
	location.href=BASE_URL+"general/Permisos/editar_solicitud/"+k_permisos_solic;	
}
