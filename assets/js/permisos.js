
$(document).ready(function() 
{	
	
	$('#seleccionar_dias').on('click',function()
	{
		var cancelar_accion=false;
		
		if($('#responsable_solicitud').val()==0||$('#tipo_solicitud').val()==0)
		{
			//cancelar_accion=false;
			
			if(isNan($('#horas_jornada').val()) || $('#horas_jornada').val()>10 || $('#horas_jornada').val()<=0)
			{
				cancelar_accion=false;
			}
			alert("Selecciona");
			
		}
		
		
		if(!cancelar_envio)
		{
			location.href=BASE_URL+"general/Permisos/solicitar_permiso";
		}
	});
	
});
