

<div id="contenedorHojaGastos">
	
	<!--BOTON VOLVER, TITULO, FECHA PAGO Y BOTON ENVIAR GASTOS  -->
	<div class="volver">
			<img class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='confirmar_boton_volver()'/>			
	</div>
	<h1 class="centrado titulo-grande" onclick="pintar()">HOJA DE GASTOS <?php echo $this->session->userdata('nom_consultor')." ("?><?php echo $mes_texto?> de <?php echo $year.")"?></h1>
	<br/>
	<div id="superior">	 							
				<?php if($datos_gastos['t_hojas_gastos'][0]['sw_autorizar_revision']==0):?>
	            <input id="enviar_gastos" class="buttonGenericoPeque centrado" type="button" value="Enviar gastos"/>
	 			<?php endif;?> 		           
		<br/><br/>
	</div>
		
	<!-- LISTA GASTOS, TOTALES -->
	<div  id="inferior">        	
	        	
	        	<!--SOLO PINTAMOS LOS PENDIENTES SI NO ESTA ENVIADA LA HOJA GASTOS  -->
	        	<?php if($datos_gastos['t_hojas_gastos'][0]['sw_autorizar_revision']==0):?>
	        	
	        	<h3 class="titulo-grande">Lista de gastos</h3>
	        	
	        	<!--PARRAFO PARA MOSTRAR SI NO HAY FILAS  -->
	        	<p id="parrafo_sin_gastos" class="titulo-mediano">No dispones de gastos en este mes</p>
	        	<div id="div_agregar_fila"><img title="Agregar fila" class="imagen_agregar_fila " src="<?php echo base_url()?>assets/img/cross.png"/><p>Agregar nueva fila</p></div>
	            <table id="tabla_gastos_pendientes_mes" class="tabla_key">   
	                     	
	                 <!-- 
	                 CON ESTO PINTAMOS LA PRIMERA FILA DE LA TABLA 
	                 -->
	                 <tr class="fila-titulos">
						<th class="id_proyecto_titulo">PROYECTO</th>
						<th class="tipo_gasto_titulo">TIPO</th>
						<th class="fecha_gasto_titulo">FECHA</th>
						<th class="valor_gasto_titulo">VALOR(€)</th>
						<th class="descripcion_gasto_titulo">DESCRIPCIÓN LINEA GASTO</th>									
					</tr>	                
	                
	                
	                
	                
	                <!-- 
	                 CON ESTO PINTAMOS UNA FILA DE LA TABLA POR CADA LINEA DE GASTO QUE HAYAMOS COGIDO DE LA BASE DE DATOS
	                 
	                 -->
	                
	                <?php foreach ($datos_gastos['lineas_gastos_pendientes'] as $linea_gastos):?>
	                
	                <tr id="<?php echo $linea_gastos['k_linea_gasto']?>" class="<?php echo $linea_gastos['k_linea_gasto']?> grabada celda-color fila-datos">
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
	                    	<input class="input_datos" type="text" placeholder="dd-mm-yyyy" value="<?php echo $linea_gastos['f_linea_gasto']?>"/>	                    	
	                    </td>     
	                    
	                    <td class="valor_gasto">
	                    	<input class="input_datos" type="text" value="<?php echo $linea_gastos['i_imp_linea_gasto']?>"/>                    	
	                    </td> 
	                    
	                    <td class="descripcion_gasto">
	                    	<textarea  maxlength="100"><?php echo $linea_gastos['desc_linea_gasto']?></textarea>
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
	            <br/><br/>
	                   
	             <?php endif;?>
	            
	            <br/>
	            <br/>
	            
	            
	            <!--SOLO PINTAMOS LOS REVISADOS SI NO ESTA ENVIADA LA HOJA GASTOS  -->
	            <?php if($datos_gastos['t_hojas_gastos'][0]['sw_autorizar_revision']!=0):?>
	         	
	         	<h3 class="titulo-grande">Gastos revisados</h3>
	         	
	         	<table id="tabla_gastos_todos_mes" class="tabla_key">            	
	                 <!-- 
	                 CON ESTO PINTAMOS LA PRIMERA FILA DE LA TABLA 
	                 -->
	                 <tr class="fila-titulos">
						<th id="id_proyecto_titulo">PROYECTO</th>
						<th id="tipo_gasto_titulo">TIPO</th>
						<th id="fecha_gasto_titulo">FECHA</th>
						<th id="valor_gasto_titulo">VALOR(€)</th>
						<th id="aut1_titulo">AUT 1</th>
						<th id="aut2_titulo">AUT 2</th>
						<th id="descripcion_titulo">DESCRIPCIÓN</th>	
						<th id="rechazo_titulo">COMENTARIO RECHAZO</th>								
					</tr>
	                
	                
	                <!-- 
	                 CON ESTO PINTAMOS UNA FILA DE LA TABLA POR CADA LINEA DE GASTO QUE HAYAMOS COGIDO DE LA BASE DE DATOS
	                 
	                 -->
	                
	                <?php foreach ($datos_gastos['lineas_gastos_todas'] as $linea_gastos):?>
	                
	                <tr id="<?php echo $linea_gastos['k_linea_gasto']?>" class="celda-color fila-datos">
	                    <td class="id_proyecto <?php echo $linea_gastos['k_linea_gasto']?> grabada">
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
	                    
	                    <td class="descripcion_gasto"><textarea disabled="disabled"><?php echo $linea_gastos['desc_linea_gasto']?></textarea></td> 
	                    
	                    <td class="comentario_rechazo"><textarea disabled="disabled"><?php echo $linea_gastos['com_rechazo_linea_gasto']?></textarea></td>              
	                    
	                </tr>
	                	
	                <?php endforeach;?>  
	                
	            </table> <!-- FINAL tabla_gastos_todos_mes -->
			 	<br/>
	         	<br/>
	         	
	         	<?php endif;?>
	         	
			 	<!-- TABLA TOTALES -->
	            <h3 class="titulo-grande">Gastos Totales</h3>
	            <table id="totales_gastos" class="tabla_key">
	                <tr>
	                 	<th>TOTAL</th>
	                 	<th>PENDIENTES</th>
	                 	<th>AUTORIZADOS</th>
	                 	<th>NO AUTORIZADOS</th>
	                 	<th>TOTAL PAGADO</th>
	                </tr>
	                <tr>	                	
	                    <td id="total_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_hoja_gastos']?>€</td>
	                    <td id="pendientes_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_gastos_pendientes']?>€</td>
	                    <td id="autorizados_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_gastos_autorizados']?>€</td>
	                    <td id="no_autorizados_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_tot_gastos_no_autorizados']?>€</td>
	                    <td id="total_pagado_hoja"><?php echo $datos_gastos['gastos_totales'][0]['i_imp_pagado']?>€</td>	                    
	                </tr>	                
	            </table>
	           
	         	
			 
	        </div><!-- CIERRE INFERIOR -->
	
</div>

<!-- CAMPOS CON VARIABLES QUE NECESITAREMOS LUEGO EN LADO CLIENTE -->
		<input type="hidden" id="k_hoja_gastos" value="<?php echo $datos_gastos['t_hojas_gastos'][0]['k_hoja_gastos']?>"/>
		<input type="hidden" id="mes_hoja" value="<?php echo $mes?>"/>
		<input type="hidden" id="año_hoja" value="<?php echo $year?>"/>	
		
		<script>
			var proyectos_consultor=<?php echo $datos_gastos['proyectos_consultor_JSON']?>;
			var tipos_gasto=<?php echo $datos_gastos['tipos_gastos_JSON']?>;
		</script>


