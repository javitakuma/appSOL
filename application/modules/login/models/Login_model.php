<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_model extends CI_Model
{
	
	public function __construct()
	{
		
		parent::__construct();
		
	}
	
	public function validar_usuario($id,$pass)//TODO  probar
	{	
		/*
		select A.k_consultor, A.id_consultor, B.pwd_guacd, A.nom_consultor, A.sw_baja, A.sw_resp_proyectos, A.sw_administrador_petra, A.sw_administracion, A.sw_comercial, A.sw_consultor, A.sw_rrhh, A.sw_imc_sol from t_consultores A, t_consultores_websol B
		where A.k_consultor=B.k_consultor
		*/
		
		$this->load->database();		
		
		$sql = "select A.k_consultor, A.id_consultor, B.pwd_guacd, A.nom_consultor, A.sw_baja, A.sw_resp_proyectos, A.sw_administrador_petra, 
						A.sw_administracion,A.sw_comercial, A.sw_consultor, A.sw_rrhh, A.sw_imc_sol from t_consultores A, t_consultores_websol B
				where A.k_consultor=B.k_consultor AND A.id_consultor=? AND pwd_guacd=?";
		
		$usuarioEncontrado=FALSE;
		
		if($this->db->query($sql, array($id,$pass))->num_rows()>0)
		{
			$usuarioEncontrado=$this->db->query($sql, array($id,$pass))->row_array();
		}	
		
		$this->db->close();	
		
		return $usuarioEncontrado;
				
	}
	
	public function codificar_pass($pass)
	{
		$pass_codificado="";
		
		$pass=utf8_encode($pass);
		for($i=0;$i<strlen($pass);$i++)
		{
			$en_asci=ord($pass[$i]);			
			
			$pass_codificado.=chr($en_asci+10);
		}
		return $pass_codificado;
	}
	
	
	
	
	
}