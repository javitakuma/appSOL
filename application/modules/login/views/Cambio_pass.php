
<div id="contenedor_login">   
<form action="<?php echo base_url()?>login/cambio_pass_post" method="post">
<h2 class="centrado titulo-grande-80-20">CAMBIO CONTRASEÑA</h2>
  <div class="group login-div">
  	<p>Usuario</p>
    <input type="text" name="usuario" id="usuario" disabled value="<?php echo $this->session->userdata('id_consultor')?>">    
  </div>
  <div class="group login-div">
    <p>Nueva Contraseña</p>
    <input type="password" name="pass" id="pass">
  </div>
 	<p class="error"><?php echo $errorCambioPassMensaje?></p>
  <input type="submit" class="button buttonGenerico" value="Enviar"/>
  
</form>
</div>

