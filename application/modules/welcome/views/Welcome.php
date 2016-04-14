    <div class="contenedor-menu">
    	
    	<div class="menu_superior_izquierda">
    		<span>Usuario activo</span>
	    	<select id="usuarioActivo">
	    		<option value="<?php echo $this->session->userdata('id_consultor')?>"><?php echo $this->session->userdata('id_consultor')?></option>
	    	</select>
    		<button id="cambiarPassword"
				onclick='location.href="<?php echo base_url()?>login/cambiar_pass"'>
				Cambiar	contraseña
			</button>
    	</div>             
 
        <div id="contenedor-opciones">
            <div id="cpestana1" class="opcion_sol">
                <ul>
                	<li><a href="<?php base_url()?>vacaciones/index">Vacaciones</a></li>
                	<li><a href="<?php base_url()?>hoja_gastos/index">Hoja de Gastos</a></li>
                	<li><a href="<?php base_url()?>general/Imc/index">IMC</a></li>
                </ul>
            </div>
            <div id="cpestana2" class="opcion_sol">
                Contenido de la pestaña 2
            </div>
            <div id="cpestana3" class="opcion_sol">
                Contenido de la pestaña 3
            </div>
            <div id="cpestana4" class="opcion_sol">
                Contenido de la pestaña 4
            </div>
            <div id="cpestana5" class="opcion_sol">
                
            </div>
    	</div>
    </div>
