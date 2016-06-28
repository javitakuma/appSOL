<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Prueba extends MX_Controller
{
	
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Prueba_model');
		
	}
	
	public function index()
	{
		
		$data['users'] = $this->data_users();
		$this->load->view('prueba',$data);
			
	}
	
	public function data_users()
	{
		
		return $this->prueba_model->get_users();
		
	}
	
	public function saludo($saludo)
	{
		
		echo 'esto es un '. $saludo;
		
	}
	
	public function curriculum_reducido()
	{
		/*
		$datos['css']='curriculum_reducido';
		enmarcar($this,'Curriculum_reducido',$datos);
		*/
		$data=array();
		$this->load->view('Curriculum_reducido',$data);
		
	}
	
	public function vacaciones()
	{		
		$this->Prueba_model->vacaciones();	
	}
	
	public function trasponer_fechas_alta_baja_pl()
	{
		$this->Prueba_model->trasponer_fechas_alta_baja_pl();
	}
	
	public function trasponer_fechas_alta_baja()
	{
		$this->Prueba_model->trasponer_fechas_alta_baja();
	}
	
	public function obtener_k_consultor()
	{
		
	}
	
	
	
}