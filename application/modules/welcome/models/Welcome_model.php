<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Welcome_model extends CI_Model
{
	
	public function __construct()
	{
		
		parent::__construct();
		
	}
	
	public function cargar_usuarios_perfil($k_consultor_original,$PERFIL_JP,$PERFIL_FINAN)
	{		
		$this->load->database();
		$this->db->trans_start();
		
		
		
		//PROBADA EN PGADMIN
		$sql="SELECT t_consultores.k_consultor, t_consultores.id_consultor, t_consultores.nom_consultor, t_consultores.sw_administrador_petra, 
		t_consultores.sw_comercial, t_consultores.sw_administracion, $PERFIL_FINAN  Expr1, t_consultores.sw_baja
		FROM t_consultores
		WHERE 
		(((t_consultores.k_consultor)=$k_consultor_original) AND ((t_consultores.sw_baja)=0)) 
		OR
		
		(((t_consultores.sw_administrador_petra)=0) AND ((t_consultores.sw_comercial)=0) AND 
		((t_consultores.sw_administracion)=0) AND ((t_consultores.sw_baja)=0) AND (($PERFIL_FINAN)!=0)) 
		OR 
		
		((($PERFIL_JP)!=0) AND ((t_consultores.sw_baja)=0))
		
		
		ORDER BY t_consultores.id_consultor";
		
		$usuarios_perfil=$this->db->query($sql)->result_array();
		
		
		
		$this->db->trans_complete();
		$this->db->close();
		return $usuarios_perfil;
				
	}
	
	public function cargar_usuarios_perfil_solo_key($k_consultor,$PERFIL_JP,$PERFIL_FINAN)
	{
		$this->load->database();
		$this->db->trans_start();
	
	
	
		//PROBADA EN PGADMIN
		$sql="SELECT t_consultores.k_consultor, t_consultores.id_consultor, t_consultores.nom_consultor, t_consultores.sw_administrador_petra,
		t_consultores.sw_comercial, t_consultores.sw_administracion, $PERFIL_FINAN  Expr1, t_consultores.sw_baja
		FROM t_consultores
		WHERE
		(((t_consultores.k_consultor)=$k_consultor) AND ((t_consultores.sw_baja)=0))
		OR
	
		(((t_consultores.sw_administrador_petra)=0) AND ((t_consultores.sw_comercial)=0) AND
		((t_consultores.sw_administracion)=0) AND ((t_consultores.sw_baja)=0) AND (($PERFIL_FINAN)!=0))
		OR
	
		((($PERFIL_JP)!=0) AND ((t_consultores.sw_baja)=0))
	
	
		ORDER BY t_consultores.id_consultor";
	
		$usuarios_perfil=$this->db->query($sql)->result_array();
	
	
	
		$this->db->trans_complete();
		$this->db->close();
		return $usuarios_perfil;
	
	}
	
}