</head>
<body>
<div id="cabecera">
	<a class="logoapp" href="<?php echo base_url()?>welcome"><img width="20%" src="<?php echo base_url()?>assets/img/logo_mediano.png"/></a>
	
	<?php if($this->session->userdata('logueado')==TRUE):?>
	<img width="6%" src="<?php echo base_url()?>assets/img/logout.png" class="flotarDerecha logout" id="logout"
				onclick='location.href="<?php echo base_url()?>login/logout"'/>
				
	<?php endif;?>
	
	<h2 class="centrado titulo-aplicacion">Aplicación WebSOL 2.0</h2>
	
		
</div>



