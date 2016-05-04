<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Permisos_model extends CI_Model
{
	
	public function __construct()
	{		
		parent::__construct();		
	}
	
	public function cargar_dias_para_horas($fechaActual)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT * FROM t_calendario where f_dia_calendario >'$fechaActual'";
		
		
		
		$fechas=$this->db->query($sql)->result_array();		
		
		//CAMBIAMOS A FORMATO DE FECHA ESPAÑOL
		for($i=0;$i<sizeof($fechas);$i++)
		{
			$fechaPartida=explode("-", $fechas[$i]['f_dia_calendario']);
			$fechas[$i]['f_dia_calendario']=$fechaPartida[2]."-".$fechaPartida[1]."-".$fechaPartida[0];
		}
		
		$this->db->trans_complete();
		$this->db->close();
		return $fechas;
	}
	
	public function cargar_dias_solicitados($k_consultor)
	{
		
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT k_permisos_solic FROM t_permisos_solicitados where k_consultor ='$k_consultor'";
		$k_permisos_solicitados=$this->db->query($sql)->result_array();
		
		
		$fechas=array();
		
		foreach ($k_permisos_solicitados as $fila)
		{
			$sql2 ="SELECT * FROM t_permisos_solicitados_det where k_permisos_solic ={$fila['k_permisos_solic']}";
			
			$datos=$this->db->query($sql2)->result_array();
			
			foreach ($datos as $fila)
			{
				array_push($fechas, $fila);
			}
			
			//array_push($fechas, $this->db->query($sql2)->result_array());
		}
		
		/*
		var_dump($fechas);
		die;
		*/
		
		$fechasFormateadas=array();
		//CAMBIAMOS A FORMATO DE FECHA ESPAÑOL
		for($i=0;$i<sizeof($fechas);$i++)
		{
			$dia=$fechas[$i]['dia_solic'];			
			$dia=sprintf('%02d', $dia);
			
			
			$mes=$fechas[$i]['mes_solic'];			
			$mes=sprintf('%02d', $mes);			
			
			$year=$fechas[$i]['año_solic'];
			
			$fechaFormateada['fecha']=$mes."/".$dia."/".$year;
			$fechaFormateada['k_permisos_solic']=$fechas[$i]['k_permisos_solic'];
			
			array_push($fechasFormateadas, $fechaFormateada);
		}
		
		
		$this->db->trans_complete();
		$this->db->close();
		return $fechasFormateadas;
		
	}
	
	
	public  function cargar_permisos($k_consultor)
	{
		$this->load->database();
		$this->db->trans_start();
		
		
		$sql ="SELECT k_proyecto,id_proyecto FROM t_proyectos where id_proyecto IN('PRO450','PRO468')";
		
		
		
		$permisos['tipo_solicitud']=$this->db->query($sql)->result_array();
		
		
					
		$this->db->trans_complete();
		$this->db->close();
		return $permisos;
	}
	
	public function cargar_festivos()
	{
		$this->load->database();
		$this->db->trans_start();		
		
		$fecha=new DateTime();
		$fechaParseada=date_format($fecha, 'Y-m-d');
				
		
		$sql ="SELECT f_dia_calendario FROM t_calendario WHERE sw_laborable=0 and f_dia_calendario>'$fechaParseada'";		
		
		$festivos=$this->db->query($sql)->result_array();		
			
		$this->db->trans_complete();
		$this->db->close();
		
		return $festivos;
	}
}