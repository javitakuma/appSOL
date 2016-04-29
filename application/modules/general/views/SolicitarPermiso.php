 <div id="contenedor_permisos">
        <div class="volver">
			<img title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>general/imc"'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p class="titulo-grande centrado" onclick="pintar()">SOLICITUD PERMISOS</p>
	        <br/>
	        <br/>
	        
	<div id="superior">       
		<article id="contenedor_calendario">
			<table class="datepickers-cont">
				<tr>
					<td class="part">					
						<div id="calendario" class="datepicker ll-skin-latoja"></div>
					</td>				
				</tr>
	
			</table>
		</article>	
		
		<div id="dias_pendientes" class="centrado centrado-margin titulo-mediano">
	        	<div>
	        		<p>DIAS PENDIENTES</p>
	        	</div>
	        	<div>
	        		<label>Año 2015</label><p id="pendientesDebidosMostrar"></p>
	        		<label>Año 2016</label><p id="pendientesMostrar"></p>	
	        	</div>	        	
	     </div><!-- CIERRE dias_pendientes -->      
	  </div>  
</div>



<input type="hidden" id="diasPendientesDebidos" value="5"/>
<input type="hidden" id="diasPendientes" value="22"/>


<script>
	var festivosDesdePhp=<?php echo $festivos?>;
</script>

