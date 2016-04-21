

<div id="contenedorHojaGastos">

	<div class="volver">
			<img class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>general/Gastos"'/>
			
	</div>
	<h1 class="centrado titulo-grande">HOJA DE GASTOS <?php echo $this->session->userdata('nom_consultor')." ("?><?php echo $mes_texto?> de <?php echo $year.")"?></h1>
	
	<div id="superior">
		<table id="tablaSuperior">
			<tr class="fila-titulos">
				<th>COMENTARIOS</th>
				<th>FECHA PAGO</th>									
			</tr>
			<tr id="fila-titulos">
				<td><?php echo $t_hojas_gastos['com_hoja_gastos']?></td>
				<td><?php echo $t_hojas_gastos['f_pago_hoja_gastos']?></td>									
			</tr>
		</table>
		<br/><br/>
		            
			<input class="buttonGenericoPeque" type="button" id="agregar_fila" value="Agregar fila"/>
		<br/><br/>
	</div>
	
	 
	
	
	
	<div  id="inferior">
	        
	            <table id="tabla_imc">            	
	                 <!-- 
	                 CON ESTO PINTAMOS LA PRIMERA FILA DE LA TABLA 
	                 -->
	                 <tr class="fila-titulos">
						<th>PROYECTO</th>
						<th>TIPO</th>
						<th>FECHA</th>
						<th>VALOR(€)</th>
						<th>DESCRIPCIÓN</th>									
					</tr>
	                
	                
	                <!-- 
	                 CON ESTO PINTAMOS UNA FILA DE LA TABLA POR CADA LINEA DE GASTO QUE HAYAMOS COGIDO DE LA BASE DE DATOS
	                 
	                 -->
	                
	                <?php foreach ($datos_imc_mes['lineas_imc'] as $linea_imc):?>
	                <tr id="<?php echo $linea_imc['k_proyecto']?>" class="celda-color fila-datos <?php echo ($linea_imc['sw_proy_especial']==-1?'especial':(($linea_imc['sw_interno']==-1)?'interno':'externo'))?>">
	                    <td class="<?php echo $linea_imc['k_linea_imc']?> grabada color_proy"><?php echo $linea_imc['id_proyecto']?></td>
	                    
	                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
	                    	<td class="celda-color dia<?php echo $i<10?'0'.$i:$i?> <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>">
	                    		<input type="text" class="input_horas <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>" value="<?php echo $i<10?$linea_imc["i_horas_0$i"]:$linea_imc["i_horas_$i"]?>"/>
	                    	</td>	
	                    <?php endfor;?>     
	                    
	                                       
	                    <td class="celda-color total_horas_imc color_proy"><?php echo $linea_imc['i_tot_horas_linea_imc']?></td>
	                    <td class="comentarios"><textarea maxlength="50" class="comentarios_textarea"><?php echo $linea_imc['desc_comentarios']?></textarea></td>
	                    <?php if($datos_imc_mes['t_imcs'][0]['sw_validacion']==0):?>
	                    	
	                    	<td class="borde_invisible no_fondo"><img title="Eliminar fila" class="eliminar_fila " src="<?php echo base_url()?>assets/img/cross.png"/></td>
	                    	<!-- 
	                    	<td class="borde_invisible no_fondo"><input class="eliminar_fila " type="image" src="<?php echo base_url()?>assets/img/cross.png"/></td>
	                		 -->    
					<?php endif;?>
	                </tr>	
	                <?php endforeach;?>      
	                
	                
	                
	                <tr id="ultima_fila">
	                    <td>TOTAL</td>                    
	                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
	                    	<td id="total<?php echo $i<10?'0'.$i:$i?>" class="<?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>"></td>	
	                    <?php endfor;?> 
	                    <td id="horas_totales"></td>
	                </tr>
	                
	            </table> 
	                    
				 <br/>	                    
	              
	            <!-- Con esto pintamos el boton de grabar solo si el IMC no esta enviado -->
	            
	            <?php if($datos_imc_mes['t_imcs'][0]['sw_validacion']==0):?>
	            <input id="grabar" class="buttonGenericoPeque" type="button" value="Grabar datos"/>
	            <?php endif;?>
	            <br/><br/>
	             <?php if($datos_imc_mes['t_imcs'][0]['sw_validacion']==0):?>
	            <input id="enviar_imc" class="buttonGenericoPeque" type="button" value="Enviar IMC"/>
	            <?php endif;?>
	            
	            <br/>
	            <table id="totales_imc" border="1">
	                <tr>
	                    <th>Horas consultor</th><th>Horas previstas</th><th>Jornadas totales</th>
	                </tr>
	                <tr>
	                	<!-- AQUI NO PINTAMOS NADA, LO HAREMOS CON JS -->
	                    <td id="horas_consultor"></td>
	                    <td id="horas_previstas"><?php echo $datos_imc_mes['dias_laborables_por_mes']*8?></td>
	                    <!-- AQUI NO PINTAMOS NADA, LO HAREMOS CON JS -->
	                    <td id="jornadas"></td>
	                </tr>
	                
	            </table>
	           
	         
			 
	        </div><!-- CIERRE INFERIOR -->
	
	
	
	
	
	
	
	
	
			
	
	
		
	<div id="rejillaGastos">
	<h3 class="centrado titulo-mediano"><?php echo $condicion==1?'Todas.':($condicion==2?'Pendiente pago.':'Ninguna.')?></h3>
		<table id="listadoGastosGeneral">
			<tr id="fila-titulos">
				<th>ABRIR</th>
				<th>AÑO</th>
				<th>MES</th>
				<th>TOTAL</th>
				<th>PENDIENTES REVISIÓN</th>
				<th>AUTORIZADOS</th>
				<th>NO AUTORIZADOS</th>	
				<th>TOTAL PAGADO</th>		
				<th>ULT. FECHA PAGO</th>		
				<th>ENVIADA</th>					
			</tr>			
			
			<?php foreach($hojas_gastos as $fila):?>
				<tr>
					<td class="abrirCell" onclick='location.href="<?php echo base_url()?>general/Gastos/mostrarGastosMes/<?php echo $fila['f_año_hoja_gastos']?>/<?php echo $fila['f_mes_hoja_gastos']?>"'>Abrir</td>
					<td class="gastosYearCell"><?php echo $fila['f_año_hoja_gastos']?></td>
					<td class="gastosMonthCell"><?php echo $fila['f_mes_hoja_gastos']?></td>
					<td class="totalCell"><?php echo $fila['i_tot_hoja_gastos']?>€</td>
					<td class="pendientesRevision"><?php echo $fila['i_tot_gastos_pendientes']?>€</td>
					<td class="autorizados"><?php echo $fila['i_tot_gastos_autorizados']?>€</td>
					<td class="noAutorizados"><?php echo $fila['i_tot_gastos_no_autorizados']?>€</td>
					<td class="totalPagado"><?php echo $fila['i_imp_pagado']?>€</td>
					<td class="ultimaFechaPago"><?php echo $fila['f_pago_hoja_gastos']?></td>
					<td class="enviada"><input type="checkbox" onclick="return false"<?php echo ($fila['sw_autorizar_revision']==-1)?' checked':''?> /></td>
				</tr>	
			<?php endforeach;?>
			 
			 
			
			
		</table>
		 
	</div>
</div>



