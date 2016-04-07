<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Imc extends MX_Controller
{
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('Imc_model');
		
	}
	
	public function index($condicion=2)
	{
		$datos['imc_mensuales']=$this->Imc_model->get_imc_mensuales($condicion);
		$datos['js'] ="imc";
		$datos['condicion']=$condicion;
		//$this->Imc_model->cargar_general();
		enmarcar($this,"Imc.php",$datos);
	}
	
	public function mostrarImcMes($year,$month)
	{
		
		$datos['year'] =$year;
		$datos['mes'] =$month;		
		enmarcar($this,"MostrarImcMes.php",$datos);
	}
	
}