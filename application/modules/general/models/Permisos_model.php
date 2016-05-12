<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Permisos_model extends CI_Model
{
	
	public function __construct()
	{		
		parent::__construct();		
	}
	
	
	
	public function cargar_dias_debidos($k_consultor,$year_solicitud)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
		coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones 
		WHERE k_consultor=$k_consultor AND año_vac=$year_solicitud";
		
		$total_vac_este=$this->db->query($sql)->row_array();
		
		$year_anterior=$year_solicitud-1;
		
		$sql2 ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
		coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones 
		WHERE k_consultor=$k_consultor AND año_vac=$year_anterior";
		
		$total_vac_anterior=$this->db->query($sql2)->row_array();
		
		$sql3 ="SELECT count(*) suma_consumidos FROM t_permisos_solicitados_det A
		join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
		WHERE A.año_vac=$year_solicitud AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2!=2";
		
		$consumidos_este=$this->db->query($sql3)->row_array();
		
		$sql4 ="SELECT count(*) suma_consumidos FROM t_permisos_solicitados_det A
		join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
		WHERE A.año_vac=$year_anterior AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2!=2";
		
		$consumidos_anterior=$this->db->query($sql4)->row_array();
				
		$dias['dias_debidos']=$total_vac_este['suma_dias']-$consumidos_este['suma_consumidos'];
		$dias['dias_debidos_pendientes']=$total_vac_anterior['suma_dias']-$consumidos_anterior['suma_consumidos'];
		
		/*
		echo $total_vac_este['suma_dias'];
		echo $consumidos_este['suma_consumidos'];
		
		echo $dias['dias_debidos']."<br/>";
		echo $dias['dias_debidos_pendientes'];
		
		die;
		*/
		
		$this->db->trans_complete();
		$this->db->close();
		
		return $dias;
	}
	
	
	public function cargar_responsables_proyectos()
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT * FROM t_consultores WHERE sw_resp_proyectos=-1 AND sw_baja=0 AND id_consultor NOT LIKE 'NADIE' order by nom_consultor ";		
		
		$resp_proyectos=$this->db->query($sql)->result_array();
				
		$this->db->trans_complete();
		$this->db->close();
		return $resp_proyectos;
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
		
		$sql ="SELECT A.dia_solic,A.mes_solic,A.año_solic year_solic,B.i_autorizado_n1,B.i_autorizado_n2 FROM t_permisos_solicitados_det A
		join t_permisos_solicitados B 
		on A.k_permisos_solic=B.k_permisos_solic		
		where B.k_consultor ='$k_consultor'";
		
		$diasSolicitados=$this->db->query($sql)->result_array();
		
				
		$this->db->trans_complete();
		$this->db->close();
		return $diasSolicitados;
		
	}
	
	public function comprobar_calendario_proximo_year($year)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$year++;
		
		//BUSCAMOS EN EL CALENDARIO EL 1 ENERO DEL AÑO QUE VIENE		
		$sql ="SELECT * FROM t_calendario where f_dia_calendario='$year-01-01' ";
		
		$existeProximoYear=$this->db->query($sql)->result_array();
		
		
			
		$this->db->trans_complete();
		$this->db->close();
		//DEVOLVEMOS EL NUMERO DE FILAS DEVUELTAS QUE SERA 1 SI ENCUENTRA Y 0 SI NO LO HACE (ESOS VALORES LOS USAMOS COMO TRUE O FALSE)
		return sizeof($existeProximoYear);
	}
	
	public  function cargar_tipo_permisos()
	{
		$this->load->database();
		$this->db->trans_start();
		
		
		$sql ="SELECT k_proyecto,id_proyecto,nom_proyecto FROM t_proyectos where id_proyecto IN('PRO450','PRO468')";
		
		
		
		$permisos['tipo_solicitud']=$this->db->query($sql)->result_array();
		
		
					
		$this->db->trans_complete();
		$this->db->close();
		return $permisos;
	}
	
	public  function cargar_historico_permisos($k_consultor)
	{
		$this->load->database();
		$this->db->trans_start();
	
	
		$sql ="SELECT k_permisos_solic, f_solicitud,id_consultor,id_proyecto,
		CASE i_autorizado_n1
			WHEN 0 THEN 'Pendiente'	
			WHEN 1 THEN 'Autorizado'
			WHEN 2 THEN 'No autorizado'
		END i_autorizado_n1,
		CASE i_autorizado_n2 
			WHEN 0 THEN 'Pendiente'	
			WHEN 1 THEN 'Autorizado'
			WHEN 2 THEN 'No autorizado'
		END i_autorizado_n2,
		desc_observaciones,COALESCE(desc_rechazo,'') desc_rechazo,sw_envio_solicitud FROM t_permisos_solicitados A
		JOIN t_proyectos B on A.k_proyecto=B.k_proyecto
		ORDER BY k_permisos_solic";
	
	
	
		$permisos=$this->db->query($sql)->result_array();
		
		
		for($i=0;$i<sizeof($permisos);$i++)
		{
			$sql ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',año_solic) primera_fecha FROM t_permisos_solicitados_det
			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} ORDER BY año_solic,mes_solic,dia_solic ASC LIMIT 1";
				
			$primer_dia=$this->db->query($sql)->result_array();
				
			$sql2 ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',año_solic) ultima_fecha FROM t_permisos_solicitados_det
			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']}  ORDER BY año_solic,mes_solic,dia_solic DESC LIMIT 1";
			
			$ultimo_dia=$this->db->query($sql2)->result_array();
			
			$permisos[$i]['primer_dia']=$primer_dia[0]['primera_fecha'];
			$permisos[$i]['ultimo_dia']=$ultimo_dia[0]['ultima_fecha'];
		}
					
		$this->db->trans_complete();
		$this->db->close();
		
				
		return $permisos;
	}
	
	public function cargar_festivos()
	{
		$this->load->database();
		$this->db->trans_start();		
		
		$ultimoDiaYear = date('Y')-1 . '-09-30';
		
				
		$sql ="SELECT f_dia_calendario FROM t_calendario WHERE sw_laborable=0 and f_dia_calendario>'$ultimoDiaYear'";		
		
		$festivos=$this->db->query($sql)->result_array();		
			
		$this->db->trans_complete();
		$this->db->close();
		
		return $festivos;
	}
	
	public function grabar_solicitud($datos_guardar)
	{
		$fechaActual = date('Y-m-d');
				
		$this->load->database();
		$this->db->trans_start();
		
		$id_responsable=$this->db->query("SELECT id_consultor FROM t_consultores WHERE k_consultor={$datos_guardar['responsable_solicitud']}")->row_array();
		
		
		//NUEVA SOLICITUD
		
		if($datos_guardar['k_permisos_solic']==0)
		{			
			$data = array(
					'k_consultor_solic'     =>   $datos_guardar['k_consultor_solic'],
					'k_consultor'         	=>   $datos_guardar['k_consultor'],
					'id_consultor' 			=>   $datos_guardar['id_consultor'],
					'k_proyecto'      		=>   $datos_guardar['k_proyecto_solicitud'],
					'f_solicitud' 	 		=>   $fechaActual,
					'desc_observaciones'   	=>   $datos_guardar['observaciones'],
					'sw_envio_solicitud'    =>   0,
					'k_responsable'         =>   $datos_guardar['responsable_solicitud'],
					'id_responsable' 		=>   $id_responsable['id_consultor'],
					'i_autorizado_n1'      	=>   0,
					'i_autorizado_n2' 	 	=>   0,
					'desc_rechazo'   		=>   null,
			);
			
			$resultado=$this->db->insert('t_permisos_solicitados',$data);	
			
			$datos_guardar['k_permisos_solic']=$this->db->insert_id();
			
		}
		
		else//actualizacion
		{
			/*
			$data = array(
					'k_hoja_gasto'       =>   $fila['k_hoja_gasto'],
					'k_proyecto'         =>   $fila['k_proyecto'],
					'k_tipo_linea_gasto' =>   $fila['k_tipo_linea_gasto'],
					'f_linea_gasto'      =>   $fila['f_linea_gasto'],
					'i_imp_linea_gasto'  =>   $fila['i_imp_linea_gasto'],
					'desc_linea_gasto'   =>   $fila['desc_linea_gasto'],
			);
			
			$this->db->where('k_permisos_solic', $datos_guardar['k_permisos_solic']);
			$this->db->update('t_permisos_solicitados', $data);
			*/
		}
		
		$array_dias =explode(", ", $datos_guardar['dias_solicitados']);
		$array_horas =explode(" ", $datos_guardar['horas_por_dias']);
		
		
		
		
		for($i=0;$i<sizeof($array_dias);$i++)
		{
			$horas;
			
			if($datos_guardar['k_proyecto_solicitud']==450)
			{
				$horas=$datos_guardar['horas_jornada'];	
			}
			
			if($datos_guardar['k_proyecto_solicitud']==468)
			{
				$horas=$array_horas[$i];
			}
			
			
			$dia_partido=explode("-", $array_dias[$i]);
			$dia=$dia_partido[0];
			$mes=$dia_partido[1];
			$year=$dia_partido[2];	
			
			$year_vac=($datos_guardar['diasPendientesDebidos']>0)?$datos_guardar['year_solicitud']-1:$datos_guardar['year_solicitud'];
			
			$data = array(
					'k_permisos_solic'      =>   $datos_guardar['k_permisos_solic'],
					'horas_solic'         	=>   $horas,
					'dia_solic' 			=>   $dia,
					'mes_solic'      		=>   $mes,
					'año_solic' 	 		=>   $year,
					'año_vac'   			=>   $year_vac,
			);
				
			$resultado=$this->db->insert('t_permisos_solicitados_det',$data);
			
			$datos_guardar['diasPendientesDebidos']--;
		}
		
		$this->db->trans_complete();
		$this->db->close();
		
		return $resultado;
		
		
	}
	
}