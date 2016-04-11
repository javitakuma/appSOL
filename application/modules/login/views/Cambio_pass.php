


   
<form action="<?php echo base_url()?>login/cambio_pass_post" method="post">
<h2 class="centrado titulo-mediano">CAMBIO CONTRASEÑA</h2>
  <div class="group login-div">
  	<p>Usuario</p>
    <input type="text" name="usuario" id="usuario" disabled value="<?php echo $this->session->userdata('id_consultor')?>">    
  </div>
  <div class="group login-div">
    <p>Nueva Contraseña</p>
    <input type="password" name="pass" id="pass">
  </div>
 	<p class="error"><?php echo $errorCambioPassMensaje?></p>
  <input type="submit" class="button buttonBlue" value="Entrar"/>
  
</form>

    <!-- <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>-->

        <!-- <script src="js/index.js"></script>    -->

