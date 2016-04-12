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
		$datos['condicion']=$condicion;
		//$this->Imc_model->cargar_general();
		enmarcar($this,"Imc.php",$datos);
	}
	
	public function mostrarImcMes($year,$month)
	{		
		$datos['year'] =$year;
		$datos['mes'] =$month;
		$datos['js']="imc_mensual";
		$datos['datos_imc_mes']=$this->Imc_model->cargar_datos_imc($this->session->userdata('k_consultor'),$year,$month);
		enmarcar($this,"MostrarImcMes.php",$datos);
	}
	
	public function mostrar_imc_mes_post()
	{
		var_dump($_REQUEST['itemsServidor']);
		var_dump($_REQUEST['itemsServidor2']);
		//echo "servidor";
		die;
	}
	
	public function obtener_lista_proyectos_por_tipo()
	{
		
		$tipo=$_REQUEST['tipoProyecto'];
		$year=$_REQUEST['year'];
		$month=$_REQUEST['mes'];
		/*
		//PARA PROBAR
		$tipo=1;
		$year=05;
		$month=2012;
		*/
		$codigos_solo_id=$this->Imc_model->listar_proyectos_por_tipo($this->session->userdata('k_consultor'),$tipo,$year,$month);
		print json_encode($codigos_solo_id);
	}
	
}