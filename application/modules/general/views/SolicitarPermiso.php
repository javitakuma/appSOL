 <div id="contenedor_permisos">
        <div class="volver">
			<img title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>general/Permisos"'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p class="titulo-grande centrado" onclick="pintar()">SOLICITUD PERMISOS</p>
	        <br/>
	        <br/>
	        
	<div id="superior"> 
		<div id="sup-izq">
			<article id="contenedor_calendario">
				<table class="datepickers-cont">
					<tr>
						<td class="part">					
							<div id="calendario" class="datepicker ll-skin-latoja"></div>
						</td>				
					</tr>		
				</table>
			</article>	
		</div> 
		<div id="sup-der">
			<div id="dias_pendientes" class="centrado centrado-margin titulo-mediano">
		        	<div>
		        		<p>DIAS PENDIENTES</p>
		        	</div>
		        	<div>
		        		<label>Año 2015</label><p id="pendientesDebidosMostrar"></p>
		        		<label>Año 2016</label><p id="pendientesMostrar"></p>	
		        	</div>	        	
		     </div><!-- CIERRE dias_pendientes -->
		     <div id="comentarios_permiso">
		     	<h3 class="centrado titulo-mediano">Comentarios</h3>
		     	<textarea id="comentarios_textarea"></textarea>
		     </div>
	     </div>           
	     <div id="flotador_superior"></div>
	  </div> <!-- CIERRE superior -->  
	  
	  <div id="inferior">
	  	<table id="celdas_horas" class="tabla_key">
	<tr class="primera-fila">
	<th class="nombres_meses">Mes</th>
	<?php for ($i=1;$i<=31;$i++):?> 
	
		<?php if($i<10):?>
			<?php $i="0".$i?>
		<?php endif;?>     
		               	
		<th><?php echo $i?></th>
	<?php endfor;?>
	</tr>
	
	<?php for ($j=1;$j<=12;$j++):?>
		
		<?php if($j<10):?>
			<?php $j="0".$j?>
		<?php endif;?>  
	<tr id="fila_mes_<?php echo $j?>" class="fila-datos">                    	
		<td class="nombres_meses">NombreMes</td>
		<?php for ($k=1;$k<=31;$k++):?>  
		
			<?php if($k<10):?>
				<?php $k="0".$k?>
			<?php endif;?>
		                  	
			<td><input id="<?php echo $k."-".$j?>-2016" type="text" value="0"/></td>
		<?php endfor;?>
	</tr>
	<?php endfor;?>
	
</table>
	  </div>
	   
</div>



<input type="hidden" id="diasPendientesDebidos" value="5"/>
<input type="hidden" id="diasPendientes" value="22"/>

<?php if(isset($habilitar_edicion)):?>
<input type="hidden" id="habilitar_edicion" value="0"/>
<?php endif;?>

<?php if(!isset($habilitar_edicion)):?>
<input type="hidden" id="habilitar_edicion" value="1"/><!-- CAMBIAR POR VALUE=1 -->
<?php endif;?>


<script>
	var festivosDesdePhp=<?php echo $festivos?>;
	var diasYaSolicitados=<?php echo $diasYaSolicitados?>;
</script>

