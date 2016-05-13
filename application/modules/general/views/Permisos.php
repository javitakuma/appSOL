 <div id="contenedor_permisos">
        <div class="volver">
			<img title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>welcome"'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p class="titulo-grande centrado">PERMISOS <?php echo $this->session->userdata('nom_consultor')?></p>
	        <br/>
	        <br/>
	        
	        <br/>
	        <br/>
	        <div id="permisos_superior">	        	
	        	<div id="permisos_sup_izq">
	        		<form id="form_nueva_solicitud" method="post" action="<?php echo base_url()?>general/Permisos/solicitar_permiso">	        		
			        	<div class="contenedor_select_35 textoSmallCaps">
				            <label>Tipo de solicitud</label>
				            <select id="tipo_solicitud" name="tipo_solicitud">
				            	<option value="0">Selecciona un tipo de permiso</option>
				            	<?php foreach ($tipo_permisos['tipo_solicitud'] as $tipo):?>
				            		<option value="<?php echo $tipo['k_proyecto']?>"><?php echo $tipo['nom_proyecto']?></option>
				            	<?php endforeach;?>
				            </select>
			            </div>
			            
				        <div class="contenedor_select_35 textoSmallCaps">
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
			            <br/><br/>  
	              	</form>
	        	</div><!-- CIERRE permisos_sup_izq -->
	        	
	        	<div id="permisos_sup_der">
	        		<div id="dias_pendientes" class="centrado centrado-margin titulo-mediano">
		        	<div>
		        		<p>DIAS PENDIENTES</p>
		        	</div>
		        	<div>
		        		<label>Año <?php echo $year_actual-1?></label><p id="pendientesDebidosMostrar"><?php echo $diasDebidosPendientes?></p>
		        		<label>Año <?php echo $year_actual?></label><p id="pendientesMostrar"><?php echo $diasDebidos?></p>	
		        	</div>	        	
		     </div><!-- CIERRE dias_pendientes -->  		     	
	        		
	        	</div><!-- CIERRE permisos_sup_der -->
	        </div><!-- CIERRE superior -->
        	
        	<div id="inferior">
				<h3 class="titulo-mediano">Histórico permisos</h3>
					<table id="listadoPermisos" class="tabla_key">
						<tr id="fila-titulos">
							<th id="fecha_titulo">FECHA SOLICITUD</th>
							<th id="tipo_permiso_titulo">TIPO PERMISO</th>
							<th id="solicitante_titulo">SOLICITANTE</th>
							<th id="fecha_inicial_titulo">FECHA INICIAL</th>
							<th id="fecha_final_titulo">FECHA FINAL</th>
							<th id="autorizacion_responsable_titulo">AUTORIZ RESP</th>	
							<th id="autorizacion_rrhh_titulo">AUTORIZ RRHH</th>		
							<th id="observaciones_titulo">OBSERVACIONES</th>		
							<th id="motivo_rechazo_titulo">MOTIVO RECHAZO</th>
							<th id="sw_envio_solicitud">ENVIADO</th>			
						</tr>							
					 
					<?php foreach($historico_permisos as $fila):?>
						<tr>
							<td class="fechaCell"><?php echo $fila['f_solicitud']?></td>
							<td class="tipoCell"><?php echo $fila['id_proyecto']?></td>
							<td class="solicitanteCell"><?php echo $fila['id_consultor']?></td>
							<td class="fechaInicialCell"><?php echo $fila['primer_dia']?></td>
							<td class="fechaFinalCell"><?php echo $fila['ultimo_dia']?></td>
							<td class="autRespCell"><?php echo $fila['i_autorizado_n1']?></td>
							<td class="autRRHHCell"><?php echo $fila['i_autorizado_n2']?></td>
							<td class="observacionesCell"><textarea disabled class="textareaObservaciones"><?php echo $fila['desc_observaciones']?></textarea></td>
							<td class="motivoRechazoCell"><textarea disabled class="textareaMotivoRechazo"><?php echo $fila['desc_rechazo']?></textarea></td>
							<td class="envioCell"><input onclick="return false" type="checkbox" <?php echo ($fila['sw_envio_solicitud']==-1)?' checked':''?>/></td>							
							<?php if(($fila['i_autorizado_n1']=='Pendiente')&&($fila['i_autorizado_n2']=='Pendiente')):?>
								<td class="eliminar_fila borde_invisible no_fondo"><img title="Eliminar fila" class="eliminar_fila_img " src="<?php echo base_url()?>assets/img/cross.png"/></td>	
							<?php endif;?>							
							<?php if($fila['sw_envio_solicitud']==0):?>
								<td  class="eliminar_fila borde_invisible no_fondo"><img onclick="editar_solicitud(<?php echo $fila['k_permisos_solic']?>)" title="Editar fila" class="editar_fila " src="<?php echo base_url()?>assets/img/pen.png"/></td>	
							<?php endif;?>						
						</tr>	
					<?php endforeach;?>
					
					
					
					
					</table>
				</div><!-- CIERRE inferior -->
        	  
	        <br/>
</div>

<input type="hidden" id="diasPendientesDebidos" value="5"/>
<input type="hidden" id="diasPendientes" value="22"/>