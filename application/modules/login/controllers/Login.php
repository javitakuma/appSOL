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
		//CONVERTIMOS EL USUARIO A MAYUSCULAS, PUES ASI ESTA EN LA BASE DE DATOS.
		$id=mb_strtoupper($this->input->post('usuario'));
		$pass=$this->input->post('pass');
		
		//COMPROBAMOS EN EL MODELO QUE EXISTE ESE USUARIO
		$usuarioEncontrado=$this->Login_model->validar_usuario($id,$pass);
				
		//LOGIN CORRECTO   //TODO
		if($usuarioEncontrado!=FALSE)
		{
			
			
			//INICIALIZACION DE SESIONES
			
			$usuario_data = array(
					'k_consultor'=> $usuarioEncontrado['k_consultor'],
					'id_consultor'=> $usuarioEncontrado['id_consultor'],
					'nom_consultor'=> $usuarioEncontrado['nom_consultor'],
					'sw_baja'=> $usuarioEncontrado['sw_baja'],
					'sw_resp_proyectos'=> $usuarioEncontrado['sw_resp_proyectos'],
					'sw_administrador_petra'=> $usuarioEncontrado['sw_administrador_petra'],
					'sw_administracion'=> $usuarioEncontrado['sw_administracion'],
					'sw_comercial'=> $usuarioEncontrado['sw_comercial'],
					'sw_consultor'=> $usuarioEncontrado['sw_consultor'],
					'sw_rrhh'=> $usuarioEncontrado['sw_rrhh'],
					'sw_imc_sol'=> $usuarioEncontrado['sw_rrhh'],					
					'logueado' => TRUE
			);
			$this->session->set_userdata($usuario_data);
			//REDIRECCIONAMOS A LA PANTALLA DE BIENVENIDA
			header("Location:".base_url().'welcome');
			 
		}
		else//LOGIN INCORRECTO, REDIRECCIONAMOS A LOGIN CON ERRORES
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
			$datos['errorCambioPassUsuario']=$this->session->flashdata('errorCambioPassUsuario');
			$datos['errorCambioPassMensaje']=$this->session->flashdata('errorCambioPassMensaje');
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
	
	public function cambio_pass_post()
	{
		//TODO 
		$nuevo_password=$this->input->post('pass');
		//RECOGEMOS AQUI LOS DATOS DEL SESION PORQUE DESDE EL MODELO NO SON ACCESIBLES
		$k_consultor=$this->session->userdata('logueado')?$this->session->userdata('k_consultor'):NULL;
		$Password_cambiado=$this->Login_model->cambiar_password($nuevo_password,$k_consultor);
		
		if($Password_cambiado)
		{
			enmarcar($this,'Cambio_pass_post.php');
		}
		else
		{
			$this->session->set_flashdata('errorCambioPassUsuario',$this->session->userdata('k_consultor'));
			$this->session->set_flashdata('errorCambioPassMensaje','No ha sido posible cambiar la contraseña, inténtelo más tarde o comunique con el administrador');
			header("Location:".base_url().'login/cambiar_pass');
		}
		
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
	
	
}