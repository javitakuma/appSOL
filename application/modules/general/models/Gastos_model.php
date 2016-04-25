<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Gastos_model extends CI_Model
{
	
	public function __construct()
	{		
		parent::__construct();		
	}
	
	
	
	
	
	public function get_hojas_gastos($condicion,$k_consultor)
	{		
		//$condicion   1=todas   2=pendiente pago 3= ninguna
		
		
		
		$this->load->database();
		$this->db->trans_start();		
		
		//EVALUAMOS LAS CONDICIONES EN LA QUERY, SEGUN NOS VENGA VALOR 1, 2 O 3 LA QUERY NOS DEVOLVERA UNOS U OTROS DATOS
		
		$sql = "SELECT t_hojas_gastos.i_tot_hoja_gastos, t_hojas_gastos.f_pago_hoja_gastos, t_hojas_gastos.k_hoja_gastos, t_hojas_gastos.f_mes_hoja_gastos, 
				t_hojas_gastos.f_año_hoja_gastos, t_hojas_gastos.i_tot_gastos_autorizados, t_hojas_gastos.i_tot_gastos_no_autorizados, 
				t_hojas_gastos.i_tot_gastos_pendientes, t_hojas_gastos.sw_autorizar_revision, t_hojas_gastos.i_imp_pagado
				FROM t_hojas_gastos
				WHERE (($condicion=1) AND (t_hojas_gastos.k_consultor=$k_consultor)) OR 
				(($condicion=2) AND (t_hojas_gastos.k_consultor=$k_consultor) AND (t_hojas_gastos.i_imp_pagado!=t_hojas_gastos.i_tot_gastos_autorizados)) OR 
				(($condicion=2) AND (t_hojas_gastos.k_consultor=$k_consultor) AND (t_hojas_gastos.i_tot_gastos_pendientes=t_hojas_gastos.i_tot_hoja_gastos)) OR
				(($condicion=0) AND (t_hojas_gastos.k_consultor=$k_consultor) AND (t_hojas_gastos.i_imp_pagado=t_hojas_gastos.i_tot_gastos_autorizados) AND (t_hojas_gastos.sw_autorizar_revision!=0))
				GROUP BY t_hojas_gastos.i_tot_hoja_gastos, t_hojas_gastos.f_pago_hoja_gastos, t_hojas_gastos.k_hoja_gastos, t_hojas_gastos.f_mes_hoja_gastos, t_hojas_gastos.f_año_hoja_gastos, t_hojas_gastos.i_tot_gastos_autorizados, t_hojas_gastos.i_tot_gastos_no_autorizados, t_hojas_gastos.i_tot_gastos_pendientes, t_hojas_gastos.sw_autorizar_revision, t_hojas_gastos.i_imp_pagado
				ORDER BY t_hojas_gastos.f_año_hoja_gastos DESC,t_hojas_gastos.f_mes_hoja_gastos DESC";
		
		
		$resultado_hojas_gastos=$this->db->query($sql)->result_array();
		
		
		//var_dump($resultado_hojas_gastos);
		
		
		$this->db->trans_complete();
		$this->db->close();
		
		
		return $resultado_hojas_gastos;
				
	}
	
	public function cargar_gastos_mes($k_consultor,$year,$month)
	{
		$this->load->database();
		$this->db->trans_start();
		
		//===========SELECCIONAMOS LA CLAVE DE LA HOJA DE GASTOS DE ESE MES=========
		
		$sql = "SELECT k_hoja_gastos, com_hoja_gastos,f_pago_hoja_gastos,f_año_hoja_gastos,f_mes_hoja_gastos,sw_autorizar_revision
		FROM t_hojas_gastos
		WHERE (t_hojas_gastos.k_consultor=$k_consultor) AND (t_hojas_gastos.f_año_hoja_gastos=$year) AND (t_hojas_gastos.f_mes_hoja_gastos LIKE '$month')";
		
		
		$datos_gastos['t_hojas_gastos']=$this->db->query($sql)->result_array();
		//GUARDAMOS EL MISMO DATO EN UNA VARIABLE MAS AMIGABLE
		$k_hoja_gastos=$datos_gastos['t_hojas_gastos'][0]['k_hoja_gastos'];
		
		
		
		
		//===========SELECCIONAMOS LAS LINEAS DE GASTOS DE ESE MES PENDIENTES DE AUTORIZACION=========
		
		$sql_lineas_gastos_pendientes="SELECT t_linea_gasto.k_linea_gasto, t_linea_gasto.k_hoja_gasto, t_linea_gasto.k_proyecto, t_linea_gasto.k_tipo_linea_gasto, 
		t_linea_gasto.k_hito_ficha_proyecto, to_char(t_linea_gasto.f_linea_gasto, 'DD-MM-YYYY') f_linea_gasto, t_linea_gasto.i_imp_linea_gasto, t_linea_gasto.sw_linea_gasto_facturable, 
		t_linea_gasto.i_imp_linea_gasto_facturado, t_linea_gasto.k_linea_gasto_autorizado1, t_linea_gasto.k_linea_gasto_autorizado2, 
		t_linea_gasto.desc_linea_gasto, t_linea_gasto.com_rechazo_linea_gasto, t_linea_gasto.i_linea_hito, t_linea_gasto.k_linea_gasto_autorizado1, 
		t_linea_gasto.k_linea_gasto_autorizado2
		FROM t_linea_gasto
		WHERE (((t_linea_gasto.k_linea_gasto_autorizado1)=0) AND ((t_linea_gasto.k_linea_gasto_autorizado2)=0))AND (t_linea_gasto.k_hoja_gasto=$k_hoja_gastos)";
		
		$datos_gastos['lineas_gastos_pendientes']=$this->db->query($sql_lineas_gastos_pendientes)->result_array();
		
		
		//===========SELECCIONAMOS LOS GASTOS TOTALES DE ESE MES=========
		
		$sql_gastos_totales="SELECT t_hojas_gastos.i_tot_hoja_gastos, t_hojas_gastos.i_tot_gastos_no_autorizados, 
		t_hojas_gastos.i_tot_gastos_pendientes, t_hojas_gastos.i_tot_gastos_autorizados, t_hojas_gastos.k_hoja_gastos, t_hojas_gastos.i_imp_pagado
		FROM t_hojas_gastos
		WHERE (t_hojas_gastos.k_hoja_gastos=$k_hoja_gastos)
		GROUP BY t_hojas_gastos.i_tot_hoja_gastos, t_hojas_gastos.i_tot_gastos_no_autorizados, t_hojas_gastos.i_tot_gastos_pendientes, t_hojas_gastos.i_tot_gastos_autorizados, t_hojas_gastos.k_hoja_gastos, t_hojas_gastos.i_imp_pagado";
		
		$datos_gastos['gastos_totales']=$this->db->query($sql_gastos_totales)->result_array();
		
		
		
		
		//===========SELECCIONA TODAS LAS LINEAS DE GASTO DE ESE MES Y EMPLEADO=========
		
		$sql_lineas_gastos_todas="SELECT t_linea_gasto.k_linea_gasto, t_linea_gasto.k_hoja_gasto, t_linea_gasto.desc_linea_gasto, 
		CASE  WHEN sw_linea_gasto_facturable=-1 THEN i_gasto_fact_cliente
		ELSE 0
		END
		AS i_fact,
		t_linea_gasto.com_rechazo_linea_gasto, 
		t_linea_gasto.k_linea_gasto_autorizado1, t_linea_gasto.k_proyecto, t_linea_gasto.k_linea_gasto_autorizado2, t_linea_gasto.k_tipo_linea_gasto, 
		t_linea_gasto.k_hito_ficha_proyecto, t_linea_gasto.f_linea_gasto, t_linea_gasto.i_imp_linea_gasto, t_linea_gasto.sw_linea_gasto_facturable
		FROM t_linea_gasto
		join t_hojas_gastos on t_linea_gasto.k_hoja_gasto=t_hojas_gastos.k_hoja_gastos 
		WHERE (t_linea_gasto.k_hoja_gasto=$k_hoja_gastos)
		GROUP BY t_linea_gasto.k_linea_gasto, t_linea_gasto.k_hoja_gasto, t_linea_gasto.desc_linea_gasto, i_fact, t_linea_gasto.com_rechazo_linea_gasto, t_linea_gasto.k_linea_gasto_autorizado1, t_linea_gasto.k_proyecto, t_linea_gasto.k_linea_gasto_autorizado2, t_linea_gasto.k_tipo_linea_gasto, t_linea_gasto.k_hito_ficha_proyecto, t_linea_gasto.f_linea_gasto, t_linea_gasto.i_imp_linea_gasto, t_linea_gasto.sw_linea_gasto_facturable";
		
		$datos_gastos['lineas_gastos_todas']=$this->db->query($sql_lineas_gastos_todas)->result_array();
		
		
		/*SELECCIONA LOS PROYECTOS PARA EL DESPLEGABLE*/
		
		$hoy=date('Y-m-d');
		$hoy_menos_mes=date('Y-m-d',strtotime ( '-1 month'));
		
		$sql_proyectos_consultor="SELECT t_proyectos.k_proyecto, t_proyectos.id_proyecto
		FROM t_proyectos INNER JOIN t_consultores_proyecto ON t_proyectos.k_proyecto = t_consultores_proyecto.k_proyecto
		WHERE (((t_proyectos.sw_admite_gastos)=-1) AND (t_consultores_proyecto.k_consultor=$k_consultor) AND (t_proyectos.sw_baja=0) AND 
		(t_consultores_proyecto.f_fin_cp>?) AND (t_consultores_proyecto.f_inicio_cp<?))
		ORDER BY t_proyectos.id_proyecto";
		
		$datos_gastos['proyectos_consultor']=$this->db->query($sql_proyectos_consultor,array($hoy_menos_mes,$hoy))->result_array();
		$datos_gastos['proyectos_consultor_JSON']=json_encode($datos_gastos['proyectos_consultor']);
		
		/*SELECCIONA LOS TIPOS DE LINEA DE GASTOS PARA EL DESPLEGABLE*/
		
		$sql_tipos_gastos="SELECT t_tipos_linea_gasto.k_tipo_linea_gasto, t_tipos_linea_gasto.nom_tipo_linea_gasto
		FROM t_tipos_linea_gasto
		ORDER BY t_tipos_linea_gasto.nom_tipo_linea_gasto";
		
		$datos_gastos['tipos_gastos']=$this->db->query($sql_tipos_gastos)->result_array();
		$datos_gastos['tipos_gastos_JSON']=json_encode($datos_gastos['tipos_gastos']);
		
		$this->db->trans_complete();
		$this->db->close();
		//var_dump($datos_gastos);
		//die;
		return $datos_gastos;
	}
	
	public function grabar_gastos_mes($eliminadas,$actualizadas,$creadas,$k_hoja_gastos)
	{
				
		
		$this->load->database();
		$this->db->trans_start();
		
		
		//PARA CADA FILA ELIMINADA QUE YA EXISTIA LA BORRAMOS EN LA BBDD
		//TODO PROBAR
		
		foreach ($eliminadas as $fila)
		{
			$this->db->delete('t_linea_gasto', array('k_linea_gasto' => $fila['k_linea_gasto_borrar']));
				
			// Produces:
			// DELETE FROM t_linea_gasto
			// WHERE k_linea_gasto = $fila['k_linea_gasto']
		
		}
		
		//PARA CADA FILA ACTUALIZADA LA ACTUALIZAMOS EN LA BBDD
		
		foreach ($actualizadas as $fila)
		{			
			//var_dump($fila);
			
			
			
			$data = array(
					'k_hoja_gasto'       =>   $fila['k_hoja_gasto'],
					'k_proyecto'         =>   $fila['k_proyecto'],
					'k_tipo_linea_gasto' =>   $fila['k_tipo_linea_gasto'],
					'f_linea_gasto'      =>   $fila['f_linea_gasto'],
					'i_imp_linea_gasto'  =>   $fila['i_imp_linea_gasto'],
					'desc_linea_gasto'   =>   $fila['desc_linea_gasto'],
			);
				
			$this->db->where('k_linea_gasto', $fila['k_linea_gasto']);
			$this->db->update('t_linea_gasto', $data);
		
		}
		
		
		//PARA CADA FILA CREADA QUE NO EXISTIA LA INSERTAMOS EN LA BBDD
		//TODO PROBAR
		foreach ($creadas as $fila)
		{			
			$data = array(
					'k_hoja_gasto'       			=>   $fila['k_hoja_gasto'],
					'k_proyecto'         			=>   $fila['k_proyecto'],
					'k_tipo_linea_gasto' 			=>   $fila['k_tipo_linea_gasto'],
					'f_linea_gasto'      			=>   $fila['f_linea_gasto'],
					'i_imp_linea_gasto' 	 		=>   $fila['i_imp_linea_gasto'],
					'desc_linea_gasto'   			=>   $fila['desc_linea_gasto'],
			);
			$this->db->insert('t_linea_gasto',$data);
				
		}
		
		$this->db->trans_complete();
		$this->db->close();
		
		}
	
	
	
	
	
	
	
}