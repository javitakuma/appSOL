<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Permisos extends MX_Controller
{
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('Permisos_model');
		
	}	
	
	public function index()
	{
		$this->mostrar_permiso_anual();
	}
	
	public function mostrar_permisos_general()
	{
		
	}
	
	public function cargar_dias_para_horas()
	{
		
		$fechaActualFormateada=$_POST['fechaInicioYearFormateada'];
		
		$fechas=$this->Permisos_model->cargar_dias_para_horas($fechaActualFormateada);
		print json_encode($fechas);
	}
	
	
	public function cargar_dias_solicitados()
	{
	
		$k_consultor=$this->session->userdata('k_consultor');
	
		$fechas=$this->Permisos_model->cargar_dias_solicitados($k_consultor);
		print json_encode($fechas);
	}
	
	public function mostrar_permiso_anual() 
	{
		$k_consultor=$this->session->userdata('k_consultor');
		$datos['css']='permisos';
		$datos['js']='permisos';
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
		
		$dias=[
				['dia'=>1,'mes'=>6,'año'=>2016,'sw_aprobacion_N1'=>-1,'sw_aprobacion_N2'=>-1,'sw_rechazo'=>0],
				['dia'=>2,'mes'=>6,'año'=>2016,'sw_aprobacion_N1'=>-1,'sw_aprobacion_N2'=>-1,'sw_rechazo'=>0],
				['dia'=>3,'mes'=>6,'año'=>2016,'sw_aprobacion_N1'=>-1,'sw_aprobacion_N2'=>-1,'sw_rechazo'=>0],
				['dia'=>1,'mes'=>7,'año'=>2016,'sw_aprobacion_N1'=>0,'sw_aprobacion_N2'=>0,'sw_rechazo'=>-1],
				['dia'=>2,'mes'=>7,'año'=>2016,'sw_aprobacion_N1'=>0,'sw_aprobacion_N2'=>0,'sw_rechazo'=>-1],
				['dia'=>1,'mes'=>8,'año'=>2016,'sw_aprobacion_N1'=>0,'sw_aprobacion_N2'=>0,'sw_rechazo'=>0],
				['dia'=>2,'mes'=>8,'año'=>2016,'sw_aprobacion_N1'=>0,'sw_aprobacion_N2'=>0,'sw_rechazo'=>0],
		];
		
		$datos['dias']=json_encode($dias);
		
		
		$k_consultor=$this->session->userdata('k_consultor');
		$datos['diasYaSolicitados']=json_encode($this->Permisos_model->cargar_dias_solicitados($k_consultor));
		
		
		/*CREO QUE NO LO USARE
		$fechaActual=new DateTime();		
		$fechaActualFormateada=date_format($fechaActual, 'Y-m-d');			
		$datos['fechas']=$this->Permisos_model->cargar_dias_para_horas($fechaActualFormateada);
		var_dump($datos['fechas']);
		die;
		*/		
		
		$datos['js']=['solicitar_permiso','jquery-ui.multidatespicker'];
		$datos['css']=['jquery-ui-1.10.1','solicitar_permiso'];
		
		enmarcar($this,'SolicitarPermiso.php',$datos);
	}
	
	
}