<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Login</title>    
        <link rel="stylesheet" href="<?php echo base_url()?>assets/css/formulario.css">    
  </head>

  <body>


   
<form action="<?php echo base_url()?>login/indexPost" method="post">
<h2 class="centrado">CAMBIO CONTRASEÑA</h2>
  <div class="group">
  	<p>Usuario</p>
    <input type="text" name="usuario" id="usuario" disabled value="<?php echo $usuario?>">    
  </div>
  <div class="group">
    <p>Nueva Contraseña</p>
    <input type="password" name="pass" id="pass">
  </div>
 	<p class="error"><?php echo $errorLoginMensaje?></p>
  <input type="submit" class="button buttonBlue" value="Entrar"/>
  
</form>

    <!-- <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>-->

        <!-- <script src="js/index.js"></script>    -->
    
  </body>
</html>
