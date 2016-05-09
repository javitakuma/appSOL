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
				            	<?php foreach ($permisos['tipo_solicitud'] as $tipo):?>
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
		        		<label>Año <?php echo $year_actual-1?></label><p id="pendientesDebidosMostrar"></p>
		        		<label>Año <?php echo $year_actual?></label><p id="pendientesMostrar"></p>	
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
							<th id="autorizacion_responsable_titulo">AUTORIZACION RESPONSABLE</th>	
							<th id="autorizacion_rrhh_titulo">AUTORIZACION RRHH</th>		
							<th id="motivo_rechazo_titulo">MOTIVO RECHAZO</th>			
						</tr>
						
						<tr>
							<td class="YearCell">aaaaaaaa</td>
							<td class="MonthCell">aaaaaaaaaaaa</td>
							<td class="tipoPermisoCell">aaaaaaaaaaaa</td>
							<td class="fechaInicialCell">aaaaaaaaaaa</td>
							<td class="fechaFinalCell">aaaaaaaaaaa</td>
							<td class="numeroDiasCell">aaaaaaaaaaaaaa</td>
							<td class="totalPagado">aaaaaaaaaa</td>
							<td class="horasTituloCell">aaaaaaaaa</td>
							<td class="borde_invisible no_fondo"><img title="Editar fila" class="editar_fila " src="<?php echo base_url()?>assets/img/cross.png"/></td>
						</tr>	
						<tr>
							<td class="YearCell">aaaaaaaa</td>
							<td class="MonthCell">aaaaaaaaaaaa</td>
							<td class="tipoPermisoCell">aaaaaaaaaaaa</td>
							<td class="fechaInicialCell">aaaaaaaaaaa</td>
							<td class="fechaFinalCell">aaaaaaaaaaa</td>
							<td class="numeroDiasCell">aaaaaaaaaaaaaa</td>
							<td class="totalPagado">aaaaaaaaaa</td>
							<td class="horasTituloCell">aaaaaaaaa</td>
							<td class="borde_invisible no_fondo"><img title="Editar fila" class="editar_fila " src="<?php echo base_url()?>assets/img/cross.png"/></td>
						</tr>		
					<!--  
					<?php foreach($permisos['historico_permiso'] as $fila):?>
						<tr>
							<td class="YearCell"><?php echo $fila['f_año_hoja_gastos']?></td>
							<td class="MonthCell"><?php echo $fila['f_mes_hoja_gastos']?></td>
							<td class="tipoPermisoCell"><?php echo $fila['i_tot_hoja_gastos']?>€</td>
							<td class="fechaInicialCell"><?php echo $fila['i_tot_gastos_pendientes']?>€</td>
							<td class="fechaFinalCell"><?php echo $fila['i_tot_gastos_autorizados']?>€</td>
							<td class="numeroDiasCell"><?php echo $fila['i_tot_gastos_no_autorizados']?>€</td>
							<td class="totalPagado"><?php echo $fila['i_imp_pagado']?>€</td>
							<td class="horasTituloCell"><?php echo $fila['f_pago_hoja_gastos']?></td>
							<td class="consumidasCell"><?php echo ($fila['sw_autorizar_revision']==-1)?' checked':''?> /></td>
						</tr>	
					<?php endforeach;?>
					-->
					</table>
				</div><!-- CIERRE inferior -->
        	  
	        <br/>
</div>

<input type="hidden" id="diasPendientesDebidos" value="5"/>
<input type="hidden" id="diasPendientes" value="22"/>