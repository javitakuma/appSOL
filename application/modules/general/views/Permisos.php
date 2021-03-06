 <div id="contenedor_permisos">
        <div id="div-volver" class="volver">
			<img id="imagen-volver" title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" onclick='location.href="<?php echo base_url()?>welcome"'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->			
		</div>
		<p id="titulo-pagina" class="titulo-grande centrado">PERMISOS <?php echo $this->session->userdata('nom_consultor')?></p>
	        
	        <br/>
	        <br/>
	        
	        <br/>
	        <br/>
	        <div id="permisos_superior">	        	
	        	<div id="permisos_sup_izq">
	        		<form id="form_nueva_solicitud" method="post" action="<?php echo base_url()?>general/Permisos/solicitar_permiso">	        		
			        	<div class="contenedor_select_35">
				            <label>Tipo de solicitud</label>
				            <select id="tipo_solicitud" name="tipo_solicitud">
				            	<option value="0">Selecciona un tipo de permiso</option>
				            	<?php foreach ($tipo_permisos['tipo_solicitud'] as $tipo):?>
				            		<option value="<?php echo $tipo['k_proyecto']?>"><?php echo $tipo['id_proyecto']?></option>
				            	<?php endforeach;?>
				            </select>
			            </div>
			            
				        <div class="contenedor_select_35">
				            <label>Responsable</label>
				            <select id="responsable_solicitud" name="responsable_solicitud">
				                <option value="0">Selecciona un responsable</option>				                
				                <?php foreach ($resp_proyectos as $responsable):?>
				            		<option value="<?php echo $responsable['k_consultor']?>"><?php echo $responsable['nom_consultor']?></option>
				            	<?php endforeach;?>				                
				            </select>
			            </div>
			            <label>Horas jornada laboral:</label><input type="text" id="horas_jornada" name="horas_jornada" disabled="disabled"/>
			            <br/><br/>
			            
			            <input class="buttonGenericoPeque" type="button" id="seleccionar_dias" value="Nueva solicitud"/>
        				<img id="ayuda_permisos" title="Ayuda solicitud permisos" class="cursor_pointer" src="<?php echo base_url()?>assets/img/help.png"/>
        				
			            <br/><br/>  
	              	</form>
	        	</div><!-- CIERRE permisos_sup_izq -->
	        	
	        	<div id="permisos_sup_der">
	        		<div id="dias_pendientes" class="centrado centrado-margin">
			        	<div id="dias_pendientes_titulo" class=" titulo-mediano">
			        		<p>DIAS PENDIENTES</p>
			        	</div>
			        	<div id="dias_pendientes_anterior">
			        		<label>Año <?php echo $year_actual-1?></label><p id="pendientesDebidosMostrar"><?php echo $diasDebidosPendientes?></p><p>&nbsp;día/s.</p>
			        	</div>
			        	<div id="dias_pendientes_actual">
			        		<label>Año <?php echo $year_actual?></label><p id="pendientesMostrar"><?php echo $diasDebidos?></p><p>&nbsp;día/s.</p>
			        	</div>
			        	<?php if($dias_base_siguiente==null):?>
			        		<div id="dias_pendientes_siguiente" class="invisible">
			        			<label>Año <?php echo $year_actual+1?></label><p id="pendientesFuturoMostrar">0</p><p>&nbsp;día/s.</p>
			        		</div>
			        	<?php endif;?>
			        	
			        	<?php if($dias_base_siguiente!=null):?>
			        		<div id="dias_pendientes_siguiente">
			        			<label>Año <?php echo $year_actual+1?></label><p id="pendientesFuturoMostrar"><?php echo $diasDebidosFuturo?></p><p>&nbsp;día/s.</p>
			        		</div>
			        	<?php endif;?>
			        		
			        	        	
		     		</div><!-- CIERRE dias_pendientes -->  		     	
	        		
	        	</div><!-- CIERRE permisos_sup_der -->
	        </div><!-- CIERRE superior -->
        	
        	<div id="inferior">
				<h3 id="titulo_historico_permisos" class="titulo-mediano">Histórico permisos</h3>
				<p id="asterisco_anno_anterior">*(Días pendientes del año anterior)  &nbsp;&nbsp;&nbsp;#(Días solicitados del año próximo)</p>
				<div onclick='location.href="<?php echo base_url()?>general/Permisos/mostrar_permisos_calendario"'  id="div_calendario_permisos">
				    
					<p class="titulo-peque">Ver todas </p><img id="permisos_en_calendario" src="<?php echo base_url()?>assets/img/ojo2.png"/>
				</div>
					<table id="listadoPermisos" class="tabla_key">
						<tr id="fila-titulos">
							<th id="fecha_titulo">FECHA SOLICITUD</th>
							<th id="tipo_permiso_titulo">TIPO PERMISO</th>
							<th id="solicitante_titulo">SOLICITANTE</th>
							<th id="fecha_inicial_titulo">FECHA INICIAL</th>
							<th id="fecha_final_titulo">FECHA FINAL</th>
							<th id="numero_dias">DIAS</th>
							<th id="autorizacion_responsable_titulo">AUTORIZ RESP</th>	
							<th id="autorizacion_rrhh_titulo">AUTORIZ RRHH</th>		
							<th id="observaciones_titulo">OBSERVACIONES</th>		
							<th id="motivo_rechazo_titulo">MOTIVO RECHAZO</th>
							<th id="sw_envio_solicitud">ENVIO</th>			
						</tr>							
					 
					<?php foreach($historico_permisos as $fila):?>
						<tr id="<?php echo $fila['k_permisos_solic']?>">
							<td class="fechaCell"><?php echo $fila['f_solicitud']?></td>
							<td class="tipoCell"><?php echo $fila['id_proyecto']?></td>
							<td class="solicitanteCell"><?php echo $fila['id_consultor']?></td>
							<td class="fechaInicialCell"><?php echo $fila['primer_dia']?></td>
							<td class="fechaFinalCell"><?php echo $fila['ultimo_dia']?></td>
							<td class="numeroDiasCell"><?php echo $fila['numero_dias']?></td>
							<td class="autRespCell"><?php echo $fila['i_autorizado_n1']?></td>
							<td class="autRRHHCell"><?php echo $fila['i_autorizado_n2']?></td>
							<td class="observacionesCell"><textarea disabled class="textareaObservaciones"><?php echo $fila['desc_observaciones']?></textarea></td>
							<td class="motivoRechazoCell"><textarea disabled class="textareaMotivoRechazo"><?php echo $fila['desc_rechazo']?></textarea></td>
							<td class="envioCell"><input onclick="return false" type="checkbox" <?php echo ($fila['sw_envio_solicitud']==-1)?' checked':''?>/></td>	
							
							<td class="borde_invisible no_fondo ver_calendario_img"><img class="ver_calendario_img" onclick='location.href="<?php echo base_url()?>general/Permisos/mostrar_permisos_calendario/<?php echo $fila['k_permisos_solic']?>"' title="Ver en calendario" src="<?php echo base_url()?>assets/img/ojo2.png"/></td>
														
							
							<!-- PINTAMOS ELIMINAR SOLO SI AMBOS SWITCH SON PENDIENTES -->						
							
							
							<?php if(($fila['k_proyecto']=='450') && ($fila['i_autorizado_n1']=='Pendiente') && ($fila['i_autorizado_n2']=='Pendiente')):?>
								<td class="eliminar_fila borde_invisible no_fondo"><img title="Eliminar solicitud" class="eliminar_fila_img " src="<?php echo base_url()?>assets/img/red_cross_120px.png"/></td>	
							<?php endif;?>
							<?php if(($fila['k_proyecto']=='468') && ($fila['i_autorizado_n2']=='Pendiente')):?>
								<td class="eliminar_fila borde_invisible no_fondo"><img title="Eliminar solicitud" class="eliminar_fila_img " src="<?php echo base_url()?>assets/img/red_cross_120px.png"/></td>	
							<?php endif;?>
							
								
							<!-- PINTAMOS EDITAR SOLO SI NO SE HA ENVIADO -->							
							<?php if($fila['sw_envio_solicitud']==0):?>
								<td  class="borde_invisible no_fondo"><img onclick="editar_solicitud(<?php echo $fila['k_permisos_solic']?>)" title="Editar solicitud" class="editar_fila " src="<?php echo base_url()?>assets/img/pen.png"/></td>	
							<?php endif;?>						
						</tr>	
					<?php endforeach;?>
					
					</table>
				</div><!-- CIERRE inferior -->
        	  
	        <br/>
