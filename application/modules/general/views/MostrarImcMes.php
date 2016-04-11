
        
        <h1>IMC MES <?php echo $mes." de ".$year?></h1>
        <h2>Agregar un proyecto a la tabla</h2>
        <div id="imc_mensual">
        <div id="superior_izquierda">
            Tipo de proyecto
            <select>
                <option>Selecciona una opcion</option>
            </select><br/><br/>
            
            Código de proyecto
            <select>
                <option>Selecciona un proyecto</option>
            </select><br/><br/>
            
            
            <input type="button" id="agregar_proyecto" value="Agregar proyecto"/>
            <br/><br/>    
        </div>       
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
                <tr class="<?php echo ($linea_imc['sw_proy_especial']==-1?'especial':(($linea_imc['sw_interno']==-1)?'interno':'externo'))?>">
                    <td class="<?php echo $linea_imc['k_linea_imc']?> color_proy"><?php echo $linea_imc['id_proyecto']?></td>
                    
                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
                    	<td class="dia<?php echo $i<10?'0'.$i:$i?>"><input type="text" class="input_horas" value="<?php echo $i<10?$linea_imc["i_horas_0$i"]:$linea_imc["i_horas_$i"]?>"/></td>	
                    <?php endfor;?>     
                    
                                       
                    <td class="total_horas_imc color_proy"><?php echo $linea_imc['i_tot_horas_linea_imc']?></td>
                    <td class="comentarios"><textarea><?php echo $linea_imc['desc_comentarios']?></textarea></td>
                    <td class="borde_invisible no_fondo"><input type="button" value="Eliminar fila"/></td>
                </tr>	
                <?php endforeach;?>      
                
                
                
                <tr>
                    <td>TOTAL</td>                    
                    <?php for ($i=1;$i<=$datos_imc_mes['dias_por_mes'];$i++):?>
                    	<td id="total<?php echo $i<10?'0'.$i:$i?>"></td>	
                    <?php endfor;?> 
                    <td id="horas_totales"></td>
                </tr>
                
            </table>
            <br/><br/>
            <input id="grabar" type="button" value="Grabar datos"/>
            <br/><br/>
            <table border="1">
                <tr>
                    <th>Horas consultor</th><th>Horas previstas</th><th>Jornadas totales</th>
                </tr>
                <tr>
                    <td id="horas_consultor"></td><td><?php echo $datos_imc_mes['dias_laborables_por_mes']*8?></td><td><?php echo $datos_imc_mes['dias_laborables_por_mes']?></td>
                </tr>
                
            </table>
            
        </div><!-- CIERRE INFERIOR -->
        </div><!-- CIERRE IMC_MENSUAL -->
        
