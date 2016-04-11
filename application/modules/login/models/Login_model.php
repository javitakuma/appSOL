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
	
	public function cambiar_password($nuevo_password,$k_consultor)
	{	
		//INICIALIZAMOS BASE DE DATOS E INICIAMOS TRANSACCION
		$this->load->database();
		$this->db->trans_start();
		
		//HACEMOS UNA CONSULTA CUALQUIERA PARA PROBRAR QUE EL ID LOGADO SEA CORRECTO
		$sql = "select user_guacd from t_consultores_websol
				where k_consultor=?";		
		//SI LA CONSULTA DEVUELVE ALGUNA FILA(SOLO DEVOLVERIA 1 COMO MUCHO..) ENTRAMOS EN EL IF Y ACTUALIZAMOS
		if($this->db->query($sql, array($k_consultor))->num_rows()>0)
		{
			$sql = "update t_consultores_websol set pwd_guacd=?
					where k_consultor=?";
			$password_cambiado=$this->db->query($sql,array($nuevo_password,$k_consultor));	
		}
		//CHEQUEAMOS SI EL UPDATE HA ACTUALIZADO FILAS(DEVOLVERA 0 EN CASO CONTRARIO)
		$correcto=$this->db->affected_rows();
		
		$this->db->trans_complete();
		$this->db->close();
		//SI AFECTO A FILAS DEVOLVEMOS TRUE
		
		return ($correcto>0)?TRUE:FALSE;
	}
	
	
	
	
	
	
	
}