
$(document).ready(function() 
{		
	$(window).load(function() 
			{
				$('.scroll-pane').jScrollPane();
			});
	
	$("#reporte_incidencias").css('display','none');
	
	$("#sombra_footer").css('display','none');			
	
	//EVENTO CLICK
	$('#reporte_boton').on('click',function()
	{
		//LE DAMOS EL TAMAÑO DE LA PANTALLA A LA SOMBRA
		$("#sombra_footer").css('width',$(document).width()+"px");
		$("#sombra_footer").css('height',$(document).height()+"px");
		
		
		//LOS HACEMOS VISIBLES
		$("#reporte_incidencias").css('display','block');
		$("#sombra_footer").css('display','block');
	});	
	
	//LOS CERRAMOS Y HACEMOS INVISIBLES
	$('#imagen_cierre_popup_footer').on('click',function()
	{
		$("#reporte_incidencias").css('display','none');
		$("#sombra_footer").css('display','none');
	});
	
	$('#sombra_footer').on('click',function()
	{
    		$("#reporte_incidencias").css('display','none');
    		$("#sombra_footer").css('display','none');		
	});	
	
	$( window ).resize(function() {
		$("#sombra_footer").css('width',$(document).width()+"px");
		$("#sombra_footer").css('height',$(document).height()+"px");
	});
	
	//FINAL EVENTOS POP UPS
	
});