</div>

<input type="hidden" id="diasPendientesDebidos" value="5"/>
<input type="hidden" id="diasPendientes" value="22"/>


<!-- VENTANA EMERGENTE AYUDA -->

<div id="dialog" >
	<img title="Cerrar" id="imagen_cierre_popup" src="<?php echo base_url()?>assets/img/cross.png"/>	
	<p id="titulo_ayuda" class="centrado titulo-mediano">AYUDA SOLICITUD PERMISOS</p>
	<div id="texto_ayuda">	
		<p>- Para KEYVACACIONES deberá indicar el número de horas de la jornada laboral estándar según las condiciones contractuales que apliquen a cada empleado 
			(las mismas horas de jornada estándar con las que se rellena el IMC). Para KEYOTROS no es necesario, para cada tipo de permiso según su naturaleza deberá 
			indicarse manualmente el número de horas estimadas de la ausencia.
		</p>
		<p>- Las solicitudes tanto de vacaciones como de permisos retribuidos se piden en paquetes de jornadas.</p>
		<p>- Para solicitudes de KEYVACACIONES en un mismo paquete se pueden incluir jornadas no consecutivas, aunque lo deseable es que se evite y se soliciten paquetes
		 	de jornadas consecutivas o aisladas. En cualquier caso las jornadas de un paquete nunca podrán afectar a más de dos meses consecutivos. 
			 Los paquetes de vacaciones se forman por la asociación de jornadas completas.</p>
		<p>- Para solicitudes de KEYOTROS, los paquetes no deben mezclar jornadas que atiendan a dos permisos retribuidos de distinta naturaleza. Mayoritariamente serán 
			paquetes que afecten a una jornada, aunque atendiendo a la naturaleza del permiso no tiene porqué. En cualquier caso serán siempre paquetes de días consecutivos y 
			deberá informarse manualmente el número de horas estimadas de la ausencia.
		</p>
		<p>- Para solicitudes de KEYVACACIONES no se pueden mezclar en un mismo paquete jornadas de vacaciones que proceden del cómputo general de dos años naturales diferentes. 
			Hay que crear dos paquetes de solicitud distintos, uno que agote las vacaciones pendientes de disfrutar del año anterior, y de seguido si se necesita crear un segundo 
			paquete sobre el que empezar a hacer uso de las del año siguiente.
		</p>
		<p>Cualquier solicitud puede ser editada siempre y cuando no haya sido enviada.</p>
		<p>Cualquier solicitud de KEYVACACIONES puede ser borrada siempre y cuando los dos niveles de autorización estén pendientes de validación.</p>
		<p>En KEYOTROS el primer nivel de autorización es automático. Cualquier solicitud de KEYOTROS puede ser borrada siempre que el segundo nivel de autorización siga pendiente de validación.</p>
	</div>
	
</div>

<div id="sombra"></div>


