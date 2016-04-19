
<div id="contenedor_login">
<form action="<?php echo base_url()?>login/indexPost" method="post">
<h2 class="centrado titulo-grande-80-20">LOGIN</h2>
  <div class="group login-div">
  	<p>Usuario</p>
    <input type="text" name="usuario" id="usuario" value="<?php echo $errorLoginUsuario?>">    
  </div>
  <div class="group login-div">
    <p>Contraseña</p>
    <input type="password" name="pass" id="pass">
  </div>
 	<p class="error"><?php echo $errorLoginMensaje?></p>
  <input type="submit" class="button buttonGenerico" value="Entrar"/>
  
</form>
</div>     
    
 