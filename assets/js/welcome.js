$(document).ready(function() 
	{
		//EVENTO CHANGE PARA EL SELECT DEL USUARIO ACTIVO
		$('#usuarioActivo').on('change',function()
		{
			var nuevo_usuario=$('#usuarioActivo').val();
			
			//VAMOS POR AJAX AL SERVIDOR PARA CAMBIAR EL USUARIO 
			
			$.ajax({        
     	       type: "POST",
     	       url: BASE_URL+"login/cambiar_usuario",
     	       data: { nuevo_usuario : nuevo_usuario},
     	       success: function(respuesta) {
     	            //alert(respuesta+"volvemos  de ajax");    	            
     	            //RECARGAMOS PARA QUE CAMBIE LOS MENUS
     	            location.reload();
     	       }
     	    }); 
		});		
		
		
		//AL CARGAR LA PAGINA COLOCAREMOS COMO SELECCIONADA EN EL SELECT LA OPCION DEL USUARIO LOGUEADO
		$('#usuarioActivo option').each(function(event)
				{
					//alert($(this).attr('value'));
					
					if($(this).attr('value')==$('#k_consultor_activo').val())
					{
						$(this).attr('selected','selected');
					}
				});	
		
	});