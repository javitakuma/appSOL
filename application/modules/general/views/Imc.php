<h1 class="centrado titulo-grande">IMC</h1>

<div id="contenedorImc">
		<div class="botonera">
			<button id="todosImc" class="boton-imc"
				onclick='imcMostrar("todos")'>
				Todos
				</button>
			
			
				<button id="todosImc" class="boton-imc"
						onclick='imcMostrar("enviados")'>
						Enviados			
				</button>
			
			
				<button id="todosImc" class="boton-imc"
						onclick='imcMostrar("noEnviados")'>
						No enviados
				</button>
			
		</div>				
	<div id="rejillaImc">
		<table id="listadoImcGeneral">
			<tr>
				<th>Abrir</th>
				<th>Consultor</th>
				<th>Año IMC</th>
				<th>Mes IMC</th>
				<th>Horas</th>
				<th>Horas Val.</th>
				<th>Enviado</th>
				
				
			</tr>
			
			<?php foreach ($filas as $fila):?>
				<tr>
					<td class="abrirCell" onclick="mostrarImcMes()">aa</td>
					<td class="consultorCell"></td>
					<td class="imcYearCell"></td>
					<td class="imcMonthCell"></td>
					<td class="horas"></td>
					<td class="horasVal"></td>
					<td class="enviado"></td>
			</tr>	
			<?php endforeach;?>
			
			
			<tr>
				<td class="abrirCell" onclick="mostrarImcMes()">aa</td>
				<td class="consultorCell"></td>
				<td class="imcYearCell"></td>
				<td class="imcMonthCell"></td>
				<td class="horas"></td>
				<td class="horasVal"></td>
				<td class="enviado"></td>
			</tr>
		</table>
	</div>
</div>

