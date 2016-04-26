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
			$datos['css']=['formulario'];
			enmarcar($this,'Login',$datos);
		}	
		
	}	
	
	public function indexPost()//FORMULARIO LOGIN POST
	{	
		echo getenv('DB_DBNAME'),
		//CONVERTIMOS EL USUARIO A MAYUSCULAS, PUES ASI ESTA EN LA BASE DE DATOS.
		$id=mb_strtoupper($this->input->post('usuario'));
		$pass=$this->input->post('pass');
		
		//COMPROBAMOS EN EL MODELO QUE EXISTE ESE USUARIO
		$usuarioEncontrado=$this->Login_model->validar_usuario($id,$pass);
				
		//LOGIN CORRECTO   //TODO
		if($usuarioEncontrado!=FALSE)
		{			
			//INICIALIZACION DE SESIONES(GUARDAMOS VARIOS CON COLETILLA _ORIGINAL QUE SON LOS PERMISOS DEL USUARIO QUE HIZO LOGIN ORIGINAL)
			//SI AÑADIMOS O QUITAMOS ALGUNO HAREMOS LO MISMO EN LA FUNCION CAMBIAR USUARIO
			
			$usuario_data = array(
					'k_consultor'=> $usuarioEncontrado['k_consultor'],
					'id_consultor'=> $usuarioEncontrado['id_consultor'],
					'nom_consultor'=> $usuarioEncontrado['nom_consultor'],
					'sw_baja'=> $usuarioEncontrado['sw_baja'],
					'PERFIL_JP'=> $usuarioEncontrado['sw_resp_proyectos'],
					'PERFIL_JP_original'=> $usuarioEncontrado['sw_resp_proyectos'],
					'PERFIL_ADMIN'=> $usuarioEncontrado['sw_administrador_petra'],
					'PERFIL_FINAN'=> $usuarioEncontrado['sw_administracion'],
					'PERFIL_FINAN_original'=> $usuarioEncontrado['sw_administracion'],
					'PERFIL_COMERCIAL'=> $usuarioEncontrado['sw_comercial'],
					'PERFIL_CONSULTOR'=> $usuarioEncontrado['sw_consultor'],
					'PERFIL_RRHH'=> $usuarioEncontrado['sw_rrhh'],
					'PERFIL_IMC'=> $usuarioEncontrado['sw_imc_sol'],	
					'login_original'=> $usuarioEncontrado['k_consultor'],
					'logueado' => TRUE
			);
			$this->session->set_userdata($usuario_data);
			//REDIRECCIONAMOS A LA PANTALLA DE BIENVENIDA
			header("Location:".base_url().'welcome/general');
			 
		}
		else//LOGIN INCORRECTO, REDIRECCIONAMOS A LOGIN CON ERRORES
		{
			//GUARDAMOS DOS VALORES EN SESIONES TEMPORALES(SOLO DISPONIBLE EN LA SIGUIENTE PETICION AL SERVIDOR)
			$this->session->set_flashdata('errorLoginUsuario',$this->input->post('usuario'));
			$this->session->set_flashdata('errorLoginMensaje','Usuario o contraseña inválidos');			
			header("Location:".base_url().'login');
		}
	}
	
	public function sesiones()
	{
		echo $this->session->userdata('k_consultor');
	}
	
	public function cambiar_usuario()
	{
		$id_nuevo_usuario=$_REQUEST['nuevo_usuario'];
		
		
		$k_consultor=$this->session->userdata('login_original');
		$PERFIL_JP=$this->session->userdata('PERFIL_JP_original');
		$PERFIL_FINAN=$this->session->userdata('PERFIL_FINAN_original');
		
		
		$usuarios_perfil=$this->Login_model->cargar_usuarios_perfil_solo_key($k_consultor,$PERFIL_JP,$PERFIL_FINAN);
		
		$cambio_valido=false;
		
		//VALIDAMOS POR SEGURIDAD QUE EL USUARIO TENGA ACCESO A ESE PERFIL
		foreach ($usuarios_perfil as $usuario)
		{
			if($usuario['k_consultor']==$id_nuevo_usuario)
			{
				$cambio_valido=true;	
			}
			
		}
		
		if($cambio_valido)
		{			
			$usuarioEncontradoCambiar=$this->Login_model->cambiar_usuario($id_nuevo_usuario);			
			
			
			//CUALQUIER CAMBIO DE SESIONES QUE SE HAGA EN INDEXPOST DEBERA REFLEJARSE AQUI
			
			$this->session->set_userdata('k_consultor',$usuarioEncontradoCambiar['k_consultor']);
			$this->session->set_userdata('id_consultor',$usuarioEncontradoCambiar['id_consultor']);
			$this->session->set_userdata('nom_consultor',$usuarioEncontradoCambiar['nom_consultor']);
			$this->session->set_userdata('sw_baja',$usuarioEncontradoCambiar['sw_baja']);
			$this->session->set_userdata('PERFIL_JP',$usuarioEncontradoCambiar['sw_resp_proyectos']);
			$this->session->set_userdata('PERFIL_JP_original',$this->session->userdata('PERFIL_JP_original'));
			$this->session->set_userdata('PERFIL_ADMIN',$usuarioEncontradoCambiar['sw_administrador_petra']);
			$this->session->set_userdata('PERFIL_FINAN',$usuarioEncontradoCambiar['sw_administracion']);
			$this->session->set_userdata('PERFIL_FINAN_original',$this->session->userdata('PERFIL_FINAN_original'));
			$this->session->set_userdata('PERFIL_COMERCIAL',$usuarioEncontradoCambiar['sw_comercial']);
			$this->session->set_userdata('PERFIL_CONSULTOR',$usuarioEncontradoCambiar['sw_consultor']);
			$this->session->set_userdata('PERFIL_RRHH',$usuarioEncontradoCambiar['sw_rrhh']);
			$this->session->set_userdata('PERFIL_IMC',$usuarioEncontradoCambiar['sw_imc_sol']);
			$this->session->set_userdata('login_original',$this->session->userdata('login_original'));
			$this->session->set_userdata('logueado',$this->session->userdata('logueado'));		
		}
		
		else
		{
			//echo "cambio usuario no posible";
		}
		
	}
	
	
	public function cambiar_pass()
	{
		//VALIDAMOS SI HAY USUARIO ACTIVO
		if($this->session->userdata('logueado'))
		{
			$datos['errorCambioPassUsuario']=$this->session->flashdata('errorCambioPassUsuario');
			$datos['errorCambioPassMensaje']=$this->session->flashdata('errorCambioPassMensaje');
			$datos['css']=['formulario'];
			enmarcar($this,'Cambio_pass.php',$datos);
			//$this->load->view('Cambio_pass',$datos);
			
		}
		//SI NO ESTA LOGUEADO LE MANDAMOS AL LOGIN CON UN CAMPO DE ERROR
		else
		{
			$datos['errorLoginUsuario']='';
			$datos['errorLoginMensaje']='Por favor inicia sesion';
			$datos['css']=['formulario'];
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