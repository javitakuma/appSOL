$(document).ready(function() 
	{	
		
		//SI EL USUARIO NO PUEDE CAMBIAR A OTROS USUARIOS QUE NO SEAN EL SUYO EL PONEMOS LA CELDA DESBLOQUEADA
		if($('#usuarioActivo').find('option').length<=1)
		{
			$('#usuarioActivo').attr('disabled','disabled');
		}
		
	
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
					
		
					var particionado=$(this).html().split("*");
					
					var stringFinal=particionado[0];
					
					for(i=0;i<15-particionado[0].length;i++)
					{
						stringFinal+="\u00A0";
					}
					
					stringFinal+=particionado[1];
					
					$(this).html(stringFinal);
			
					
					
					
					
					
					if($(this).attr('value')==$('#k_consultor_activo').val())
					{
						$(this).attr('selected','selected');
					}
				});	
		//$('#usuarioActivoNombre').val($('#usuarioActivo').val());
	});