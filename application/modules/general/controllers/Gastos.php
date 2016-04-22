<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Gastos extends MX_Controller
{
	
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model('Gastos_model');
		
	}
	
	
	
	
	
	
	
	public function index($condicion=3)
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
		$datos['js'] ="gastos";
		$datos['css'] ="gastos";
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
		enmarcar($this,"hojaGastosMes.php",$datos);
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
		return $mes_texto;
	}
	
}