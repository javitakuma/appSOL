<nav id="menu" class="pushmenu pushmenu-left" role="navigation">
<ul>
	<li><a href="<?php echo base_url()?>welcome/general" title="General">GENERAL</a></li>
	<li><a href="<?php echo base_url()?>welcome/operaciones" title="Operaciones">OPERACIONES</a></li>
	<li><a href="<?php echo base_url()?>welcome/comercial_finanzas" title="Comercial_finanzas">COMERCIAL/FINANZAS</a></li>
	<li><a href="<?php echo base_url()?>welcome/general" title="RRHH">RRHH</a></li>
	<li><a href="<?php echo base_url()?>welcome/general" title="IT">IT</a></li>
</ul>
</nav>       
    <div id="contenedor-menu">
        	        
        
	    
    	<div id="menu_superior_izquierda">
    	
		    	<div id="boton_menu" class="buttonset">
			            <div id="nav_list" class="push buttonset"></div><p id="texto_menu">MENU</p>
			    </div>
		    
		    <div id="caja_usuario_activo">
	    		<span class=textoSmallCaps>Usuario Activo</span>
		    	<select id="usuarioActivo">
		    		<?php foreach ($usuarios_perfil as $usuario):?>
		    			<option value="<?php echo $usuario['k_consultor']?>"><?php echo $usuario['id_consultor']."    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp(".$usuario['nom_consultor'].")"?></option>
		    		<?php endforeach;?>
		    		<!-- 
		    		<option value="<?php echo $this->session->userdata('id_consultor')?>"><?php echo $this->session->userdata('id_consultor')?></option>
		    		 -->
		    	</select>
	    		<button id="cambiarPassword" class="buttonGenericoPeque"
					onclick='location.href="<?php echo base_url()?>login/cambiar_pass"'>
					Cambiar	contraseña
				</button>
			</div>			
    	</div>           
 		
        <div id="contenedor-submenu">
        
	        
        
        	<h2 class="titulo-mediano">General</h2>
        	
            <div id="contenedor-opciones">
            	<div class="columna-menu">
            		<div class="opcion-columna" >
            			<img onclick='location.href="<?php echo base_url()?>general/Imc/index"' src="<?php echo base_url()?>assets/img/imc.png" class="imagen-opcion"/>
            			<p class="nombre-opcion" onclick='location.href="<?php echo base_url()?>general/Imc/index"'>IMC</p>
            		</div>
            	</div>
            	
            	<div class="columna-menu">
            		<div class="opcion-columna" >
            			<img onclick='location.href="<?php echo base_url()?>general/vacaciones/index"' src="<?php echo base_url()?>assets/img/vacaciones.png" class="imagen-opcion"/>
            			<p class="nombre-opcion" onclick='location.href="<?php echo base_url()?>general/Imc/index"'>VACACIONES</p>
            		</div>
            	</div>
            	
            	<div class="columna-menu">
            		<div class="opcion-columna" >
            			<img onclick='location.href="<?php echo base_url()?>general/hoja_gastos/index"' src="<?php echo base_url()?>assets/img/gastos.png" class="imagen-opcion"/>
            			<p class="nombre-opcion" onclick='location.href="<?php echo base_url()?>general/Imc/index"'>GASTOS</p>
            		</div>
            	</div>
            	
            	<!-- 
                <ul>
                	<li><a href="<?php echo base_url()?>vacaciones/index">Vacaciones</a></li>
                	<li><a href="<?php echo base_url()?>hoja_gastos/index">Hoja de Gastos</a></li>
                	<li><a href="<?php echo base_url()?>general/Imc/index">IMC</a></li>
                </ul>
                 -->
            </div>
    	</div>
    </div>
    
    
    <input type=hidden id="k_consultor_activo" value="<?php echo $this->session->userdata('k_consultor')?>"/>
    