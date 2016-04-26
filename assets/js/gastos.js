

$(document).ready(function() {
	
	$(function() {
		
		//PONEMOS LA VENTANA EMERGENTE Y EL FONDO OSCURO OCULTOS POR DEFECTO
		
		$("#dialog").css('display','none');
		
		$("#sombra").css('display','none');
		
			
		
		$('#div_agregar_hoja').on('click',function()
		{
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
		
	
		$('#boton_selecciones_hoja').on('click',function()
		{
			if(validarMesYear())
			{	
				//SI NOS PONE EL MES CON UN NUMERO LOS CAMBIAMOS A FORMATO DE DOS NUMEROS
				if($('#mes_seleccion').val().length==1)
				{
					var digito=$('#mes_seleccion').val();
					$('#mes_seleccion').val("0"+digito);
				}
				
				$('#dialog form').submit();
			}
		});	
		});	
		
});

function validarMesYear()
{
	var regexMes=/^([1-9]|[0][1-9]|[1][0-2])$/;
	var valorMes=$('#mes_seleccion').val();
	var respuesta=false;
	if(regexMes.test(valorMes))
	{
		respuesta=true;
	}
	else
	{
		alert("mes no valido");
	}
	
	var regexYear=/^([2][0][1][6-9])$/;
	var valorYear=$('#year_seleccion').val();
	
	//VOLVEMOS A PONER A FALSE PARA VALIDAR EL AÑO
	if(regexYear.test(valorYear))
	{
		//TRAMPA PARA QUE NO NOS PONGA A TRUE SI ESTA FALSEADA
		if(respuesta==true)
		{
			respuesta=true;
		}					
	}
	else
	{
		respuesta=false;
		alert("año no valido");
	}	
	return respuesta;
}