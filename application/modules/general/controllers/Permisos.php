<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Permisos extends MX_Controller
{
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('Permisos_model');
		
	}	
	
	
	public function mostrar_permisos_general()
	{
		
	}
	
	public function mostrar_permiso_anual() 
	{
		$k_consultor=$this->session->userdata('k_consultor');
		$datos['css']='permisos';
		$datos['permisos']=$this->Permisos_model->cargar_permisos($k_consultor);
		//var_dump($datos['permisos']);
		enmarcar($this,'Permisos.php',$datos);
	}
	
	public function solicitar_permiso()
	{
		//ir al modelo para sacar los dias pendientes de disfrutar
		//ir al calendario para pintar los festivos
		//$datos['permisos']=$this->Permisos_model->cargar_permisos($k_consultor);
		$datos['festivos']=json_encode($this->Permisos_model->cargar_festivos());
		
		$datos['js']=['solicitar_permiso','jquery-ui.multidatespicker'];
		$datos['css']=['jquery-ui-1.10.1','solicitar_permiso'];
		
		
		enmarcar($this,'SolicitarPermiso.php',$datos);
	}
	
	
}