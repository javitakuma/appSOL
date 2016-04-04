<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Prueba extends MX_Controller
{
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('prueba_model');
		
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
	
}