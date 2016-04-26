

$(document).ready(function() {
	
	$(function() {
		$("#dialog").css('display','none');
		
		$("#sombra").css('display','none');
		$("#sombra").css('width',$(window).width()+"px");
		$("#sombra").css('height',$(window).height()+"px");	
		
		$('#div_agregar_hoja').on('click',function()
		{
			$("#dialog").css('display','block');
			$("#sombra").css('display','block');
		});
		
		$('#imagen_popup').on('click',function()
		{
			$("#dialog").css('display','none');
			$("#sombra").css('display','none');
		});		
		
	
		$('#boton_selecciones_hoja').on('click',function()
		{
			if(validarMesYear())
			{
				$('#dialog form').submit();
			}
		});	
		});	
		
});

function validarMesYear()
{
	var regexMes=/^([0][1-9]|[1][0-2])$/;
	var valorMes=$('#mes_seleccion').val();
	var respuesta=false;
	if(regexMes.test(valorMes))
	{
		respuesta=true;
	}
	else
	{
		alert("mes no valido, utiliza dos dígitos");
	}
	
	var regexYear=/^([2][0][1][6-9])$/;
	var valorYear=$('#year_seleccion').val();
	var respuesta=false;
	if(regexYear.test(valorYear))
	{
		respuesta=true;
	}
	else
	{
		respuesta=false;
		alert("año no valido, utiliza cuatro dígitos"+valorYear);
	}
	
	
	return respuesta;
}