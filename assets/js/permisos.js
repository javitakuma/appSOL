
$(document).ready(function() 
{	
	$("#dialog").css('display','none');
	
	$("#sombra").css('display','none');			
	
	$('#ayuda_permisos').on('click',function()
	{
		//LE DAMOS EL TAMAÑO DE LA PANTALLA A LA SOMBRA
		$("#sombra").css('width',$(window).width()+"px");
		$("#sombra").css('height',$(window).height()+"px");
		
		//LE DAMOS EL TAMAÑO DE LA PANTALLA A LA SOMBRA
		$("#sombra").css('width',$(window).width()+"px");
		$("#sombra").css('height',$(window).height()+"px");
		
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
	
	
	//EVENTO BOTON NUEVA SOLICITUD
	$('#seleccionar_dias').on('click',function()
	{
		var cancelar_accion=false;
		
		if($('#tipo_solicitud').val()==0)
		{
			cancelar_accion=true;			
			alert("Selecciona un tipo de solicitud");			
		}
		
		if($('#responsable_solicitud').val()==0)
		{
			cancelar_accion=true;			
			alert("Selecciona un responsable");			
		}		
		
		//COMPROBAMOS QUE INTRODUZCA HORAS SOLO SI ES KEYVACACIONES
		if($('#tipo_solicitud').val()==450)
		{
			if(isNaN($('#horas_jornada').val()) || $('#horas_jornada').val()>10 || $('#horas_jornada').val()<=0)
			{
				cancelar_accion=true;
				alert("Selecciona un número de horas correcto (Formato: 6.5).");
			}
		}
		
		//SI NO HEMOS CANCELADO ENTRAMOS A LA SIGUIENTE PANTALLA
		if(!cancelar_accion)
		{
			//location.href=BASE_URL+"general/Permisos/solicitar_permiso";
			$('#form_nueva_solicitud').submit();
		}
	});
	
	
	$('#tipo_solicitud').on('change',function()
	{
		//SI ES KEYVACACIONES HABILITAMOS LA CASILLA DE HORAS
		if($(this).val()==450)
		{
			$('#horas_jornada').prop('disabled',false);
		}
		//SI ES KEYOTROS DESHABILITAMOS LA CASILLA DE HORAS
		if($(this).val()==468)
		{
			$('#horas_jornada').prop('disabled',true);
		}
	});
	
	$('.eliminar_fila_img').on('click',function()
			{
				var k_permiso_solic=$(this).parent().parent().attr('id');
				
				var aceptado=confirm("Deseas eliminar esa petición de vacaciones");
				
				if(aceptado)
				{
					$.ajax({        
		        	       type: "POST",
		        	       url: BASE_URL+"general/Permisos/eliminar_solicitud",
		        	       data: { k_permiso_solic : k_permiso_solic},
		        	       success: function(respuesta) {
		        	    	   
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
