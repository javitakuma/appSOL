<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Gastos extends MX_Controller
{
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('Gastos_model');
		
	}	
	
	//MUESTRA LA PANTALLA DE TOODOS LOS MESES
	public function index($condicion=1)
	{		
		$k_consultor=$this->session->userdata('k_consultor');
		if($condicion==3)
		{
			$datos['hojas_gastos']=array();
		}
		else
		{
			$datos['hojas_gastos']=$this->Gastos_model->get_hojas_gastos($condicion,$k_consultor);
		}		
		$datos['js'] =["gastos"];
		//$datos['css'] ="gastos";
		$datos['css'] =["gastos"];
		$datos['condicion']=$condicion;
		 
		enmarcar($this,"Gastos.php",$datos);
	}
	
	public function mostrar_gastos_mes($year,$month)
	{
		$datos['year'] =$year;
		$datos['mes'] =$month;
		$datos['mes_texto'] =$this->mesTexto($month);
		$datos['js']="hoja_gastos_mes";
		$datos['css']="hoja_gastos_mes";
		$datos['datos_gastos']=$this->Gastos_model->cargar_gastos_mes($this->session->userdata('k_consultor'),$year,$month);
		
		/*
		var_dump($datos);
		die;
		*/
		
		enmarcar($this,"hojaGastosMes.php",$datos);
	}
	
	public function grabar_gastos_mes()
	{		
		$eliminadas=isset($_REQUEST['lineasEliminadas'])?$_REQUEST['lineasEliminadas']:[];
		$actualizadas=isset($_REQUEST['lineasActualizadas'])?$_REQUEST['lineasActualizadas']:[];
		$creadas=isset($_REQUEST['lineasCreadas'])?$_REQUEST['lineasCreadas']:[];
		$k_hoja_gastos=isset($_REQUEST['k_hoja_gastos'])?$_REQUEST['k_hoja_gastos']:null;
		
		$this->Gastos_model->grabar_gastos_mes($eliminadas,$actualizadas,$creadas,$k_hoja_gastos);
		
		echo "Cambios guardados.";
	}
	
	public function enviar_hoja_gastos_mes()
	{
		$eliminadas=isset($_REQUEST['lineasEliminadas'])?$_REQUEST['lineasEliminadas']:[];
		$actualizadas=isset($_REQUEST['lineasActualizadas'])?$_REQUEST['lineasActualizadas']:[];
		$creadas=isset($_REQUEST['lineasCreadas'])?$_REQUEST['lineasCreadas']:[];
		$k_hoja_gastos=isset($_REQUEST['k_hoja_gastos'])?$_REQUEST['k_hoja_gastos']:null;
	
		$this->Gastos_model->grabar_gastos_mes($eliminadas,$actualizadas,$creadas,$k_hoja_gastos);
		
		$this->Gastos_model->enviar_hoja_gastos($k_hoja_gastos);
	
		echo "Hoja de gastos guardada.";
	}
	
	public function generar_nueva_hoja_gastos()
	{
		$year=$_REQUEST['year_seleccion'];
		$month=$_REQUEST['mes_seleccion'];
		
		/*
		if(strlen($month)==1)
		{
			$month='0564'+$month;
		}
		echo $month;
		die;
		*/
		
		$this->Gastos_model->buscar_hojas_gastos($this->session->userdata('k_consultor'),$year,$month,$this->session->userdata('id_consultor'));
		
		$this->mostrar_gastos_mes($year, $month);
	}
	
	
	
	
	
	
	
	
	
	public function mesTexto($numero)
	{
		$meses_texto=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];			
			
		return $meses_texto[$numero-1];
	}
	
}