<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_model extends CI_Model
{
	
	public function __construct()
	{
		
		parent::__construct();
		
	}
	
	public function validar_usuario($id,$pass)//TODO  probar
	{	
		mb_internal_encoding ( "UTF-8" );
		header('Content-Type: text/html; charset=UTF-8');
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
		echo mb_strlen("uña");
		
		//die;
		
		
		
		
		echo mb_detect_encoding($pass);
		
		$pp=mb_convert_encoding($pass, "UTF-8");
		
		$pp1=$string = iconv('ASCII', 'UTF-8', $pass);
		
		echo mb_detect_encoding($pp);
		echo mb_detect_encoding($pp1);
		
		echo $this->codificar_pass($pass);
		die;
		
		$this->load->database();		
		
		$sql = "SELECT * FROM t_consultores WHERE id_consultor = ? && pswd_consultor = ?";
		
		$usuarioEncontrado=FALSE;
		
		if($this->db->query($sql, array($id,$this->codificar_pass($pass)))->num_rows()>0)
		{
			$usuarioEncontrado=TRUE;
			echo "encontrado";
		}
		else
		{
			echo "no encontrado";
		}		
		
		$this->db->close();	
		
		//return $usuarioEncontrado;
		
		
		/*//TODO PRUEBA
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
		*/
		
		
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