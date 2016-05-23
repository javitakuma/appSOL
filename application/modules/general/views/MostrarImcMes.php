    <div id="imc_mensual">
        <div id="div-volver" class="volver">
			<img id="imagen-volver" title="Volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='confirmar_boton_volver()'/>
			<!--  <h3 class="titulo-peque">Volver</h3>-->
		</div>
	        <p id="titulo-pagina" class="titulo-grande centrado">IMC <?php echo $this->session->userdata('nom_consultor')." ("?><?php echo $mes_texto?> de <?php echo $year.")"?></p>
	        <br/>
	        <br/>
        	
        	
        	
        		<div id="superior">
	        <!-- SOLO PINTAMOS ESTA PARTE SI EL IMC NO ESTA ENVIADO -->
		        
		        
		        	<div id="superior_izquierda">
			        <h2 class="titulo-mediano">Agregar línea de proyecto</h2>
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
			            
			            <input class="buttonGenericoPeque" type="button" id="agregar_proyecto" value="Agregar proyecto"/>
			              
			        </div>    <!-- FIN SUPERIOR IZQ -->
		        	<div id="superior_derecha">
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
		            	
		            	<?php if($datos_imc_mes['t_imcs'][0]['sw_validacion']==0):?>
	            			<input id="enviar_imc" class="buttonGenericoPeque centrado-margin" type="button" value="Enviar IMC"/>
	        			<?php endif;?>
	        	
		        	</div><!-- FIN SUPERIOR IZQ -->
		        </div><!-- FIN SUPERIOR -->		        
       		 
        	
        	<br/><br/>
        	 
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
	                    	class="<?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>"><?php echo $i<10?'0'.$i:$i?></th>	
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
	                    	
	                    	<td class="borde_invisible no_fondo"><img title="Eliminar fila" class="eliminar_fila " src="<?php echo base_url()?>assets/img/red_cross_120px.png"/></td>
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
	                    <td>Total horas</td>                    
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
	                       
	         
	         	<br/><br/>
	         	
	         	
	         	<h3 class="titulo-mediano" onclick="comparar_imc_permisos()">Recordatorio permisos solicitados</h3>
	         	
	         	<table id="tabla_permisos">            	
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
	                    	<th id="titulo_permisos_dia<?php echo $i<10?'0'.$i:$i?>"
	                    	class="celda-titulo <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>"><?php echo $i<10?'0'.$i:$i?></th>	
	                    <?php endfor;?>         
	                                  
	                    <th id="titulo_horas_totales">TOT</th>   
	                </tr>
	               	                
	                
	                <tr id="permisos_keyvacaciones" class="permisos">
	                    <td class="color_proy">KEYVACACIONES</td>	                    
	                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
	                    	<td class="celda-color per-dia<?php echo $i<10?'0'.$i:$i?> <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>">
	                    		<input type="text" class="color_proy input_horas_permisos <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>" value="0"/>
	                    	</td>	
	                    <?php endfor;?>      
	                    <td class="celda-color total_horas_permisos color_proy">0</td>
	                </tr>	
	                
	                <tr id="permisos_keyotros" class="permisos">
	                    <td class="color_proy">KEYOTROS</td>	                    
	                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
	                    	<td class="celda-color per-dia<?php echo $i<10?'0'.$i:$i?> <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>">
	                    		<input type="text" class="color_proy input_horas_permisos <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>" value="0"/>
	                    	</td>	
	                    <?php endfor;?>      
	                    <td class="celda-color total_horas_permisos color_proy">0</td>
	                </tr>
	                
	                     
	                
	                
	                <!--  
	                <tr id="ultima_fila">
	                    <td>TOTAL</td>                    
	                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
	                    	<td id="total<?php echo $i<10?'0'.$i:$i?>" class="celda-totales <?php echo $datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==-1?'laborable':'festivo'?>"></td>	
	                    <?php endfor;?> 
	                    <td id="horas_totales"></td>
	                </tr>
	                -->
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
		
		
		
<!-- POP UPS -->		
		
		
		
<div id="dialog1" class="dialog">
	<img title="Cerrar" class="imagen_cierre_popup" src="<?php echo base_url()?>assets/img/cross.png"/>	
	<p class="titulo_ayuda centrado titulo-mediano">AYUDA PROYECTOS INTERNOS</p>
	<div class="texto_ayuda">	
		<p class="titulo-peque negrita">KEYCURINT: Formación interna / externa</p>
        <ul>
            <li>RR.HH. es el responsable de aprobar las horas imputadas a KEYCURINT por lo que siempre ha de ser informado con anterioridad 
                de la formación que un consultor va a recibir y así poder activarlo previamente en su IMC de ese mes. 
            </li>    
            <li>Las horas que se imputen a KEYCURINT deberán ser completadas con un pequeño comentario al tipo de formación en la 
                que se está involucrado: <span class="negrita">Ejemplo: ‘Formación Interna ODI’ o ‘Formación en SAP Data Services’</span>
            </li>
        </ul>	
            
        <p class="titulo-peque negrita">KEYPREVENTA: Actividades de apoyo a la venta o cualificación de un proyecto y/o tecnología.</p>
        <ul>
            <li>
                El responsable de Preventa es el encargado de aprobar las horas imputadas a KEYPREVENTA, que debe ser informado 
                con anterioridad para que se active el código en el IMC del mes.
            </li>    
            <li>
                Debe completarse el campo comentario con una breve descripción que referencie claramente el objeto de la 
                actividad. <span class="negrita">Ejemplo: ‘Cliente/Tecnología/Actividad’, ‘Direct/Tableau Qlik Sense/POC’</span>
            </li>
            <li>
                Si se ha colaborado en un mismo mes en más de una actividad DISTINTA de preventa, debe rellenarse un 
                <span class="negrita">registro diferente por línea de actividad.</span>
            </li>
        </ul>
	
        <p><span class="titulo-peque negrita">KEYSINPROY:</span>Aunque haya paradas o caídas de actividad mientras se está asociado a la actividad de 
            un proyecto o varios, con dedicación 100%, la imputación no debe hacerse a este código, debe hacerse a los códigos de
            proyectos en los que se está involucrado. A este código de proyecto se imputa únicamente a indicación del responsable, 
            cuando aplican periodos de cambio o solape entre proyectos ya sea con el mismo u otro responsable comercial. Será el 
            responsable comercial normalmente el que comunique al consultor sobre su uso. Se recomienda añadir un comentario con el motivo 
            o referencia. <span class="negrita">Ejemplo: ‘Salida proyecto X responsable Y’</span> 
        </p>
   </div>
	
