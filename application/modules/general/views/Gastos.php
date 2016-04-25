

<div id="contenedorGastos">

	<div class="volver">
			<img class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>welcome"'/>
			
	</div>
	
	<h1 class="centrado titulo-grande">HOJAS DE GASTOS</h1>
	<div class="botonera">
		<button id="todosGastos" class="buttonGenericoPeque boton-gastos"
			onclick='location.href="<?php echo base_url()?>general/Gastos/index/1"'>
		Ver todas
		</button>			
		<button id="todosGastos" class="buttonGenericoPeque boton-gastos"
				onclick='location.href="<?php echo base_url()?>general/Gastos/index/2"'>
		Pendiente pago			
		</button>			
		<button id="todosGastos" class="buttonGenericoPeque boton-gastos"
				onclick='location.href="<?php echo base_url()?>general/Gastos/index/0"'>
		Pagadas
		</button>			
	</div>			
	
	
		
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
					<td class="abrirCell" onclick='location.href="<?php echo base_url()?>general/Gastos/mostrar_gastos_mes/<?php echo $fila['f_año_hoja_gastos']?>/<?php echo $fila['f_mes_hoja_gastos']?>"'>Abrir</td>
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



