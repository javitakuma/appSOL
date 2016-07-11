
$(document).ready(function() 
{		
	$("#reporte_incidencias").css('display','none');
	
	$("#sombra").css('display','none');			
	
	//EVENTO CLICK
	$('#reporte_boton').on('click',function()
	{
		//LE DAMOS EL TAMAÑO DE LA PANTALLA A LA SOMBRA
		$("#sombra").css('width',$(document).width()+"px");
		$("#sombra").css('height',$(document).height()+"px");
		
		
		//LOS HACEMOS VISIBLES
		$("#reporte_incidencias").css('display','block');
		$("#sombra").css('display','block');
	});	
	
	//LOS CERRAMOS Y HACEMOS INVISIBLES
	$('#imagen_cierre_popup').on('click',function()
	{
		$("#reporte_incidencias").css('display','none');
		$("#sombra").css('display','none');
	});
	
	$('#sombra').on('click',function()
	{
    		$("#reporte_incidencias").css('display','none');
    		$("#sombra").css('display','none');		
	});	
	
	$( window ).resize(function() {
		$("#sombra").css('width',$(document).width()+"px");
		$("#sombra").css('height',$(document).height()+"px");
	});
	
	//FINAL EVENTOS POP UPS
	
});

