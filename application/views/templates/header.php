</head>
<body>
<div id="cabecera">
	<a href="<?php echo base_url()?>welcome"><img src="<?php echo base_url()?>assets/img/logo_mediano.png"/></a>
	
	<h2 class="centrado titulo-aplicacion">Aplicación WebSOL 2.0</h2>
	
	<?php if($this->session->userdata('logueado')==TRUE):?>
	<img src="<?php echo base_url()?>assets/img/logout.png" class="flotarDerecha logout" id="logout"
				onclick='location.href="<?php echo base_url()?>login/logout"'/>
				
	<?php endif;?>	
</div>



