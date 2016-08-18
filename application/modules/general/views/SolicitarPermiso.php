 <div id="contenedor_permisos">
        <div id="div-volver" class="volver">
			<img id="imagen-volver" title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='confirmar_boton_volver()'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p id="titulo-pagina" class="titulo-grande centrado">SOLICITUD PERMISOS <?php echo $tipo_solicitud?></p>
	        <p class="titulo-grande centrado"><?php echo $this->session->userdata('nom_consultor')?></p>
	        
	        <?php if($solovista!=1):?>
	            <input id="enviar_solicitud" class="buttonGenericoPeque centrado" type="button" value="Enviar solicitud"/>
	 		<?php endif;?> 
	        <br/>
	        <br/>
	<img id="ayuda_permisos" title="Ayuda solicitud permisos" class="cursor_pointer" src="<?php echo base_url()?>assets/img/help.png"/>
	     
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
			<?php if ($solovista!=1):?>
			
			<div id="dias_pendientes" class="centrado centrado-margin">
	        	<div id="dias_pendientes_titulo" class=" titulo-mediano">
	        		<p>DIAS PENDIENTES</p>
	        	</div>
	        	<div id="dias_pendientes_anterior">
	        		<label>Año <?php echo $year_solicitud-1?></label><p id="pendientesDebidosMostrar"></p><p>&nbsp;día/s.</p>
	        	</div>
	        	<div id="dias_pendientes_actual">
	        		<label>Año <?php echo $year_solicitud?></label><p id="pendientesMostrar"></p><p>&nbsp;día/s.</p>
	        	</div>
	        	
	        	<!-- SI TIENE DIAS DEL AÑO PROXIMO PINTAMOS ESTO(invisible) -->
	        	<?php if($dias_base_siguiente==null):?>
	        		<div id="dias_pendientes_siguiente" class="invisible">
	        			<label>Año <?php echo $year_solicitud+1?></label><p id="pendientesFuturoMostrar"></p><p>&nbsp;día/s.</p>
	        		</div>
	        	<?php endif;?>
	        	
	        	
	        	<!-- SI TIENE DIAS DEL AÑO PROXIMO PINTAMOS ESTO -->
	        	<?php if($dias_base_siguiente!=null):?>
	        		<div id="dias_pendientes_siguiente">
	        			<label>Año <?php echo $year_solicitud+1?></label><p id="pendientesFuturoMostrar"></p><p>&nbsp;día/s.</p>
	        		</div>
	        	<?php endif;?>			        	        	
		     </div><!-- CIERRE dias_pendientes -->
			
			
		     <div id="comentarios_permiso">
		     	<h3 class="centrado titulo-mediano">Observaciones</h3>
		     	<textarea maxlength="150" id="observaciones_textarea"></textarea>
		     </div>
		     
		     <?php endif;?>
	     </div>  
	     
	     
	              
	     <div id="flotador_superior"></div>
	  </div> <!-- CIERRE superior -->  
	  
	  
	  
	  
	  <?php if ($solovista!=1):?><!-- IF TABLA INFERIOR -->
	  <input id="grabar_solicitud" class="buttonGenericoPeque" type="button" value="Grabar solicitud"/>	  
	  
	  <div id="inferior">
	  <h2 class="titulo-mediano">Histórico permisos año actual</h2>
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
	
	<!-- AÑO ACTUAL -->
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
	
	<!-- AÑO SIGUIENTE SI EXISTE EN t_calendario -->
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
	   <?php endif;?><!-- FIN IF TABLA INFERIOR -->
	   
</div>



<input type="hidden" id="diasPendientesDebidos" value="<?php echo $diasDebidosPendientes?>"/>
<input type="hidden" id="diasPendientes" value="<?php echo $diasDebidos?>"/>
<input type="hidden" id="diasPendientesFuturo" value="<?php echo $diasDebidosFuturo?>"/>
<input type="hidden" id="tipo_solicitud" value="<?php echo $tipo_solicitud?>"/>
<input type="hidden" id="k_proyecto_solicitud" value="<?php echo $k_proyecto_solicitud?>"/>
<input type="hidden" id="responsable_solicitud" value="<?php echo $responsable_solicitud?>"/>
<input type="hidden" id="horas_jornada" value="<?php echo $horas_jornada?>"/>
<input type="hidden" id="year_solicitud" value="<?php echo $year_solicitud?>"/>
<input type="hidden" id="existe_next_year_bbdd" value="<?php echo $existe_next_year_bbdd?>"/>
<input type="hidden" id="k_permisos_solic" value="<?php echo isset($k_permisos_solic)?$k_permisos_solic:'0'?>"/>
<input type="hidden" id="solovista" value="<?php echo $solovista?>"/>
<input type="hidden" id="primer_dia_t_calendario" value="<?php echo $primer_dia_t_calendario?>"/>
<input type="hidden" id="ultimo_dia_t_calendario" value="<?php echo $ultimo_dia_t_calendario?>"/>
<input type="hidden" id="adm_rrhh" value="<?php echo $adm_rrhh?>"/>

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



