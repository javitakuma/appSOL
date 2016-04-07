<h1 class="centrado titulo-grande">IMC</h1>

<div id="contenedorImc">
	<div class="botonera">
		<button id="todosImc" class="boton-imc"
			onclick='location.href="<?php echo base_url()?>general/Imc/index/3"'>
		Todos
		</button>			
		<button id="todosImc" class="boton-imc"
				onclick='location.href="<?php echo base_url()?>general/Imc/index/1"'>
		Enviados			
		</button>			
		<button id="todosImc" class="boton-imc"
				onclick='location.href="<?php echo base_url()?>general/Imc/index/2"'>
		No enviados
		</button>			
	</div>			
	
	
		
	<div id="rejillaImc">
	<h3 class="centrado titulo-mediano"><?php echo $condicion==1?'Enviados.':($condicion==2?'No enviados.':'Todos.')?></h3>
		<table id="listadoImcGeneral">
			<tr>
				<th>ABRIR</th>
				<th>CONSULTOR</th>
				<th>AÑO IMC</th>
				<th>MES IMC</th>
				<th>HORAS</th>
				<th>HORAS VAL.</th>
				<th>ENVIADO</th>				
			</tr>			
			
			<?php foreach($imc_mensuales as $fila):?>
				<tr>
					<td class="abrirCell" onclick='location.href="<?php echo base_url()?>general/Imc/mostrarImcMes/<?php echo $fila['year_imc']?>/<?php echo $fila['mes_imc']?>"'>Abrir</td>
					<td class="consultorCell"><?php echo $fila['k_consultor']?></td>
					<td class="imcYearCell"><?php echo $fila['year_imc']?></td>
					<td class="imcMonthCell"><?php echo $fila['mes_imc']?></td>
					<td class="horas"><?php echo $fila['i_tot_horas_imc']?></td>
					<td class="horasVal"><?php echo $fila['i_tot_horas_imc_validadas']?></td>
					<td class="enviado"><?php echo $fila['sw_validacion']?></td>
				</tr>	
			<?php endforeach;?>
			 
			 
			
			<!-- 
			<tr>
				<td id="kk"class="abrirCell">aa</td>
				<td class="consultorCell"></td>
				<td class="imcYearCell">2016</td>
				<td class="imcMonthCell">05</td>
				<td class="horas"></td>
				<td class="horasVal"></td>
				<td class="enviado"></td>
			</tr>
			 -->
		</table>
		<img class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="50px" onclick='location.href="<?php echo base_url()?>welcome"'/>
		<h3 class="titulo-peque">Volver al menú principal.</h3> 
	</div>
</div>

