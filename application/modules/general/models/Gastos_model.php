<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Gastos_model extends CI_Model
{
	
	public function __construct()
	{		
		parent::__construct();		
	}
	
	
	
	
	
	public function get_hojas_gastos($condicion,$k_consultor)
	{		
		//$condicion   1=todas   2=pendiente pago 3= ninguna
		
		
		
		$this->load->database();
		$this->db->trans_start();		
		
		//EVALUAMOS LAS CONDICIONES EN LA QUERY, SEGUN NOS VENGA VALOR 1, 2 O 3 LA QUERY NOS DEVOLVERA UNOS U OTROS DATOS
		
		$sql = "SELECT t_hojas_gastos.i_tot_hoja_gastos, t_hojas_gastos.f_pago_hoja_gastos, t_hojas_gastos.k_hoja_gastos, t_hojas_gastos.f_mes_hoja_gastos, 
				t_hojas_gastos.f_año_hoja_gastos, t_hojas_gastos.i_tot_gastos_autorizados, t_hojas_gastos.i_tot_gastos_no_autorizados, 
				t_hojas_gastos.i_tot_gastos_pendientes, t_hojas_gastos.sw_autorizar_revision, t_hojas_gastos.i_imp_pagado
				FROM t_hojas_gastos
				WHERE (($condicion=1) AND (t_hojas_gastos.k_consultor=$k_consultor)) OR 
				(($condicion=2) AND (t_hojas_gastos.k_consultor=$k_consultor) AND (t_hojas_gastos.i_imp_pagado!=t_hojas_gastos.i_tot_gastos_autorizados)) OR 
				(($condicion=2) AND (t_hojas_gastos.k_consultor=$k_consultor) AND (t_hojas_gastos.i_tot_gastos_pendientes=t_hojas_gastos.i_tot_hoja_gastos))
				GROUP BY t_hojas_gastos.i_tot_hoja_gastos, t_hojas_gastos.f_pago_hoja_gastos, t_hojas_gastos.k_hoja_gastos, t_hojas_gastos.f_mes_hoja_gastos, t_hojas_gastos.f_año_hoja_gastos, t_hojas_gastos.i_tot_gastos_autorizados, t_hojas_gastos.i_tot_gastos_no_autorizados, t_hojas_gastos.i_tot_gastos_pendientes, t_hojas_gastos.sw_autorizar_revision, t_hojas_gastos.i_imp_pagado
				ORDER BY t_hojas_gastos.f_año_hoja_gastos DESC,t_hojas_gastos.f_mes_hoja_gastos DESC";
		
		
		$resultado_hojas_gastos=$this->db->query($sql)->result_array();
		
		
		//var_dump($resultado_hojas_gastos);
		
		
		$this->db->trans_complete();
		$this->db->close();
		
		
		return $resultado_hojas_gastos;
				
	}
	
	
	
	
	
	
	
}