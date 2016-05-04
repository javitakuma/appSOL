 <div id="contenedor_permisos">
        <div class="volver">
			<img title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>general/imc"'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p class="titulo-grande centrado">PERMISOS <?php echo $this->session->userdata('nom_consultor')?></p>
	        <br/>
	        <br/>
	        <div id="dias_pendientes" class="centrado centrado-margin titulo-mediano">
	        	<div>
	        		<p>DIAS PENDIENTES</p>
	        	</div>
	        	<div>
	        		<p>Año 2015 (22)</p>
	        		<p>Año 2016 (22)</p>		
	        	</div>	        	
	        </div><!-- CIERRE dias_pendientes -->
	        <br/>
	        <br/>
	        <div id="permisos_superior">	        	
	        	<div id="permisos_sup_izq">
		        	<div class="contenedor_select_35 textoSmallCaps">
			            <label>Tipo de solicitud</label>
			            <select id="tipo_solicitud">
			            	<?php foreach ($permisos['tipo_solicitud'] as $tipo):?>
			            		<option value="<?php echo $tipo['k_proyecto']?>"><?php echo $tipo['id_proyecto']?></option>
			            	<?php endforeach;?>
			            </select>
		            </div>
		            
			        <div class="contenedor_select_35 textoSmallCaps">
			            <label>Responsable</label>
			            <select id="cod_proyecto_select">
			                <option value="0">Selecciona un proyecto</option>
			                <option value="1">1</option>
			                <option value="2">2</option>
			            </select>
		            </div>
		            <br/><br/>
		            
		            <input onclick='location.href="<?php echo base_url()?>General/Permisos/solicitar_permiso"' class="buttonGenericoPeque" type="button" id="seleccionar_dias" value="Seleccionar días"/>
		            <br/><br/>  
	              
	        	</div><!-- CIERRE permisos_sup_izq -->
	        	
	        	<div id="permisos_sup_der">
	        	
	        		<div class="fila-autorizaciones">
	        			<div class="radio-container">
	        				<input type="radio" id="aut_responsable" onclick="return false"/>
	        				<label>Aut. responsable</label>
	        			</div>
	        			<div class="radio-container">
	        				<input type="radio" id="rechazo_responsable" onclick="return false"/>
	        				<label>Rechazo</label>
	        			</div>
	        			<textarea class="com-rechazo" disabled="disabled"></textarea>
	        		</div> 
	        		
	        		<div class="fila-autorizaciones">
	        			<div class="radio-container">
	        				<input type="radio" id="aut_rrhh" onclick="return false"/>
	        				<label>Aut. RRHH</label>
	        			</div>
	        			<div class="radio-container">
	        				<input type="radio" id="rechazo_rrhh" onclick="return false"/>
	        				<label>Rechazo</label>
	        			</div>
	        			<textarea class="com-rechazo" disabled="disabled"></textarea>
	        		</div>       	
	        		
	        	</div><!-- CIERRE permisos_sup_der -->
	        </div><!-- CIERRE superior -->
        	
        	<div id="inferior">
				<h3 class="titulo-mediano">Histórico permisos</h3>
					<table id="listadoPermisos" class="tabla_key">
						<tr id="fila-titulos">
							<th id="anyo_titulo">AÑO</th>
							<th id="mes_titulo">MES</th>
							<th id="tipo_permiso_titulo">TIPO PERMISO</th>
							<th id="fecha_inicial_titulo">FECHA INICIAL</th>
							<th id="fecha_final_titulo">FECHA FINAL</th>
							<th id="numero_dias_titulo">Nº DIAS</th>	
							<th id="horas_titulo">HORAS</th>		
							<th id="consumidas_titulo">CONSUMIDAS</th>			
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