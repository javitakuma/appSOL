

    <div class="contenedor">
    	
    	<div class="menu_superior_derecha">
    		<span>Usuario activo</span>
	    	<select id="usuarioActivo">
	    		<option value="<?php echo $this->session->userdata('id_consultor')?>"><?php echo $this->session->userdata('id_consultor')?></option>
	    	</select>
    		<button id="cambiarPassword"
				onclick='location.href="<?php echo base_url()?>login/cambiar_pass"'>
				Cambiar	contraseña
			</button>
    	</div>
    	
        <div id="pestanas">
            <ul id=lista>
                <li id="pestana1"><a href='javascript:cambiarPestana(pestanas,pestana1);'>Gestión de proyectos</a></li>
                <li id="pestana2"><a href='javascript:cambiarPestana(pestanas,pestana2);'>RRHH</a></li>
                <li id="pestana3"><a href='javascript:cambiarPestana(pestanas,pestana3);'>3</a></li>
                <li id="pestana4"><a href='javascript:cambiarPestana(pestanas,pestana4);'>4</a></li>
                <li id="pestana5"><a href='javascript:cambiarPestana(pestanas,pestana5);'>General</a></li>              
            </ul>
        </div>        
 
        <div id="contenidopestanas">
            <div id="cpestana1">
                Contenido de la pestaña 1
            </div>
            <div id="cpestana2">
                Contenido de la pestaña 2
            </div>
            <div id="cpestana3">
                Contenido de la pestaña 3
            </div>
            <div id="cpestana4">
                Contenido de la pestaña 4
            </div>
            <div id="cpestana5">
                <ul>
                	<li><a href="<?php base_url()?>vacaciones/index">Vacaciones</a></li>
                	<li><a href="<?php base_url()?>hoja_gastos/index">Hoja de Gastos</a></li>
                	<li><a href="<?php base_url()?>general/Imc/index">IMC</a></li>
                </ul>
            </div>
    	</div>
    </div>
