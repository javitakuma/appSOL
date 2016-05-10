
        
        
        <div id="imc_mensual">
        <div class="volver">
			<img title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>general/imc"'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p class="titulo-grande centrado">IMC <?php echo $this->session->userdata('nom_consultor')." ("?><?php echo $mes_texto?> de <?php echo $year.")"?></p>
	        <br/>
	        <br/>
        	<?php if($datos_imc_mes['t_imcs'][0]['sw_validacion']==0):?>
	            <input id="enviar_imc" class="buttonGenericoPeque centrado-margin" type="button" value="Enviar IMC"/>
	        <?php endif;?>
	        <br/>
	        <br/>
	        <!-- SOLO PINTAMOS ESTA PARTE SI EL IMC NO ESTA ENVIADO -->
	        <?php if($datos_imc_mes['t_imcs'][0]['sw_validacion']==0):?>
	        <h2 class="titulo-mediano">Agregar un proyecto a la tabla</h2>
	        <div id="superior_izquierda">
	        	<div class="contenedor_select_35 textoSmallCaps">
		            Tipo de proyecto
		            <select id="tipo_proyecto">
		                <option value="0">Selecciona una opcion</option>
		                <option value="1">Proyecto externo</option>
		                <option value="2">Proyecto interno</option>
		                <option value="3">Proyecto especial</option>
		            </select>
	            </div>
		        <div class="contenedor_select_35 textoSmallCaps">
		            Código proyecto
		            <select id="cod_proyecto_select" disabled>
		                <option value="0">Selecciona un proyecto</option>
		            </select>
	            </div>
	            <br/><br/>
	            
	            <input class="buttonGenericoPeque" type="button" id="agregar_proyecto" value="Agregar proyecto"/>
	            <br/><br/>  
	              
	        </div>    
        <?php endif;?>
        	
        	 
        	<div id="ayuda_proyectos">
        		<div class="interno contenedor_ayuda">
        			<img title="Ayuda proyecto interno" class="cursor_pointer" src="<?php echo base_url()?>assets/img/help.png"/><p>Proyectos internos</p>
        		</div>
        		<div class="externo contenedor_ayuda">
        			<img title="Ayuda proyecto externo" class="cursor_pointer" src="<?php echo base_url()?>assets/img/help.png"/><p>Proyectos externos</p>
        		</div> 
        		<div class="especial contenedor_ayuda">
        			<img title="Ayuda proyecto especial" class="cursor_pointer" src="<?php echo base_url()?>assets/img/help.png"/><p>Proyectos especiales</p>
        		</div>         	
        	</div>
        	 
        	<!--
        	<div id="ayuda_proyectos">
        		
        			<p class="interno contenedor_ayuda">Proyectos internos</p>
        			<p class="externo contenedor_ayuda">Proyectos externos</p>
        			<p class="especial contenedor_ayuda">Proyectos especiales</p>        		
        		        	
        	</div>
          	-->
	        <div  id="inferior">
	        
	            <table id="tabla_imc">            	
	                 <!-- 
	                 CON ESTO PINTAMOS LA PRIMERA FILA DE LA TABLA CON TANTAS CELDAS COMO DIAS TENGA EL MES
	                 Y COLOCAMOS EL NOMBRE DE CLASES Y VALORES DE FORMA DINAMICA
	                 UTILIZO TERNARIOS EJ:  echo  $i<10   ?  '0'.$i  :  $i 
	                 EVALUA LA CONDICION $i<10, SI ES AFIRMATIVA PINTA '0'.$i, SI NO LO ES PINTA $i
	                 -->
	                 <tr id="fila_titulos">                    
	                    <th id="titulo_cod_proyecto">Código proyecto</th>
	                    
	                    <!-- PINTA ID DINAMICAMENTE Y LA CLASE SEGUN SEA EL DIA LABORABLE O NO --> 
	                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>                    	
	                    	<th id="titulo_dia<?php echo $i<10?'0'.$i:$i?> "
	                    	class="<?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>"><?php echo $i?></th>	
	                    <?php endfor;?>         
	                                  
	                    <th id="titulo_horas_totales">TOT</th>                    
	                    <th id="titulo_comentarios">Comentarios</th>
	                </tr>
	                
	                
	                <!-- 
	                 CON ESTO PINTAMOS UNA FILA DE LA TABLA POR CADA LINEA DE IMC QUE HAYAMOS COGIDO DE LA BASE DE DATOS
	                 LE DAMOS CLASE INTERNO, EXTERNO O ESPECIAL SEGUN SEA
	                 COLOCAMOS EL NOMBRE DE CLASES Y VALORES DE FORMA DINAMICA
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
						<!-- PINTAMOS LA ULTIMA CELDA CON DISPLAY NONE PARA QUE NO DESCUADRE EL CSS -->
						
						<?php if($datos_imc_mes['t_imcs'][0]['sw_validacion']==-1):?>
	                    	
	                    	<td class=" display_none borde_invisible no_fondo"><img title="Eliminar fila" class="eliminar_fila " src="<?php echo base_url()?>assets/img/cross.png"/></td>
	                    	<!-- 
	                    	<td class="borde_invisible no_fondo"><input class="eliminar_fila " type="image" src="<?php echo base_url()?>assets/img/cross.png"/></td>
	                		 -->    
						<?php endif;?>
	                </tr>	
	                <?php endforeach;?>      
	                
	                
	                
	                <tr id="ultima_fila">
	                    <td>TOTAL</td>                    
	                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
	                    	<td id="total<?php echo $i<10?'0'.$i:$i?>" class="celda-totales <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>"></td>	
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
        </div><!-- CIERRE IMC_MENSUAL -->
        
        
        <!-- CAMPOS CON VARIABLES QUE NECESITAREMOS LUEGO EN LADO CLIENTE -->
		<input type="hidden" id="dias_mes" value="<?php echo $datos_imc_mes['dias_por_mes']?>"/>
		<input type="hidden" id="k_imc" value="<?php echo $datos_imc_mes['t_imcs'][0]['k_imc']?>"/>	
		<input type="hidden" id="mes_imc" value="<?php echo $mes?>"/>
		<input type="hidden" id="year_imc" value="<?php echo $year?>"/>	
		
		<input type="hidden" id="celdas_deshabilitadas" value="<?php echo $datos_imc_mes['t_imcs'][0]['sw_validacion']==0?'habilitadas':'deshabilitadas'?>"/>
		
		<script>
			var festivos=<?php echo $datos_imc_mes['dias_festivos_array']?>;
		</script>
		