<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Welcome extends MX_Controller
{
	
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Welcome_model');
		
	}
	
	//FUNCION PRINCIPAL INDEX, MOSTRAR MENU
	public function index()
	{	
		$this->general();
		
		/*
		//VALIDAMOS SI HAY USUARIO ACTIVO
		if($this->session->userdata('logueado'))
		{
			//MOSTRAMOS LA VISTA DE BIENVENIDA
			//$datos['js']='sliiide';
			$datos['js']=['sliiide','welcome'];
			$datos['css']='menu';
			enmarcar($this,'General',$datos);				
		}
		//SI NO ESTA LOGUEADO LE MANDAMOS AL LOGIN CON UN CAMPO DE ERROR
		else
		{
			$this->session->set_flashdata('errorLoginMensaje','Por favor inicia sesión');
			header("Location:".base_url().'login');    
		}	
		*/		
	}
	
	public function general()
	{
		//VALIDAMOS SI HAY USUARIO ACTIVO
		if($this->session->userdata('logueado'))
		{
			$k_consultor_original=$this->session->userdata('login_original');			
			
			$PERFIL_JP=$this->session->userdata('PERFIL_JP_original');
			$PERFIL_FINAN=$this->session->userdata('PERFIL_FINAN_original');
			
			$datos['usuarios_perfil']=$this->Welcome_model->cargar_usuarios_perfil($k_consultor_original,$PERFIL_JP,$PERFIL_FINAN);
			
			//MOSTRAMOS LA VISTA DE BIENVENIDA
			$datos['js']=['sliiide','welcome'];
			$datos['css']='menu';
			enmarcar($this,'General',$datos);
		}
		//SI NO ESTA LOGUEADO LE MANDAMOS AL LOGIN CON UN CAMPO DE ERROR
		else
		{
			$this->session->set_flashdata('errorLoginMensaje','Por favor inicia sesión');
			header("Location:".base_url().'login');
		}
	}
	
	public function operaciones()
	{
		//VALIDAMOS SI HAY USUARIO ACTIVO
		if($this->session->userdata('logueado'))
		{
			//MOSTRAMOS LA VISTA DE BIENVENIDA
			enmarcar($this, 'operaciones',$datos);
		}
		//SI NO ESTA LOGUEADO LE MANDAMOS AL LOGIN CON UN CAMPO DE ERROR
		else
		{
			$this->session->set_flashdata('errorLoginMensaje','Por favor inicia sesión');
			header("Location:".base_url().'login');
		}
	}
	
	
}