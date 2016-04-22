

<div id="contenedorHojaGastos">

	<div class="volver">
			<img class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>general/Gastos"'/>
			
	</div>
	<h1 class="centrado titulo-grande">HOJA DE GASTOS <?php echo $this->session->userdata('nom_consultor')." ("?><?php echo $mes_texto?> de <?php echo $year.")"?></h1>
	
	<div id="superior">
	
	 <?php if($datos_gastos['t_hojas_gastos'][0]['sw_autorizar_revision']==0):?>
	            <input id="enviar_gastos" class="buttonGenericoPeque centrado" type="button" value="Enviar gastos"/>
	            <?php endif;?>		
				<p id="comentarios_hoja_gastos">Comentarios:<?php echo $datos_gastos['t_hojas_gastos'][0]['com_hoja_gastos']?></p>
				<p id="fecha_pago_hoja_gastos">Fecha de pago:<?php echo $datos_gastos['t_hojas_gastos'][0]['f_pago_hoja_gastos']?></p>													
			
		<br/><br/>
		            
			<input class="buttonGenericoPeque" type="button" id="agregar_fila" value="Agregar linea"/>
			
			<br/><br/>
	           
		<br/><br/>
	</div>
	
	 
	
	
	
	<div  id="inferior">        	
	        	
	            <table id="tabla_gastos_pendientes_mes" class="tabla_key">            	
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
	                
	                <?php foreach ($datos_gastos['lineas_gastos_pendientes'] as $linea_gastos):?>
	                
	                <tr id="<?php echo $linea_gastos['k_linea_gasto']?>" class="grabada celda-color fila-datos">
	                    <td class="id_proyecto">
	                    	<select class="select_proyecto">
	                    		<option value="0">Elige una opción</option>
	                    		<?php foreach ($datos_gastos['proyectos_consultor'] as $proyecto):?>
	                    			<option value="<?php echo $linea_gastos['k_proyecto'] ?>" <?php echo ($linea_gastos['k_proyecto']==$proyecto['k_proyecto'])?'selected="selected"':'' ?>><?php echo $proyecto['id_proyecto']?></option>
	                    		<?php endforeach;?>
	                    	</select>
	                    </td>	
	                    
	                    <td class="tipo_gasto">
	                    	<select class="select_tipo_gasto">
	                    		<option value="0">Elige una opción</option>
	                    		<?php foreach ($datos_gastos['tipos_gastos'] as $tipo_gasto):?>
	                    			<option value="<?php echo $tipo_gasto['k_tipo_linea_gasto'] ?>" <?php echo ($linea_gastos['k_tipo_linea_gasto']==$tipo_gasto['k_tipo_linea_gasto'])?'selected="selected"':'' ?>><?php echo $tipo_gasto['nom_tipo_linea_gasto']?></option>
	                    		<?php endforeach;?>
	                    	</select>
	                    </td> 
	                    
	                    <td class="fecha_gasto">
	                    	<input class="input_datos" type="text" placeholder="yyyy-mm-dd" value="<?php echo $linea_gastos['f_linea_gasto']?>"/>	                    	
	                    </td>     
	                    
	                    <td class="valor_gasto">
	                    	<input class="input_datos" type="text" value="<?php echo $linea_gastos['i_imp_linea_gasto']?>"/>                    	
	                    </td> 
	                    
	                    <td class="descripcion_gasto">
	                    	<textarea class="input_datos"><?php echo $linea_gastos['desc_linea_gasto']?></textarea>
	                    </td>  
	                    <td class="borde_invisible no_fondo"><img title="Eliminar fila" class="eliminar_fila " src="<?php echo base_url()?>assets/img/cross.png"/></td>             
	                    
	                </tr>
	                	
	                <?php endforeach;?>  
	                
	            </table> <!-- FINAL tabla_gastos_pendientes_mes -->
	                    
				 <br/>	  
				 
				 
				                   
	              
	            <!-- Con esto pintamos el boton de grabar solo si el IMC no esta enviado -->
	            
	            <?php if($datos_gastos['t_hojas_gastos'][0]['sw_autorizar_revision']==0):?>
	            <input id="grabar" class="buttonGenericoPeque" type="button" value="Grabar datos"/>
	            <?php endif;?>
	                    
	            
	            
	            <br/>
	            <br/>
	            <table id="totales_gastos" class="tabla_key">
	                <tr>
	                 	<th>Total</th>
	                 	<th>Pendientes</th>
	                 	<th>Autorizados</th>
	                 	<th>No autorizados</th>
	                 	<th>Total pagado</th>
	                </tr>
	                <tr>	                	
	                    <td id="total_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_hoja_gastos']?></td>
	                    <td id="pendientes_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_gastos_pendientes']?></td>
	                    <td id="autorizados_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_gastos_autorizados']?></td>
	                    <td id="no_autorizados_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_gastos_no_autorizados']?></td>
	                    <td id="total_pagado_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_imp_pagado']?></td>	                    
	                </tr>	                
	            </table>
	           
	         	<br/>
	         	<br/>
	         	
	         	<table id="tabla_gastos_todos_mes" class="tabla_key">            	
	                 <!-- 
	                 CON ESTO PINTAMOS LA PRIMERA FILA DE LA TABLA 
	                 -->
	                 <tr class="fila-titulos">
						<th>PROYECTO</th>
						<th>TIPO</th>
						<th>FECHA</th>
						<th>VALOR(€)</th>
						<th>AUT 1</th>
						<th>AUT 2</th>
						<th>DESCRIPCIÓN</th>	
						<th>COMENTARIO RECHAZO</th>								
					</tr>
	                
	                
	                <!-- 
	                 CON ESTO PINTAMOS UNA FILA DE LA TABLA POR CADA LINEA DE GASTO QUE HAYAMOS COGIDO DE LA BASE DE DATOS
	                 
	                 -->
	                
	                <?php foreach ($datos_gastos['lineas_gastos_todas'] as $linea_gastos):?>
	                
	                <tr id="<?php echo $linea_gastos['k_linea_gasto']?>" class="celda-color fila-datos">
	                    <td class="id_proyecto<?php echo $linea_gastos['k_linea_gasto']?> grabada">
	                    		<?php foreach ($datos_gastos['proyectos_consultor'] as $proyecto):?>
	                    			<p><?php echo ($linea_gastos['k_proyecto']==$proyecto['k_proyecto'])?$proyecto['id_proyecto']:'' ?></p>
	                    		<?php endforeach;?>
	                    </td>	
	                    
	                    <td class="tipo_gasto">
	                    		<?php foreach ($datos_gastos['tipos_gastos'] as $tipo_gasto):?>
	                    			<p><?php echo ($linea_gastos['k_tipo_linea_gasto']==$tipo_gasto['k_tipo_linea_gasto'])?$tipo_gasto['nom_tipo_linea_gasto']:'' ?></p>
	                    		<?php endforeach;?>
	                    </td> 
	                    
	                    <td class="fecha_gasto"><?php echo $linea_gastos['f_linea_gasto']?></td>     
	                    
	                    <td class="valor_gasto"><?php echo $linea_gastos['i_imp_linea_gasto']?></td> 
	                    
	                    <td class="autorizacion1"><?php echo $linea_gastos['k_linea_gasto_autorizado1']==0?'Pendiente':($linea_gastos['k_linea_gasto_autorizado1']==-1)?'Autorizado':'No autorizado'?></td>
	                    
	                    <td class="autorizacion2"><?php echo $linea_gastos['k_linea_gasto_autorizado2']==0?'Pendiente':($linea_gastos['k_linea_gasto_autorizado1']==-1)?'Autorizado':'No autorizado'?></td>
	                    
	                    <td class="descripcion_gasto"><?php echo $linea_gastos['desc_linea_gasto']?></td> 
	                    
	                    <td class="comentario_rechazo"><textarea><?php echo $linea_gastos['com_rechazo_linea_gasto']?></textarea></td>              
	                    
	                </tr>
	                	
	                <?php endforeach;?>  
	                
	            </table> <!-- FINAL tabla_gastos_todos_mes -->
			 
	        </div><!-- CIERRE INFERIOR -->
	
</div>

<!-- CAMPOS CON VARIABLES QUE NECESITAREMOS LUEGO EN LADO CLIENTE -->
		<input type="hidden" id="k_hoja_gastos" value="<?php echo $datos_gastos['t_hojas_gastos'][0]['k_hoja_gastos']?>"/>	
		



