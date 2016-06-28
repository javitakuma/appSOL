<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Prueba_model extends CI_Model
{
	
	public function __construct()
	{
		
		parent::__construct();
		
	}
	
	public function get_users()
	{		
		//$query = $this->db->get('users');
		
		return "Datos desde modelo";		
	}
	
	//Para migrar vacaciones de tablas temporal a solicitados
	public function vacaciones()
	{
		$year=2015;
		$this->load->database('vacas');		
		
		
		$sql = "select * FROM vacaciones2015";		
		
		$resultado=$this->db->query($sql)->result_array();
		
		$this->db->close();	
		
		////////////////////////////////////////
		
		foreach ($resultado as $fila)
		{
			$this->load->database();
			$this->db->trans_start();
			
			$id_consultor=trim($fila['id']);
			
			$sql2="select k_consultor FROM t_consultores WHERE id_consultor='$id_consultor'";
			
			$k_consultor=$this->db->query($sql2)->row_array()['k_consultor'];
			
			
			$this->db->trans_complete();
			$this->db->close();
			
			////////////////////////////////////////
			
			
			$this->load->database();
			$this->db->trans_start();
			
			
				
			
				for($i=1;$i<=12;$i++)
				{
					if($i<10)
					{
						$i="0".+$i;
					}
						
					//ESE MES TIENE VACACIONES
				
					if($fila["ant_".$i]!=null||$fila["act_".$i])
					{
						$data = array(
								'k_consultor'      =>   $k_consultor,
								'id_consultor'     =>   $fila['id'],
								'k_proyecto'       =>   450,
								'año_cons'         =>   $year,
								'mes_cons'         =>   $i,
						);
						$this->db->insert('t_permisos_consumidos',$data);
					}
					
					
					$k_permisos_cons=$this->db->insert_id();
				
				
				
					if($fila["ant_".$i]!=null)
					{
						$dias_partidos=explode(",", $fila["ant_".$i]);
							
						foreach ($dias_partidos as $dia)
						{
							$dia=trim($dia);
							
							if(is_nan($dia)||$dia>31)
							{
								echo $id_consultor."ant".$i;
							}
							
							
							if($dia<10)
							{
								$dia="0".$dia;
							}
				
							$data = array(
									'k_permisos_cons'      =>   $k_permisos_cons,
									'horas_cons'    	   =>   8,
									'dia_cons'      	   =>   $dia,
									'sw_consumido'         =>   -1,
									'desc_observaciones'   =>   "histórico vacaciones ( ".$id_consultor." ".$year.")",
									'año_vac'              =>   $year-1,
							);
							$this->db->insert('t_permisos_consumidos_det',$data);
				
						}
					}
				
					if($fila["act_".$i]!=null)
					{
						$dias_partidos=explode(",", $fila["act_".$i]);
				
						foreach ($dias_partidos as $dia)
						{
							$dia=trim($dia);
							
							if(is_nan($dia)||$dia>31)
							{
								echo $id_consultor."act".$i;
							}
							
							if($dia<10)
							{
								$dia="0".$dia;
							}
								
							$data = array(
									'k_permisos_cons'      =>   $k_permisos_cons,
									'horas_cons'    	   =>   8,
									'dia_cons'      	   =>   $dia,
									'sw_consumido'         =>   -1,
									'desc_observaciones'   =>   "histórico vacaciones ( ".$id_consultor." ".$year.")",
									'año_vac'              =>   $year,
							);
							$this->db->insert('t_permisos_consumidos_det',$data);
								
						}
					}
						
				}	
			
			
			
			
			//var_dump($resultado);
			
			$this->db->trans_complete();
			$this->db->close();
		}
		
			
	}
	
	public function trasponer_fechas_alta_baja_pl()
	{
		$this->load->database();
		$this->db->trans_start();
		
		$this->db->call_function("f_transposicion_fechas_consultores");
		
		$this->db->trans_complete();
		$this->db->close();
	}
	
	public function trasponer_fechas_alta_baja()
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql="SELECT * FROM zz_fechas_alta_baja_temp1";
		
		$resultado=$this->db->query($sql)->result_array();
		
		foreach ($resultado as $fila)
		{
			$data = array(
					'f_alta_consultor'       =>   $fila['fecha_alta'],
					'f_baja_consultor'       =>   $fila['fecha_baja'],
			);
				
			if($fila['fecha_alta']!=NULL)
			{

				$this->db->where('id_consultor', $fila['id_consultor']);
				$this->db->update('t_consultores', $data);
			}
			
		}
		
		$this->db->trans_complete();
		$this->db->close();
	}
	
}