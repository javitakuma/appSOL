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
		$this->db->trans_start();		
		
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
		$this->db->trans_complete();
		$this->db->close();
		return $resultado_imc;
		//var_dump($resultado_imc);
		
		//$query = $this->db->get('users');
		
		//return "Datos desde modelo";		
	}
	
	public function cargar_datos_imc($k_consultor,$year,$month)
	{
		//FORMATO $datos_imc_mes
		//datos_imc_mes['t_imcs']=FILA CON LINEA t_imcs DE ESE MES PARA ESE EMPLEADO
		//datos_imc_mes['t_imcs']=TODAS LAS LINEAS IMC DEL MES ANTERIOR
		
		$this->load->database();
		$this->db->trans_start();
		
		//==========IMC MES==============
		$sql ="SELECT *
		FROM t_imcs WHERE k_consultor = ? AND año_imc = ? AND mes_imc = ?";
		
		$datos_imc_mes['t_imcs']=$this->db->query($sql,array($k_consultor,$year,$month))->result_array();
		
		//===========LINEAS IMC=====================		
		$sql2 ="SELECT t_lineas_imc.k_linea_imc, t_lineas_imc.k_imc, t_lineas_imc.k_proyecto, 
				t_lineas_imc.i_horas_01, t_lineas_imc.i_horas_02, t_lineas_imc.i_horas_03, t_lineas_imc.i_horas_04, t_lineas_imc.i_horas_05, t_lineas_imc.i_horas_06, t_lineas_imc.i_horas_07, t_lineas_imc.i_horas_08, t_lineas_imc.i_horas_09, t_lineas_imc.i_horas_10, t_lineas_imc.i_horas_11, t_lineas_imc.i_horas_12, t_lineas_imc.i_horas_13, t_lineas_imc.i_horas_14, t_lineas_imc.i_horas_15, t_lineas_imc.i_horas_16, t_lineas_imc.i_horas_17, t_lineas_imc.i_horas_18, t_lineas_imc.i_horas_19, t_lineas_imc.i_horas_20, t_lineas_imc.i_horas_21, t_lineas_imc.i_horas_22, t_lineas_imc.i_horas_23, t_lineas_imc.i_horas_24, t_lineas_imc.i_horas_25, t_lineas_imc.i_horas_26, t_lineas_imc.i_horas_27, t_lineas_imc.i_horas_28, t_lineas_imc.i_horas_29, t_lineas_imc.i_horas_30, t_lineas_imc.i_horas_31, 
				t_lineas_imc.i_tot_horas_linea_imc, t_lineas_imc.desc_comentarios,
				t_proyectos.id_proyecto, t_proyectos.sw_proy_especial,t_proyectos.sw_interno
		FROM t_lineas_imc INNER JOIN t_proyectos ON t_lineas_imc.k_proyecto = t_proyectos.k_proyecto
				WHERE t_lineas_imc.k_imc={$datos_imc_mes['t_imcs'][0]['k_imc']}
				ORDER BY t_lineas_imc.k_linea_imc";		
		
		$datos_imc_mes['lineas_imc']=$this->db->query($sql2)->result_array();
		
		//DIAS MES
		
		//CREAMOS DOS STRING CON FECHAS ENTRE LAS QUE HACER BUSQUEDAS
		$fecha_inicio="$year-$month-01";
		
		//SI ESTAMOS EN DICIEMBRE NOS VAMOS AL ELSE Y PONEMOS EL 1 DE ENERO DEL AÑO SIGUIENTE
		if($month<12)
		{
			$fecha_fin="$year-".($month+1)."-01";
		}
		else
		{
			$fecha_fin=($year+1)."-01-01";
		}
		
		//SACAMOS LA CANTIDAD DE DIAS DEL MES RESTANDO 1 A LA BUSQUEDA PORQUE NOS CUENTA EL DIA 1 DEL MES SIGUIENTE
		$sql3 ='SELECT count(*)-1 cantidad_dias_mes
		FROM t_calendario
		WHERE f_dia_calendario between ? AND ?';
				
		$datos_imc_mes['cantidad_dias_mes']=$this->db->query($sql3,array($fecha_inicio,$fecha_fin))->result_array();
				
		//GUARDAMOS EL MISMO DATO EN UNA VARIABLE MAS AMIGABLE
		$datos_imc_mes['dias_por_mes']=$datos_imc_mes['cantidad_dias_mes'][0]['cantidad_dias_mes'];
			
		//GUARDAMOS EL DATO EN UNA VARIABLE MAS AMIGABLE
		$dias=$datos_imc_mes['dias_por_mes'];
				
		
		
		
		
		//CON ESTO SACAMOS LOS DIAS DEL MES DEL CALENDARIO CON ESE APAÑO DE LIMIT NOS QUITAMOS EL DIA 1 DEL MES SIGUIENTE
		$sql4 ="SELECT *
		FROM t_calendario
		WHERE f_dia_calendario between ? AND ?
		LIMIT $dias";
		
		$datos_imc_mes['t_calendario']=$this->db->query($sql4,array($fecha_inicio,$fecha_fin))->result_array();
		
		//VARIABLE CON LA CANTIDAD DE DIAS LABORABLES DEL MES
		$datos_imc_mes['dias_laborables_por_mes']=0;
		
		//BUCLE PARA CALCULAR LOS DIAS LABORABLES
		foreach ($datos_imc_mes['t_calendario'] as $dia)
		{
			if($dia['sw_laborable']==-1)
			{
				$datos_imc_mes['dias_laborables_por_mes']++;
			}
		}
		
		//VARIABLE TIPO ARRAY CON LOS DIAS FESTIVOS DEL MES
		$datos_imc_mes['dias_festivos_array']=[];
		
		//CREAMOS EL ARRAY
		for($i=1;$i<=sizeof($datos_imc_mes['t_calendario']);$i++)
		{	
			if($datos_imc_mes['t_calendario'][$i-1]['sw_laborable']==0)
			{
				array_push($datos_imc_mes['dias_festivos_array'], $i);
			}
						
		}
		
		$datos_imc_mes['dias_festivos_array']=json_encode($datos_imc_mes['dias_festivos_array']);
		
				
		$this->db->trans_complete();
		$this->db->close();
		return $datos_imc_mes;
		
	}
	
	
	public function grabar_datos_imc($eliminadas,$actualizadas,$creadas,$total_horas,$k_imc)
	{
		$this->load->database();
		$this->db->trans_start();
		
		
		//PARA CADA FILA ELIMINADA QUE YA EXISTIA LA BORRAMOS EN LA BBDD
		foreach ($eliminadas as $fila)
		{				
			$this->db->delete('t_lineas_imc', array('k_linea_imc' => $fila['k_linea_imc_borrar']));
			
			// Produces:
			// DELETE FROM t_lineas_imc
			// WHERE k_lineas_imc = $fila['k_linea_imc_borrar']
				
		}
		
		//PARA CADA FILA ACTUALIZADA LA ACTUALIZAMOS EN LA BBDD
		foreach ($actualizadas as $fila)
		{
			$fila['i_horas_29']=isset($fila['i_horas_29'])?$fila['i_horas_29']:0;
			$fila['i_horas_30']=isset($fila['i_horas_30'])?$fila['i_horas_30']:0;
			$fila['i_horas_31']=isset($fila['i_horas_31'])?$fila['i_horas_31']:0;
			
			$data = array(
					'k_imc'       	   =>   $fila['k_imc'],
					'k_proyecto'       =>   $fila['k_proyecto'],
					'i_horas_01'       =>   $fila['i_horas_01'],
					'i_horas_02'       =>   $fila['i_horas_02'],
					'i_horas_03'       =>   $fila['i_horas_03'],
					'i_horas_04'       =>   $fila['i_horas_04'],
					'i_horas_05'       =>   $fila['i_horas_05'],
					'i_horas_06'       =>   $fila['i_horas_06'],
					'i_horas_07'       =>   $fila['i_horas_07'],
					'i_horas_08'       =>   $fila['i_horas_08'],
					'i_horas_09'       =>   $fila['i_horas_09'],
					'i_horas_10'       =>   $fila['i_horas_10'],
					'i_horas_11'       =>   $fila['i_horas_11'],
					'i_horas_12'       =>   $fila['i_horas_12'],
					'i_horas_13'       =>   $fila['i_horas_13'],
					'i_horas_14'       =>   $fila['i_horas_14'],
					'i_horas_15'       =>   $fila['i_horas_15'],
					'i_horas_16'       =>   $fila['i_horas_16'],
					'i_horas_17'       =>   $fila['i_horas_17'],
					'i_horas_18'       =>   $fila['i_horas_18'],
					'i_horas_19'       =>   $fila['i_horas_19'],
					'i_horas_20'       =>   $fila['i_horas_20'],
					'i_horas_21'       =>   $fila['i_horas_21'],
					'i_horas_22'       =>   $fila['i_horas_22'],
					'i_horas_23'       =>   $fila['i_horas_23'],
					'i_horas_24'       =>   $fila['i_horas_24'],
					'i_horas_25'       =>   $fila['i_horas_25'],
					'i_horas_26'       =>   $fila['i_horas_26'],
					'i_horas_27'       =>   $fila['i_horas_27'],
					'i_horas_28'       =>   $fila['i_horas_28'],
					'i_horas_29'       =>   $fila['i_horas_29'],
					'i_horas_30'       =>   $fila['i_horas_30'],
					'i_horas_31'       =>   $fila['i_horas_31'],
					'i_tot_horas_linea_imc'=>   $fila['i_tot_horas_linea_imc'],
					'desc_comentarios' =>   $fila['desc_comentarios'],					
			);
			
			$this->db->where('k_linea_imc', $fila['k_linea_imc']);
			$this->db->update('t_lineas_imc', $data); 
		
		}
		
		//PARA CADA FILA CREADA QUE NO EXISTIA LA INSERTAMOS EN LA BBDD
		foreach ($creadas as $fila)
		{			
						
			//ASIGNAMOS VALORES A LOS DIAS 29,30 Y 31 POR SI FUERA UN MES COMO FEBRERO, SI YA EXISTIAN NO SOBREESCRIBIRA
			
			$fila['i_horas_29']=isset($fila['i_horas_29'])?$fila['i_horas_29']:0;
			$fila['i_horas_30']=isset($fila['i_horas_30'])?$fila['i_horas_30']:0;
			$fila['i_horas_31']=isset($fila['i_horas_31'])?$fila['i_horas_31']:0;
			
			$data = array(
					'k_imc'       	   =>   $fila['k_imc'],
					'k_proyecto'       =>   $fila['k_proyecto'],
					'i_horas_01'       =>   $fila['i_horas_01'],
					'i_horas_02'       =>   $fila['i_horas_02'],
					'i_horas_03'       =>   $fila['i_horas_03'],
					'i_horas_04'       =>   $fila['i_horas_04'],
					'i_horas_05'       =>   $fila['i_horas_05'],
					'i_horas_06'       =>   $fila['i_horas_06'],
					'i_horas_07'       =>   $fila['i_horas_07'],
					'i_horas_08'       =>   $fila['i_horas_08'],
					'i_horas_09'       =>   $fila['i_horas_09'],
					'i_horas_10'       =>   $fila['i_horas_10'],
					'i_horas_11'       =>   $fila['i_horas_11'],
					'i_horas_12'       =>   $fila['i_horas_12'],
					'i_horas_13'       =>   $fila['i_horas_13'],
					'i_horas_14'       =>   $fila['i_horas_14'],
					'i_horas_15'       =>   $fila['i_horas_15'],
					'i_horas_16'       =>   $fila['i_horas_16'],
					'i_horas_17'       =>   $fila['i_horas_17'],
					'i_horas_18'       =>   $fila['i_horas_18'],
					'i_horas_19'       =>   $fila['i_horas_19'],
					'i_horas_20'       =>   $fila['i_horas_20'],
					'i_horas_21'       =>   $fila['i_horas_21'],
					'i_horas_22'       =>   $fila['i_horas_22'],
					'i_horas_23'       =>   $fila['i_horas_23'],
					'i_horas_24'       =>   $fila['i_horas_24'],
					'i_horas_25'       =>   $fila['i_horas_25'],
					'i_horas_26'       =>   $fila['i_horas_26'],
					'i_horas_27'       =>   $fila['i_horas_27'],
					'i_horas_28'       =>   $fila['i_horas_28'],
					'i_horas_29'       =>   $fila['i_horas_29'],
					'i_horas_30'       =>   $fila['i_horas_30'],
					'i_horas_31'       =>   $fila['i_horas_31'],
					'i_tot_horas_linea_imc'=>   $fila['i_tot_horas_linea_imc'],
					'desc_comentarios' =>   $fila['desc_comentarios'],					
			);
			$this->db->insert('t_lineas_imc',$data);
					
		}			
		$this->db->trans_complete();
		$this->db->close();
		
	}
	
	public function enviar_imc($k_imc)
	{
		//SOLO PONEMOS EL sw_validacion a -1
		
		$this->load->database();
		$this->db->trans_start();
	
		$data = array(
				'sw_validacion' => -1,
		);
		
		$this->db->where('k_imc', $k_imc);
		
		$this->db->update('t_imcs', $data);
		// Produces:
		// UPDATE t_imcs
		// SET sw_validacion = '{-1}'
		// WHERE k_imc = $k_imc
		
		
		
		$this->db->trans_complete();
		$this->db->close();
	
	}
	
	
	public function listar_proyectos_por_tipo($k_consultor,$tipo,$year,$month)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql;
		$codigos_proyecto;
		
		//==========CODIGOS PROYECTO==============
		if($tipo==1 ||$tipo==2)
		{
			$sql ="SELECT A.k_proyecto,B.id_proyecto
			FROM t_consultores_proyecto A
			INNER JOIN t_proyectos B on A.k_proyecto=B.k_proyecto
			WHERE A.k_consultor = ? AND f_inicio_cp<? AND f_fin_cp>? AND B.sw_baja=0 AND
			((B.sw_interno=0 AND B.sw_proy_especial=0 AND $tipo=1)OR
			(B.sw_interno=-1 AND B.sw_proy_especial=0 AND $tipo=2))";
			
			$hoy=date('Y-m-d');
			$hoy_menos_mes=date('Y-m-d',strtotime ( '-1 month'));
			//TODO
			$codigos_proyecto=$this->db->query($sql,array($k_consultor,$hoy,$hoy_menos_mes))->result_array();
		}
		
		if($tipo==3)
		{
			$sql ="SELECT k_proyecto,id_proyecto FROM t_proyectos where sw_proy_especial=-1";
			$codigos_proyecto=$this->db->query($sql)->result_array();
		}			
		$this->db->trans_complete();
		$this->db->close();
		return $codigos_proyecto;
		
	}
	
}