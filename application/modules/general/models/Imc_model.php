<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Imc_model extends CI_Model
{
	
	public function __construct()
	{		
		parent::__construct();		
	}
	
	public function get_imc_mensuales($condicion,$k_consultor)
	{		
		//$condicion   1=enviados   2=no enviados   3= todos
		
		$this->load->database();		
		
		//EVALUAMOS LAS CONDICIONES EN LA QUERY, SEGUN NOS VENGA VALOR 1, 2 O 3 LA QUERY NOS DEVOLVERA UNOS U OTROS DATOS
		
		$sql = "SELECT t_imcs.k_consultor, COALESCE(t_imcs.i_tot_horas_imc,0) i_tot_horas_imc, COALESCE(t_imcs.i_tot_horas_imc_validadas,0) i_tot_horas_imc_validadas, 
						t_imcs.sw_validacion, t_imcs.mes_imc, t_imcs.año_imc year_imc, t_imcs.k_imc
				FROM t_imcs
				GROUP BY t_imcs.k_consultor, t_imcs.i_tot_horas_imc, t_imcs.i_tot_horas_imc_validadas, 
						t_imcs.sw_validacion, t_imcs.mes_imc, t_imcs.año_imc, t_imcs.k_imc
				HAVING (((t_imcs.k_consultor)=$k_consultor) AND ((t_imcs.sw_validacion)=0) AND ($condicion=2)) OR (((t_imcs.k_consultor)=$k_consultor) AND ((t_imcs.sw_validacion)=-1) AND 
						($condicion=1)) OR (((t_imcs.k_consultor)=$k_consultor) AND ($condicion=3))
				ORDER BY  t_imcs.año_imc DESC,t_imcs.mes_imc DESC";
		
		
		$resultado_imc=$this->db->query($sql)->result_array();
		
		/*
		var_dump($resultado_imc);
		die;
		*/
		$this->db->close();
		return $resultado_imc;
		//var_dump($resultado_imc);
		
		//$query = $this->db->get('users');
		
		//return "Datos desde modelo";		
	}
	
}