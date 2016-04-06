<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_model extends CI_Model
{
	
	public function __construct()
	{
		
		parent::__construct();
		
	}
	
	public function get_users()
	{		
		$this->load->database();		
		$query=$this->db->query('select * from t_imcs LIMIT 3');		
		var_dump($query->result());
		$resultado=$query->result_array();		
		echo "<br/>";		
		foreach ($resultado as $r)
		{
			echo $r['i_tot_horas_imc'];
			echo "<br/>";
		}
		$this->db->close();		
	}
	
	
	
	
	
}