</div>

<div id="dialog2" class="dialog">
	<img title="Cerrar" class="imagen_cierre_popup" src="<?php echo base_url()?>assets/img/cross.png"/>	
	<p class="titulo_ayuda centrado titulo-mediano">AYUDA PROYECTOS EXTERNOS</p>
	<div class="texto_ayuda">	
		<p>Criterios generales de imputación de horas producidas a proyectos sobre jornada estándar:</p>
        <ul>
            <li>
                El IMC es un documento fundamental para el funcionamiento de la empresa. Desde su creación en torno a la segunda semana
                del mes, se recomienda completarlo diariamente para evitar errores y olvidos a la hora de imputar horas trabajadas en 
                el proyecto correcto.
            </li>
            <li>
                Se debe cumplimentar todos los días trabajados y asignar el tiempo al proyecto correspondiente. Las <span class="negrita">
                jornadas laborales estándar SIEMPRE serán de 8 horas incluidos viernes</span>, independientemente de horas extras o jornada de
                verano.
            </li>
            <li>
                Si no aparece en el desplegable vuestro código de proyecto, solicitadlo a vuestro responsable.
            </li>
            <li>
                <span class="negrita">Empleados en jornada reducida</span> imputarán las horas que le corresponden a su acuerdo de reducción de lunes a viernes. 
                Independientemente de la actividad (proyecto, vacaciones, enfermedad, …) sus jornadas sumarán el total de horas de su acuerdo,
                no las 8 horas estándar.
            </li>
            <li>
                <span class="negrita">El calendario laboral de Madrid  capital es el de referencia.</span> Los empleados que trabajan fuera de la Comunidad de Madrid bajo 
                diferente calendario laboral, completarán las jornadas trabajadas al código de proyecto aunque sea festivo en Madrid. Festivos 
                exclusivos  de la provincia o comunidad de trabajo, no trabajados, se dejaran a cero sin horas imputadas.
            </li>
            <li>
                El IMC no debe reportar horas trabajadas sobre jornadas no laborales, por ejemplo, fines de semana. Estos casos serán reportados
                y gestionados con el Gerente responsable, que determinará como proceder.
            </li>
            <li>
                En caso de que el proyecto esté sujeto a un <span class="negrita">calendario especial de cliente</span>, que incluya días no laborales que no son festivos 
                según calendarios oficiales (en ciertas empresas del sector seguros “Día del Seguro”), se imputará al código de proyecto como 
                si se hubiese trabajado.
            </li>
            <li>
                El <span class="negrita">día por cortesía de Keyrus</span>, a elegir entre el 24 y 31 de Diciembre, se pondrá en KEYOTROS atendiendo a las indicaciones 
                especificas de esté caso en el apartado Actividades Especiales. Los consultores que por el calendario del cliente disponen 
                de ambos días como no laborales, asignarán uno a KEYOTROS y el otro lo imputarán al proyecto como si lo hubieran trabajado.
            </li>
        </ul>
	</div>
	
</div>

<div id="dialog3" class="dialog">
	<img title="Cerrar" class="imagen_cierre_popup" src="<?php echo base_url()?>assets/img/cross.png"/>	
	<p class="titulo_ayuda centrado titulo-mediano">AYUDA PROYECTOS ESPECIALES</p>
	<div class="texto_ayuda">	
		<p><span class="negrita">KEYVACACIONES:</span> Ausencias comunicadas y aprobadas por vacaciones personales.</p>
            
            <p><span class="negrita">KEYBAJA:</span> Ausencias por enfermedad desde el primer momento, independientemente de que a partir del 2 día se necesite aportar un parte de baja médica de la Seguridad Social.</p>
            
            <p><span class="negrita">KEYOTROS:</span> Permisos retribuidos según Convenio y ausencias justificadas. Debe rellenarse <span class="negrita">un registro diferente del IMC para cada tipo de permiso. Obligatorio rellenar el campo comentario con un patrón formado por: prefijo predefinido + referencia mínima al motivo (máximo de 40 caracteres).</span> Los permisos responden a los siguientes prefijos predefinidos que deben coincidir literalmente EN MAYUSCULAS Y SIN ACENTOS tal que:</p>
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
	</div>
	
</div>

<div id="sombra"></div>