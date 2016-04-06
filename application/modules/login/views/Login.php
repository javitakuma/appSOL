
  
<form action="<?php echo base_url()?>login/indexPost" method="post">
<h2 class="centrado">LOGIN</h2>
  <div class="group">
  	<p>Usuario</p>
    <input type="text" name="usuario" id="usuario" value="<?php echo $errorLoginUsuario?>">    
  </div>
  <div class="group">
    <p>Contraseña</p>
    <input type="password" name="pass" id="pass">
  </div>
 	<p class="error"><?php echo $errorLoginMensaje?></p>
  <input type="submit" class="button buttonBlue" value="Entrar"/>
  
</form>
   
    
  </body>
</html>
