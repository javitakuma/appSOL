<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login extends MX_Controller
{
	
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Login_model');	
		//AQUI SE PUEDEN CARGAR LIBRERIAS QUE NO ESTEN AUTOCARGADAS EN AUTOLOAD
	}
	
	//FORMULARIO LOGIN
	public function index()
	{	
		//VALIDAMOS SI HAY USUARIO ACTIVO
		//SI ESTA LOGUEADO LE MANDAMOS AL WELCOME
		if($this->session->userdata('logueado'))
		{
			header("Location:".base_url().'welcome');
				
		}
		//SI NO ESTA LOGUEADO LE MANDAMOS AL LOGIN
		else
		{
			$datos['errorLoginUsuario']=$this->session->flashdata('errorLoginUsuario');
			$datos['errorLoginMensaje']=$this->session->flashdata('errorLoginMensaje');
			enmarcar($this,'Login',$datos);
		}
		
		
	}	
	
	public function indexPost()//FORMULARIO LOGIN POST
	{	
		
		$id=$this->input->post('usuario');
		$pass=$this->input->post('pass');
		
		//COMPROBAMOS EN EL MODELO QUE EXISTE ESE USUARIO
		//$usuarioEncontrado=$this->Login_model->validar_usuario($id,$pass);
		
		//LOGIN CORRECTO   //TODO
		if($this->input->post('usuario')=="admin"&&$this->input->post('pass')=="admin")
		{
			
			
			//INICIALIZACION DE SESIONES
			
			$usuario_data = array(
					'usuario' => $this->input->post('usuario'),//TODO lo que venga del modelo					
					'logueado' => TRUE
			);
			$this->session->set_userdata($usuario_data);
			header("Location:".base_url().'welcome');
			 
		}
		else//LOGIN INCORRECTO
		{
			//GUARDAMOS DOS VALORES EN SESIONES TEMPORALES(SOLO DISPONIBLE EN LA SIGUIENTE PETICION AL SERVIDOR)
			$this->session->set_flashdata('errorLoginUsuario',$this->input->post('usuario'));
			$this->session->set_flashdata('errorLoginMensaje','Usuario o contraseña inválidos');			
			header("Location:".base_url().'login');
		}
	}
	
	public function cambiar_pass()
	{
		//VALIDAMOS SI HAY USUARIO ACTIVO
		if($this->session->userdata('logueado'))
		{
			$datos['usuario']=$this->session->userdata('usuario');
			$datos['errorLoginMensaje']=$this->session->userdata('errorLoginMensaje');
			enmarcar($this,'Cambio_pass.php',$datos);
			//$this->load->view('Cambio_pass',$datos);
			
		}
		//SI NO ESTA LOGUEADO LE MANDAMOS AL LOGIN CON UN CAMPO DE ERROR
		else
		{
			$datos['errorLoginUsuario']='';
			$datos['errorLoginMensaje']='Por favor inicia sesion';
			enmarcar($this,'Login',$datos);    //TODO
		}
		
	}
	
	public function cambioPassPost()
	{
		//TODO 
		$cambioPassword['passwd']=$this->input->post('pass');
		$cambioPassword['usuario']=$this->session->userdata('logueado')?$this->session->userdata('usuario'):NULL;
	}
	
	public function logout()
	{
		//DATOS DE SESION A FALSE Y ADEMAS DESTRUIMOS LA SESION Y LE REENVIAMOS A LA PANTALLA DE LOGIN
		$usuario_data = array(
				'logueado' => FALSE
		);
		$this->session->set_userdata($usuario_data);
		$this->session->sess_destroy();
		header("Location:".base_url().'login');
	}
	
	public function prueba_basedatos()
	{		
		 	
	}
	
}