

$(document).ready(function() {
	
var condicion=$('#condicion').val();
	
	if(condicion==0)
	{
		$('#pagadasGastos').css('font-weight','bold');
		$('#pagadasGastos').css('color','black');
		$('#pagadasGastos').css('background-color','#0f7c77');
		//background: rgb(15,124,119)==#0f7c77; 
	}
	if(condicion==2)
	{
		$('#noPagadasGastos').css('font-weight','bold');
		$('#noPagadasGastos').css('color','black');
		$('#noPagadasGastos').css('background-color','#0f7c77');
	}
	if(condicion==1)
	{
		$('#todosGastos').css('font-weight','bold');
		$('#todosGastos').css('color','black');
		$('#todosGastos').css('background-color','#0f7c77');
	}
	
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
		
		//BOTON SUBMIT DE LA VENTANA EMERGENTE DE LA SELECCION DE MES Y AÑO
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
				//HACEMOS SUBMIT MANUAL AL FORMULARIO
				$('#dialog form').submit();
			}
		});	
		});	
		
});

function validarMesYear()
{
	//VALIDAMOS EL MES CORRECTO(PUEDE SER MES CON UN NUMERO Ó 2)
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
	
	//VALIDAMOS EL AÑO CORRECTO
	var regexYear=/^([2][0][1][6-9])$/;
	var valorYear=$('#year_seleccion').val();	
	
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