
$(document).ready(function() 
{	
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
