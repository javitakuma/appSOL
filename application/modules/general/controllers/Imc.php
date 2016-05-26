<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Imc extends MX_Controller
{
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('Imc_model');
		
	}
	
	public function index($condicion=2)
	{
		$k_consultor=$this->session->userdata('k_consultor');
		$datos['imc_mensuales']=$this->Imc_model->get_imc_mensuales($condicion,$k_consultor);
		$datos['js'] ="imc";
		$datos['css'] ="imc";
		$datos['condicion']=$condicion;
		//$this->Imc_model->cargar_general();
		enmarcar($this,"Imc.php",$datos);
	}
	
	public function cargar_permisos_para_imc()
	{
		$year=$_REQUEST['year'];
		$month=$_REQUEST['mes'];
		
		
		$this->load->model('general/Permisos_model');
		$datos_permisos_para_imc=$this->Permisos_model->cargar_datos_para_imc($this->session->userdata('k_consultor'),$year,$month);
		
		print json_encode($datos_permisos_para_imc);
	}
	
	public function mostrarImcMes($year,$month)
	{		
		$datos['year'] =$year;
		$datos['mes'] =$month;
		
		
		$datos['mes_texto'] =$this->mesTexto($month);
		
		
		$datos['js']="imc_mensual";
		$datos['css']="imc_mes";
		$datos['datos_imc_mes']=$this->Imc_model->cargar_datos_imc($this->session->userdata('k_consultor'),$year,$month);
		
		
		
		//var_dump($datos_permisos_para_imc);die;
		
		enmarcar($this,"MostrarImcMes.php",$datos);
	}
	
	
	//funcion grabar imc
	public function mostrar_imc_mes_post()
	{
		//var_dump($_REQUEST);
		
		$eliminadas=isset($_REQUEST['lineasEliminadas'])?$_REQUEST['lineasEliminadas']:[];
		$actualizadas=isset($_REQUEST['lineasActualizadas'])?$_REQUEST['lineasActualizadas']:[];
		$creadas=isset($_REQUEST['lineasCreadas'])?$_REQUEST['lineasCreadas']:[];
		$total_horas=isset($_REQUEST['total_horas'])?$_REQUEST['total_horas']:null;
		$k_imc=isset($_REQUEST['k_imc'])?$_REQUEST['k_imc']:null;
		
		$this->Imc_model->grabar_datos_imc($eliminadas,$actualizadas,$creadas,$total_horas,$k_imc);
		
		echo "Cambios guardados.";
		
		
	}
	
	public function enviar_imc()
	{
		//var_dump($_REQUEST);
		
		
		//GRABAMOS EL IMC ANTES DE ENVIARLO
		
		$eliminadas=isset($_REQUEST['lineasEliminadas'])?$_REQUEST['lineasEliminadas']:[];
		$actualizadas=isset($_REQUEST['lineasActualizadas'])?$_REQUEST['lineasActualizadas']:[];
		$creadas=isset($_REQUEST['lineasCreadas'])?$_REQUEST['lineasCreadas']:[];
		$total_horas=isset($_REQUEST['total_horas'])?$_REQUEST['total_horas']:null;
		$k_imc=isset($_REQUEST['k_imc'])?$_REQUEST['k_imc']:null;
	
		$this->Imc_model->grabar_datos_imc($eliminadas,$actualizadas,$creadas,$total_horas,$k_imc);
		
		//y LO MARCAMOS COMO ENVIADO
		
		$this->Imc_model->enviar_imc($k_imc);
	
		echo "Su IMC ha sido enviado.";
	
	
	}
	
	public function obtener_lista_proyectos_por_tipo()
	{		
		$tipo=$_REQUEST['tipoProyecto'];
		$year=$_REQUEST['year'];
		$month=$_REQUEST['mes'];
		$codigos_solo_id=$this->Imc_model->listar_proyectos_por_tipo($this->session->userdata('k_consultor'),$tipo,$year,$month);
		print json_encode($codigos_solo_id);
	}
	
	
	
	public function mesTexto($numero)
	{
		$mes_texto="";
		switch ($numero) {			
			case 01:
				$mes_texto="Enero";
				break;
			case 02:
				$mes_texto="Febrero";
				break;
			case 03:
				$mes_texto="Marzo";
				break;
			case 04:
				$mes_texto="Abril";
				break;
			case 05:
				$mes_texto="Mayo";
				break;
			case 06:
				$mes_texto="Junio";
				break;
			case 07:
				$mes_texto="Julio";
				break;
			case 08:
				$mes_texto="Agosto";
				break;
			case 09:
				$mes_texto="Septiembre";
				break;
			case 10:
				$mes_texto="Octubre";
				break;
			case 11:
				$mes_texto="Noviembre";
				break;
			case 12:
				$mes_texto="Diciembre";
				break;
		}	
		//PONGO ESTO PORQUE MISTERIOSAMENTE NO FUNICONAN EL CASE 08 Y 09
		if($numero=='08')
		{
			$mes_texto='Agosto';
		}
		
		if($numero=='09')
		{
			$mes_texto='Septiembre';
		}
		
		return $mes_texto;
	}
	
}