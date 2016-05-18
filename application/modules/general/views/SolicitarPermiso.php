 <div id="contenedor_permisos">
        <div class="volver">
			<img title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>general/Permisos"'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p class="titulo-grande centrado" onclick="pintar()">SOLICITUD PERMISOS <?php echo $tipo_solicitud?></p>
	        <p class="titulo-grande centrado"><?php echo $this->session->userdata('nom_consultor')?></p>
	        
	        <?php if(true):?>
	            <input id="enviar_solicitud" class="buttonGenericoPeque centrado" type="button" value="Enviar solicitud"/>
	 		<?php endif;?> 
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
		        		<label>Año <?php echo $year_solicitud-1?></label><p id="pendientesDebidosMostrar"></p>
		        		<label>Año <?php echo $year_solicitud?></label><p id="pendientesMostrar"></p>	
		        	</div>	        	
		     </div><!-- CIERRE dias_pendientes -->
		     <div id="comentarios_permiso">
		     	<h3 class="centrado titulo-mediano">Observaciones</h3>
		     	<textarea maxlength="150" id="observaciones_textarea"></textarea>
		     </div>
	     </div>           
	     <div id="flotador_superior"></div>
	  </div> <!-- CIERRE superior -->  
	  
	  
	  <input id="grabar_solicitud" class="buttonGenericoPeque" type="button" value="Grabar solicitud"/>
	  
	  <div id="inferior">
	  
	 <!-- TABLA INFERIOR PARA INTRODUCIR HORAS --> 
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
	
	
	
	<!-- AÑO ANTERIOR -->
	<?php for ($j=10;$j<=12;$j++):?>
	<tr id="fila_mes_<?php echo $j?>_ant" class="fila-datos">                    	
		<td class="nombres_meses">NombreMes</td>
		<?php for ($k=1;$k<=31;$k++):?>  
		
			<?php if($k<10):?>
				<?php $k="0".$k?>
			<?php endif;?>
		                  	
			<td><input id="<?php echo $k."-".$j?>-2015" class="input-datos" type="text" value="0"/></td>
		<?php endfor;?>
	</tr>
	<?php endfor;?>
	
	
	
	
	
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
		                  	
			<td><input id="<?php echo $k."-".$j?>-2016" class="input-datos" type="text" value="0"/></td>
		<?php endfor;?>
	</tr>
	<?php endfor;?>
	
	<?php if($existe_next_year_bbdd==true):?>
		<tr id="fila_mes_01_sig" class="fila-datos">
		<td class="nombres_meses">aa</td>                    	
			<?php for ($k=1;$k<=31;$k++):?>  
		
			<?php if($k<10):?>
				<?php $k="0".$k?>
			<?php endif;?>
		                  	
			<td><input id="<?php echo $k?>-01-2017" class="input-datos" type="text" value="1"/></td>
		<?php endfor;?>
		</tr>	
	<?php endif;?>
	
	
</table>
	  </div>
	   
</div>



<input type="hidden" id="diasPendientesDebidos" value="<?php echo $diasDebidosPendientes?>"/>
<input type="hidden" id="diasPendientes" value="<?php echo $diasDebidos?>"/>
<input type="hidden" id="tipo_solicitud" value="<?php echo $tipo_solicitud?>"/>
<input type="hidden" id="k_proyecto_solicitud" value="<?php echo $k_proyecto_solicitud?>"/>
<input type="hidden" id="responsable_solicitud" value="<?php echo $responsable_solicitud?>"/>
<input type="hidden" id="horas_jornada" value="<?php echo $horas_jornada?>"/>
<input type="hidden" id="year_solicitud" value="<?php echo $year_solicitud?>"/>
<input type="hidden" id="existe_next_year_bbdd" value="<?php echo $existe_next_year_bbdd?>"/>
<input type="hidden" id="k_permisos_solic" value="<?php echo isset($k_permisos_solic)?$k_permisos_solic:'0'?>"/>

<?php if(isset($habilitar_edicion)):?>
<input type="hidden" id="habilitar_edicion" value="0"/>
<?php endif;?>

<?php if(!isset($habilitar_edicion)):?>
<input type="hidden" id="habilitar_edicion" value="1"/><!-- CAMBIAR POR VALUE=1 -->
<?php endif;?>


<script>
	var festivosDesdePhp=<?php echo $festivos?>;
	var diasDesdePhp=<?php echo $diasYaSolicitados?>;
</script>

