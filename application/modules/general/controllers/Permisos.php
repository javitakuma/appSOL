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
		
		$datos['resp_proyectos']=$this->Permisos_model->cargar_responsables_proyectos();
		$datos['year_actual']=date('Y');
		$datos['css']='permisos';
		$datos['js']='permisos';
		
		$datos['tipo_permisos']=$this->Permisos_model->cargar_tipo_permisos();
		
		$datos['year_solicitud']=date('Y');
		$dias_debidos_two_years=$this->Permisos_model->cargar_dias_debidos($k_consultor,$datos['year_solicitud']);		
		$datos['diasDebidos']=$dias_debidos_two_years['dias_debidos'];
		$datos['diasDebidosPendientes']=$dias_debidos_two_years['dias_debidos_pendientes'];
		
		$datos['historico_permisos']=$this->Permisos_model->cargar_historico_permisos($k_consultor);	
		
		
		enmarcar($this,'Permisos.php',$datos);
	}
	
	public function solicitar_permiso($year=0)
	{
		//ir al modelo para sacar los dias pendientes de disfrutar
		//$datos['permisos']=$this->Permisos_model->cargar_permisos($k_consultor);
		
		//CAMBIO DEV-PRO
		
		
		//AÑO "FISCAL" DE LAS VACACIONES
		$datos['year_solicitud']=$year;		
		if($year==0)
		{
			$datos['year_solicitud']=date('Y');
		}		
		
		//NOS APUNTAMOS SI ES KEYVACACIONES O KEYOTROS
		$datos['tipo_solicitud']="";
		
		if($_REQUEST['tipo_solicitud']=="450")
		{
			$datos['tipo_solicitud']="KEYVACACIONES";
		}
		if($_REQUEST['tipo_solicitud']=="468")
		{
			$datos['tipo_solicitud']="KEYOTROS";
		}
		
		$datos['k_proyecto_solicitud']=$_REQUEST['tipo_solicitud'];
		
		//NOS APUNTAMOS EL RESPONSABLE
		$datos['responsable_solicitud']=$_REQUEST['responsable_solicitud'];
		
		$datos['horas_jornada']=isset($_REQUEST['horas_jornada'])?$_REQUEST['horas_jornada']:0;
				
		$datos['existe_next_year_bbdd']=$this->Permisos_model->comprobar_calendario_proximo_year($datos['year_solicitud']);
		
		$datos['festivos']=json_encode($this->Permisos_model->cargar_festivos());
				
		$k_consultor=$this->session->userdata('k_consultor');
		$datos['diasYaSolicitados']=json_encode($this->Permisos_model->cargar_dias_solicitados($k_consultor));
				
		$dias_debidos_two_years=$this->Permisos_model->cargar_dias_debidos($k_consultor,$datos['year_solicitud']);
		
		$datos['diasDebidos']=$dias_debidos_two_years['dias_debidos'];
		$datos['diasDebidosPendientes']=$dias_debidos_two_years['dias_debidos_pendientes'];
				
		$datos['js']=['solicitar_permiso','jquery-ui.multidatespicker'];
		$datos['css']=['jquery-ui-1.10.1','solicitar_permiso'];
		
		enmarcar($this,'SolicitarPermiso.php',$datos);
	}
	
	public function grabar_solicitud()
	{		
		
		$datos_guardar['observaciones']=$_REQUEST['observaciones'];
		$datos_guardar['responsable_solicitud']=$_REQUEST['responsable_solicitud'];
		$datos_guardar['diasPendientesDebidos']=$_REQUEST['diasPendientesDebidos'];
		$datos_guardar['diasPendientes']=$_REQUEST['diasPendientes'];
		$datos_guardar['dias_solicitados']=$_REQUEST['dias_solicitados'];
		$datos_guardar['horas_por_dias']=isset($_REQUEST['horas_por_dias'])?$_REQUEST['horas_por_dias']:"";
		$datos_guardar['horas_jornada']=isset($_REQUEST['horas_jornada'])?$_REQUEST['horas_jornada']:"";
		$datos_guardar['year_solicitud']=$_REQUEST['year_solicitud'];
		$datos_guardar['k_permisos_solic']=$_REQUEST['k_permisos_solic'];
		$datos_guardar['k_proyecto_solicitud']=$_REQUEST['k_proyecto_solicitud'];
		
		$datos_guardar['k_consultor']=$this->session->userdata('k_consultor');
		$datos_guardar['id_consultor']=$this->session->userdata('id_consultor');
		$datos_guardar['k_consultor_solic']=$this->session->userdata('login_original');
		
		
		//var_dump($datos_guardar);
		
		$this->Permisos_model->grabar_solicitud($datos_guardar);
		
	}
	
	
}