<!-- DIALOG PARA SELECCION Y EDICION -->

	<div id="dialog">
		<img title="Cerrar" id="imagen_cierre_popup" src="<?php echo base_url()?>assets/img/cross.png"/>	
		<p id="titulo_ayuda" class="centrado titulo-mediano">AYUDA SOLICITUD PERMISOS</p>
		
		<h2 id ="tit_leyenda" class="titulo-peque">Leyenda colores.</h2>
		<div id="contenedor_ayuda_colores">
			<div id="cuadrado_aceptado" class="contenedor_tipo_estado">
				<p>Autorizado*</p>
			</div>
			<div id="cuadrado_pendiente" class="contenedor_tipo_estado">
				<p>Pendiente*</p>
			</div>
			<div id="cuadrado_rechazado" class="contenedor_tipo_estado">
				<p>Rechazado</p>
			</div>
			<div id="cuadrado_seleccionado" class="contenedor_tipo_estado">
				<p>Seleccionado</p>
			</div>
			
		</div>
		<p>*En color negro y cursiva los días pendientes del año anterior.</p>
		<div id="texto_ayuda">	
			<p>- Situate con el ratón encima del dia solicitado que desees para ver su campo observaciones.</p>
			
			<!-- ESTA PARTE SOLO LO HAREMOS SI NO ESTA EN MODO SOLOVISTA -->
			<?php if($solovista!=1):?>
			<p>- Selecciona solo días laborables, salvo que tu calendario no sea el de Madrid y desees coger uno de los festivos de dicha región.</p>
			<p>- No selecciones los días festivos propios de tu región si tu calendario no es el de Madrid.</p>
			<p>- Es necesario imputar el número de horas de jornada laboral para el tipo de permiso Keyotros.</p>
			<p>- Es obligatorio rellenar el campo observaciones.</p>
			<p>- Solicita los permisos dividiéndolos por paquetes vacacionales, evitando solicitar semanas no consecutivas.</p>
			<p><span class="negrita">- Tipo solicitud Keyotros: Obligatorio rellenar el campo observaciones con un patrón formado por: prefijo predefinido + referencia mínima al motivo (máximo de 40 caracteres).</span> Los permisos responden a los siguientes prefijos predefinidos que deben coincidir literalmente EN MAYUSCULAS Y SIN ACENTOS tal que:</p>
			<br/>
	        <ul>
	            <li>
	                <span class="negrita">HOSPITAL FAMILIAR,</span> permiso por enfermedad grave, operación u hospitalización de familiar hasta 2º grado de consanguinidad o afinidad. Hasta 2 días dependiendo de las circunstancias y gravedad del asunto, con un máximo de 4 días en caso de desplazamiento de más de 200 km por trayecto. <span class="negrita">Ejemplo: HOSPITAL FAMILIAR operación cónyuge</span>
	            </li>
	            <li>
	                <span class="negrita">DEFUNCION FAMILIAR,</span>permiso por defunción de 4 días para familiares de 1er grado (padres, hijos, cónyuge), o de 2 días para familiares hasta 2º grado de consanguinidad o afinidad, con un máximo de 4 días para ambos casos si incluye desplazamientos largos.  <span class="negrita">Ejemplo: DEFUNCION FAMILIAR abuela</span>
	            </li>
	            <li>
	                <span class="negrita">ASUNTOS PROPIOS,</span>permiso por defunción de 4 días para familiares de 1er grado (padres, hijos, cónyuge), o de 2 días para familiares hasta 2º grado de consanguinidad o afinidad, con un máximo de 4 días para ambos casos si incluye desplazamientos largos.  <span class="negrita">Ejemplo: ASUNTOS PROPIOS Médico, ASUNTOS PROPIOS firma hipoteca</span>
	            </li>
	            <li>
	                <span class="negrita">MUDANZA,</span>1 día en caso de cambio de domicilio.
	            </li>
	            <li>
	                <span class="negrita">MATRIMONIO,</span>15 días naturales de permiso por matrimonio.
	            </li>
	            <li>
	                <span class="negrita">PATERNIDAD,</span> permiso a cargo de la empresa de 2 días naturales inmediatos al nacimiento de hijo. Diferenciar de los 13 días de baja paternal cubierta por la Prestación de la Seguridad Social que se imputan a KEYBAJA.
	            </li>
	            <li>
	                <span class="negrita">ACADEMICO,</span>para asistencia a exámenes oficiales debidamente notificados y justificados. <span class="negrita">Ejemplos: ACADEMICO Examen Universidad, ACADEMICO Defensa Proyecto Final Carrera.</span>
	            </li>
	            <li>
	                <span class="negrita">LACTANCIA,</span>permiso que da derecho a las madres a ausentarse 1 hora al día hasta que el bebé cumple 9 meses, o como alternativa, permiso acumulado de 15 días naturales justo después de la baja maternal.
	            </li>            
	            <li>
	                <span class="negrita">PRENATAL</span> permiso que da derecho a ausentarse para la realización de pruebas prenatales y técnicas de preparación al parto, que sean de necesidad dentro de la jornada de trabajo.
	            </li>
	            <li>
	                <span class="negrita">PERMISO KEYRUS,</span>, aplicaría a todo lo que no está recogido en los anteriores bajo conocimiento y autorización del responsable y RRHH. De forma general aplica a la jornada de cortesía de Keyrus a elegir entre 24 y 31 de Diciembre.  <span class="negrita">Ejemplo: PERMISO KEYRUS Navidad.</span>
	            </li>
	            <li>
	                <span class="negrita">PERMISO SIN SUELDO</span> por un máximo de 1 mes, no más de una vez por año, concedido por la empresa a instancia del empleado, que debe tener al menos 1 año de antigüedad en la empresa.
	            </li>
	            
	            
	        </ul>
	        <?php endif;?>
		</div>
		
	</div>


<div id="sombra"></div>

<div id="enviando">
   <p class="titulo-grande">ESPERE POR FAVOR...</p>
</div>

