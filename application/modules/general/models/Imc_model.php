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
				WHERE t_lineas_imc.k_imc={$datos_imc_mes['t_imcs'][0]['k_imc']}";		
		
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
		
		
		
		$dias=$datos_imc_mes['dias_por_mes'];
		
		
		
		$datos_imc_mes['dias_laborables_por_mes']=0;
		
		//CON ESTO SACAMOS LOS DIAS DEL MES DEL CALENDARIO CON ESE APAÑO DE LIMIT NOS QUITAMOS EL DIA 1 DEL MES SIGUIENTE
		$sql4 ="SELECT *
		FROM t_calendario
		WHERE f_dia_calendario between ? AND ?
		LIMIT $dias";
		
		$datos_imc_mes['t_calendario']=$this->db->query($sql4,array($fecha_inicio,$fecha_fin))->result_array();
		
		//BUCLE PARA CALCULAR LOS DIAS LABORABLES
		foreach ($datos_imc_mes['t_calendario'] as $dia)
		{
			if($dia['sw_laborable']==-1)
			{
				$datos_imc_mes['dias_laborables_por_mes']++;
			}	
		}
				
		$this->db->trans_complete();
		$this->db->close();
		return $datos_imc_mes;
		
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