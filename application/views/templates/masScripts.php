 
<?php if(is_array($js)):?>
	<?php foreach ($js as $fichero_js):?>
		<script src="<?php echo base_url()."assets/js/".$fichero_js.".js"?>"></script>
	<?php endforeach;?>
<?php endif;?>

<?php if(!is_array($js)):?>	
		<script src="<?php echo base_url()."assets/js/".$js.".js"?>"></script>	
<?php endif;?>



