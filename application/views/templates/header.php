</head>
<body>
<div id="cabecera">
	<p>AQUI IRA EL LOGO</p>
	
	<?php if($this->session->userdata('logueado')==TRUE):?>
	<button class="flotarDerecha logout" id="logout"
				onclick='location.href="<?php echo base_url()?>login/logout"'>
				Logout
			</button>
	<?php endif;?>
	
	
</div>



