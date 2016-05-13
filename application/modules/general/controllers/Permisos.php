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
	
	public function solicitar_permiso($k_permiso_solic=0,$year=0)
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
	
	//EDICION DE UNA SOLICITUD
	public function editar_solicitud($k_permiso_solic,$year=0)
	{		
		//$datos['permisos']=$this->Permisos_model->cargar_permisos($k_consultor);
	
		//CAMBIO DEV-PRO	
	
		//AÑO "FISCAL" DE LAS VACACIONES
		$datos['k_permisos_solic']=$k_permiso_solic;
		
		//PONEMOS EL AÑO ACTUAL
		$datos['year_solicitud']=$year;
		if($year==0)
		{
			$datos['year_solicitud']=date('Y');
		}
		
		//COGEMOS TOODOS LOS DATOS DE ESA SOLICITUD
		$todosDatosTabla=$this->Permisos_model->cargar_datos_solicitud_activa($k_permiso_solic);
		
		//CAMPO OBSERVACIONES QUE PINTAMOS CON LOS DATOS QUE TENIA
		$datos['desc_observaciones']=$todosDatosTabla[0]['desc_observaciones'];
		
		//NOS APUNTAMOS SI ES KEYVACACIONES O KEYOTROS
		//NOS PASAMOS EL DATOS CON NOMBRE Y CON CAMPO K
		$datos['k_proyecto_solicitud']=$todosDatosTabla[0]['k_proyecto'];
		$datos['tipo_solicitud']="";
	
		if($datos['k_proyecto_solicitud']=="450")
		{
			$datos['tipo_solicitud']="KEYVACACIONES";
		}
		if($datos['k_proyecto_solicitud']=="468")
		{
			$datos['tipo_solicitud']="KEYOTROS";
		}	
				
		
		//NOS APUNTAMOS EL RESPONSABLE
		$datos['responsable_solicitud']=$todosDatosTabla[0]['k_responsable'];
		
		
		$datos['horas_jornada']=0;
		//cargar horas solicitud, SI ES KEYVACACIONES BUSCAMOS EL VALOR QUE INTRODUJO EN SU DIA
		if($datos['k_proyecto_solicitud']=="450")//KEYVACACIONES
		{
			$datos['horas_jornada']=$this->Permisos_model->cargar_horas_solicitud($k_permiso_solic);
		}
				
		//MIRAMOS SI EXISTE EL CALENDARIO DEL AÑO SIGUIENTE PARA PINTAR EL MES DE ENERO DEL AÑO SIGUIENTE
		$datos['existe_next_year_bbdd']=$this->Permisos_model->comprobar_calendario_proximo_year($datos['year_solicitud']);
		//DIAS FESTIVOS DEL CALENDARIO
		$datos['festivos']=json_encode($this->Permisos_model->cargar_festivos());
		
		//DIAS QUE YA TIENE PEDIDOS
		$k_consultor=$this->session->userdata('k_consultor');
		$datos['diasYaSolicitados']=json_encode($this->Permisos_model->cargar_dias_solicitados($k_consultor));
	
		//CALCULAMOS LOS DIAS QUE LE QUEDAN DE VACACIONES Y TAMBIEN LOS QUE TENIA EN ORIGEN
		$dias_debidos_two_years=$this->Permisos_model->cargar_dias_debidos($k_consultor,$datos['year_solicitud'],$k_permiso_solic);
	
		//DIAS PENDIENTES QUE TIENE
		$datos['diasDebidos']=$dias_debidos_two_years['dias_debidos'];
		$datos['diasDebidosPendientes']=$dias_debidos_two_years['dias_debidos_pendientes'];
		
		//DIAS QUE LE CORRESPONDEN  (ESTO NO ESTA EN EL DE SOLICITAR)
		$datos['dias_base']=$dias_debidos_two_years['dias_base'];
		$datos['dias_base_anterior']=$dias_debidos_two_years['dias_base_anterior'];
		
			
		$datos['js']=['editar_permiso','jquery-ui.multidatespicker'];
		$datos['css']=['jquery-ui-1.10.1','editar_permiso'];
	
		enmarcar($this,'EditarPermiso.php',$datos);
	}
	
	public function grabar_solicitud()
	{		
		//CAMPOS QUE PASAMOS AL MODELO
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
		
		
		//NOS DEVOLVEMOS EL K QUE HEMOS GUARDADO
		$k_permisos_solic=$this->Permisos_model->grabar_solicitud($datos_guardar);
		
		//echo $k_permisos_solic;
		
		//echo "Cambio guardados.";
		
	}
	
	public function grabar_solicitud_editado()
	{
		//CAMPOS QUE PASAMOS AL MODELO
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
		
		//NOS DEVOLVEMOS EL K QUE HEMOS GUARDADO
		$k_permisos_solic=$this->Permisos_model->grabar_solicitud_editado($datos_guardar);
		
		
		//echo $k_permisos_solic;
	
		//echo "Cambio guardados.";	
	}
	
	
	
	public function enviar_solicitud()
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
	
		
		$k_permisos_solic=$this->Permisos_model->grabar_solicitud($datos_guardar);
		
		$this->Permisos_model->enviar_solicitud($k_permisos_solic);
		
		echo "Solicitud enviada.";	
	}
	
	
}