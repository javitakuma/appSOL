

<div id="contenedorGastos">

	<div id="div-volver" class="volver">
			<img id="imagen-volver" class="cursor_pointer" src="<?php echo base_url()?>assets/img/back.png" width="4%" onclick='location.href="<?php echo base_url()?>welcome"'/>
			
	</div>
	
	<h1 id="titulo-pagina" class="centrado titulo-grande">HOJAS DE GASTOS</h1>
	<div class="botonera">
		<button id="todosGastos" class="buttonGenericoPeque boton-gastos"
			onclick='location.href="<?php echo base_url()?>general/Gastos/index/1"'>
		Ver todas
		</button>			
		<button id="noPagadasGastos" class="buttonGenericoPeque boton-gastos"
				onclick='location.href="<?php echo base_url()?>general/Gastos/index/2"'>
		Pendiente pago			
		</button>			
		<button id="pagadasGastos" class="buttonGenericoPeque boton-gastos"
				onclick='location.href="<?php echo base_url()?>general/Gastos/index/0"'>
		Pagadas
		</button>			
	</div>			
	
	
		
	<div id="rejillaGastos">
	
	<div id="div_agregar_hoja"><img title="Agregar nueva hoja de gastos" class="imagen_agregar_hoja" src="<?php echo base_url()?>assets/img/green_cross_2.png"/><p class="titulo-peque">Agregar nueva hoja de gastos</p></div>
		<table id="listadoGastosGeneral">
			<tr id="fila-titulos">
				<th id="abrir_titulo">ABRIR</th>
				<th id="anyo_titulo">AÑO</th>
				<th id="mes_titulo">MES</th>
				<th id="total_titulo">TOTAL</th>
				<th id="pendientes_titulo">PEND. REVISIÓN</th>
				<th id="autorizados_titulo">AUTORIZADOS</th>
				<th id="no_autorizados_titulo">NO AUTORIZ</th>	
				<th id="total_pagado_titulo">TOTAL PAGADO</th>		
				<th id="ultima_fecha_titulo">ULT. FECHA PAGO</th>		
				<th id="enviada_titulo">ENVIADA</th>					
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

<!-- VENTANA EMERGENTE -->

<div id="dialog">
	<img title="Cerrar" id="imagen_cierre_popup" src="<?php echo base_url()?>assets/img/cross.png"/>
	<p class="centrado titulo-mediano">Selecciona un mes</p>
	<form action="<?php echo base_url()?>general/Gastos/generar_nueva_hoja_gastos" method="post">
	<div id="seleccion_mes">
		<label class="titulo-peque">Mes:</label>
		<input id="mes_seleccion" name="mes_seleccion" maxlength="2" type="text">
	</div>
	<div id="seleccion_year">
		<label class="titulo-peque">Año:</label>
		<input id="year_seleccion" name="year_seleccion" type="text">
	</div>
		
	</form>
	<button id="boton_selecciones_hoja" class="centrado buttonGenericoPeque">Crear</button>
</div>

<div id="sombra"></div>

<input type="hidden" id="condicion" value="<?php echo $condicion?>"/>
