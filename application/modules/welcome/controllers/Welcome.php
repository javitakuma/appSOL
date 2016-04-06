<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Welcome extends MX_Controller
{
	
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Welcome_model');
		
	}
	
	public function index()
	{	
		//VALIDAMOS SI HAY USUARIO ACTIVO
		if($this->session->userdata('logueado'))
		{
			//MOSTRAMOS LA VISTA DE BIENVENIDA
			enmarcar($this, 'Welcome');				
		}
		//SI NO ESTA LOGUEADO LE MANDAMOS AL LOGIN CON UN CAMPO DE ERROR
		else
		{
			$this->session->set_flashdata('errorLoginMensaje','Por favor inicia sesión');
			header("Location:".base_url().'login');    //TODO
		}		
		
	}	
	
	public function indexPost()
	{
		//$data['users'] = $this->data_users();
		//$this->load->view('Login');
		echo "--";
	}
}