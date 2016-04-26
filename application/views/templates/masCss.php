


<?php if(is_array($css)):?>
	<?php foreach ($css as $fichero_css):?>
		<link rel="stylesheet" href="<?php echo base_url()."assets/css/".$fichero_css.".css"?>">		
	<?php endforeach;?>
<?php endif;?>

<?php if(!is_array($css)):?>	
		<link rel="stylesheet" href="<?php echo base_url()."assets/css/".$css.".css"?>">
<?php endif;?>

