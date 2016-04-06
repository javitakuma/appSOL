<!--  
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php base_url()?>assets/css/pestanas.css"/>
    <link rel="stylesheet" type="text/css" href="<?php base_url()?>assets/css/estilos.css"/>
    <script type="text/javascript" src="<?php base_url()?>assets/js/cambiarPestana.js"></script>    
    <script type="text/javascript" src="<?php base_url()?>assets/js/jquery-2.1.3.js"></script>
    <title>Bienvenido a appSOL</title>
   
</head>
-->
<body>
    <div class="contenedor">
    	
    	<div class="menu_superior_derecha">
    		<span>Usuario activo</span>
	    	<select id="usuarioActivo">
	    		<option value="<?php echo $this->session->userdata('usuario')?>"><?php echo $this->session->userdata('usuario')?></option>
	    	</select>
    		<button id="cambiarPassword"
				onclick='location.href="<?php echo base_url()?>login/cambiar_pass"'>
				Cambiar	contraseña
			</button>
			<button id="passwordsCliente"
				onclick='location.href="<?php echo base_url()?>Password_en_cliente"'>
				Contraseñas en cliente
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
                	<li><a href="<?php base_url()?>Vacaciones/index.php">Vacaciones</a></li>
                	<li><a href="<?php base_url()?>Hoja_gastos/index.php">Hoja de Gastos</a></li>
                	<li><a href="<?php base_url()?>Imc/index.php">IMC</a></li>
                </ul>
            </div>
    	</div>
    </div>
</body>
</html>