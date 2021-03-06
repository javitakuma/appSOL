<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Permisos_model extends CI_Model
{
	
	public function __construct()
	{		
		parent::__construct();		
	}
	
	
	public function verificar_admin_rrhh($k_consultor_original)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT * from t_consultores where k_consultor=$k_consultor_original AND sw_admin_permisos=-1";
		
		$es_administrador_rrhh=$this->db->query($sql)->row_array();
		
		$respuesta=false;
		if($es_administrador_rrhh!=null)
		{
			$respuesta=true;
		}
		
		$this->db->trans_complete();
		$this->db->close();
		return $respuesta;
	}
	
	public function primer_dia_calendario()
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT f_dia_calendario FROM t_calendario LIMIT 1";
		
		$primer_dia=$this->db->query($sql)->row_array();		
		
		$this->db->trans_complete();
		$this->db->close();
		return $primer_dia['f_dia_calendario'];
	}
	
	public function ultimo_dia_calendario()
	{
		$this->load->database();
		$this->db->trans_start();
	
		$sql ="SELECT f_dia_calendario FROM t_calendario ORDER BY f_dia_calendario DESC LIMIT 1";
	
		$ultimo_dia=$this->db->query($sql)->row_array();
	
		$this->db->trans_complete();
		$this->db->close();
				
		return $ultimo_dia['f_dia_calendario'];
	}
	
	public function get_usuario_by_k_permiso_solic($k_permiso_solic)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT k_consultor FROM t_permisos_solicitados WHERE k_permisos_solic=$k_permiso_solic";
		
		$k_consultor=$this->db->query($sql)->result_array();
		
		
		$this->db->trans_complete();
		$this->db->close();
		return $k_consultor[0]['k_consultor'];
	}
	
	
	
	//===================================VERSIONES PRODUCCION============(CON MOTIVO DE LAS "EÑES" EN POSTGRE)
	
	
	 //COGE LOS PERMISOS ACEPTADOS DE UN MES PARA LA AYUDA DEL IMC
	 public function cargar_datos_para_imc($k_consultor,$year,$mes)//VERSION PROD
	 {
	 $this->load->database();
	 $this->db->trans_start();
	
	
	 $sql ="SELECT A.horas_cons horas_solic,A.dia_cons dia_solic,B.k_proyecto,A.desc_observaciones
	 FROM t_permisos_consumidos_det A
	 JOIN t_permisos_consumidos B
	 ON A.k_permisos_cons=B.k_permisos_cons
	 where B.k_consultor ='$k_consultor' and B.mes_cons='$mes' and B.\"año_cons\"='$year'
	 ORDER BY dia_solic";
	
	 $datos_permisos_para_imc=$this->db->query($sql)->result_array();
	
	
	 $this->db->trans_complete();
	 $this->db->close();
	 return $datos_permisos_para_imc;
	 }
	
	

	 //CALCULA LOS DIAS PENDIENTE QUE TIENE EL USUARIO Y DEVUELVE TAMBIEN LOS QUE LE COREEPONDEN
	 public function cargar_dias_debidos($k_consultor,$year_solicitud,$k_permiso_solic=0)//VERSION PROD
	 {
	 //A.k_permisos_solic!=$k_permiso_solic  es para no sumar los dias de una solicitud que se va a editar
	
	 //calculamos los dias totales de vacaciones que tiene y le restamos los que estan en consumidos(aprobados) y los que tenga pendiente de aprobar
	 //Eso para el año anterior, el actual y el siguiente.
	
	
	
	 $this->load->database();
	 $this->db->trans_start();
	
	 //====================TOTALES======================
	 $sql ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
	 coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones
	 WHERE k_consultor=$k_consultor AND \"año_vac\"=$year_solicitud AND sw_validado=-1";
	
	 $total_vac_este=$this->db->query($sql)->row_array();
	
	 $year_anterior=$year_solicitud-1;
	 $year_siguiente=$year_solicitud+1;
	
	 $sql2 ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
	 coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones
	 WHERE k_consultor=$k_consultor AND \"año_vac\"=$year_anterior AND sw_validado=-1";
	
	 $total_vac_anterior=$this->db->query($sql2)->row_array();
	
	
	 $sql2bis ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
	 coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones
	 WHERE k_consultor=$k_consultor AND \"año_vac\"=$year_siguiente AND sw_validado=-1";
	
	 $total_vac_siguiente=$this->db->query($sql2bis)->row_array();
	
	
	 //====================CONSUMIDOS Y PENDIENTES======================
	
	 //SOLO CONTAMOS LOS DIAS QUE NO ESTEN RECHAZADOS Y QUE NO SEAN DE ESTA SOLICITUD PORQUE ESTOS ULTIMOS LUEGO HACEMOS UNA SELECCION EN EL CALENDARIO
	 //AL CARGARLO Y LOS DESCUENTA
	
	 $sql3 ="SELECT count(*) suma_consumidos FROM t_permisos_consumidos_det A
	 join t_permisos_consumidos B on A.k_permisos_cons=B.k_permisos_cons
	 WHERE A.\"año_vac\"=$year_solicitud AND B.k_consultor=$k_consultor";
	
	 $consumidos_este=$this->db->query($sql3)->row_array();
	
	 $sql3_pendientes_aprob ="SELECT count(*) suma_pendientes FROM t_permisos_solicitados_det A
	 join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
	 WHERE A.\"año_vac\"=$year_solicitud AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2=0 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
	 $pendientes_este=$this->db->query($sql3_pendientes_aprob)->row_array();
	
	
	
	
	 //SOLO CONTAMOS LOS DIAS QUE NO ESTEN RECHAZADOS Y QUE NO SEAN DE ESTA SOLICITUD PORQUE ESTOS ULTIMOS LUEGO HACEMOS UNA SELECCION EN EL CALENDARIO
	 //AL CARGARLO Y LOS DESCUENTA
	
	 $sql4 ="SELECT count(*) suma_consumidos FROM t_permisos_consumidos_det A
	 join t_permisos_consumidos B on A.k_permisos_cons=B.k_permisos_cons
	 WHERE A.\"año_vac\"=$year_anterior AND B.k_consultor=$k_consultor";
	
	 $consumidos_anterior=$this->db->query($sql4)->row_array();
	
	 $sql4_pendientes_aprob ="SELECT count(*) suma_pendientes FROM t_permisos_solicitados_det A
	 join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
	 WHERE A.\"año_vac\"=$year_anterior AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2=0 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
	 $pendientes_anterior=$this->db->query($sql4_pendientes_aprob)->row_array();
	
	
	
	
	
	 $sql5 ="SELECT count(*) suma_consumidos FROM t_permisos_consumidos_det A
	 join t_permisos_consumidos B on A.k_permisos_cons=B.k_permisos_cons
	 WHERE A.\"año_vac\"=$year_siguiente AND B.k_consultor=$k_consultor";
	
	 $consumidos_siguiente=$this->db->query($sql5)->row_array();
	
	 $sql6 ="SELECT count(*) suma_pendientes FROM t_permisos_solicitados_det A
	 join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
	 WHERE A.\"año_vac\"=$year_siguiente AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2=0 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
	 $pendientes_siguiente=$this->db->query($sql6)->row_array();
	
	
	
	
	 $sql5 ="SELECT count(*) suma_consumidos FROM t_permisos_solicitados_det A
	 join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
	 WHERE A.\"año_vac\"=$year_siguiente AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2!=2 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
	 $consumidos_siguiente=$this->db->query($sql5)->row_array();
	
	 $dias['dias_debidos']=$total_vac_este['suma_dias']-$consumidos_este['suma_consumidos']-$pendientes_este['suma_pendientes'];
	 $dias['dias_debidos_pendientes']=$total_vac_anterior['suma_dias']-$consumidos_anterior['suma_consumidos']-$pendientes_anterior['suma_pendientes'];
	 $dias['dias_debidos_futuro']=$total_vac_siguiente['suma_dias']-$consumidos_siguiente['suma_consumidos']-$pendientes_siguiente['suma_pendientes'];
	
	 $dias['dias_base']=$total_vac_este['suma_dias'];
	 $dias['dias_base_anterior']=$total_vac_anterior['suma_dias'];
	 $dias['dias_base_siguiente']=$total_vac_siguiente['suma_dias'];
	
	 $this->db->trans_complete();
	 $this->db->close();
	
	
	 return $dias;
	 }
	 
	
	
	
	 //SELECCIONA TOODOS LOS DIAS SOLICITADOS POR EL USUARIO
	 public function cargar_dias_solicitados($k_consultor,$k_permiso_solicitud_calendario)//VERSION PROD
	 {
	
	 $this->load->database();
	 $this->db->trans_start();
	
	 $sql;
	 	
	 //NUEVA SOLICITUD O VISTA DE TODAS LAS VACAS(SELECCIONAMOS DE CONSUMIDOS LOS ACEPTADOS Y DE SOLICITADOS LOS DEMAS)
	 if($k_permiso_solicitud_calendario==0)
	 {
	 	
	 $sql ="SELECT A.dia_solic,A.mes_solic,A.\"año_solic\" year_solic,B.i_autorizado_n1,B.i_autorizado_n2,\"año_vac\" year_vac,
	 A.k_permisos_solic,A.horas_solic ,B.desc_observaciones
	 FROM t_permisos_solicitados_det A
	 join t_permisos_solicitados B
	 on A.k_permisos_solic=B.k_permisos_solic
	 where B.k_consultor ='$k_consultor' AND (i_autorizado_n1!=1 OR i_autorizado_n2!=1)
	 UNION ALL
	 SELECT A.dia_cons dia_solic,B.mes_cons mes_solic,B.\"año_cons\" year_solic,1 i_autorizado_n1,1 i_autorizado_n2,\"año_vac\" year_vac,
	 9223372036854775807 k_permisos_solic,A.horas_cons horas_solic ,A.desc_observaciones
	 FROM t_permisos_consumidos_det A
	 JOIN t_permisos_consumidos B
	 ON A.k_permisos_cons=B.k_permisos_cons
	 where B.k_consultor ='$k_consultor'
	 ORDER BY year_solic, mes_solic,dia_solic,k_permisos_solic";
	 }
	 else//SOLOVISTA PARA UN k_permisos EN CONCRETO
	 {
	 $sql ="SELECT A.dia_solic,A.mes_solic,A.\"año_solic\" year_solic,B.i_autorizado_n1,B.i_autorizado_n2,\"año_vac\" year_vac,A.k_permisos_solic,A.horas_solic ,B.desc_observaciones
	 FROM t_permisos_solicitados_det A
	 join t_permisos_solicitados B
	 on A.k_permisos_solic=B.k_permisos_solic
	 where B.k_consultor ='$k_consultor' AND B.k_permisos_solic=$k_permiso_solicitud_calendario
	 ORDER BY year_solic, mes_solic,dia_solic,k_permisos_solic";
	 	
	 }
	
	 $diasSolicitados=$this->db->query($sql)->result_array();
	 	
	
	 $this->db->trans_complete();
	 $this->db->close();
	 return $diasSolicitados;
	
	 }
	 
	
	

	
	 //CARGA DE DATOS PARA LA PANTALLA GENERAL DE PERMISOS Y PINTAR EL HISTORICO INFERIOR
	 /*
	 public  function cargar_historico_permisos($k_consultor)//VERSION PROD
	 {
	 $this->load->database();
	 $this->db->trans_start();
	
	 //fecha_formato_original solo lo uso para ordenar
	 $sql ="SELECT k_permisos_solic, to_char(f_solicitud, 'DD-MM-YYYY') f_solicitud,f_solicitud as fecha_formato_original,C.id_consultor,A.k_proyecto,id_proyecto,
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
	 desc_observaciones,COALESCE(desc_rechazo,'') desc_rechazo,sw_envio_solicitud
	 FROM t_permisos_solicitados A
	 JOIN t_proyectos B on A.k_proyecto=B.k_proyecto
	 JOIN t_consultores C on A.k_consultor_solic=C.k_consultor
	 WHERE A.k_consultor=$k_consultor
	 ORDER BY fecha_formato_original DESC, k_permisos_solic DESC";
	
	
	
	 $permisos=$this->db->query($sql)->result_array();
	
	
	 for($i=0;$i<sizeof($permisos);$i++)
	 {
		 $sql ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',\"año_solic\") primera_fecha FROM t_permisos_solicitados_det
		 WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} ORDER BY \"año_solic\",mes_solic,dia_solic ASC LIMIT 1";
		
		 $primer_dia=$this->db->query($sql)->result_array();
		
		 $sql2 ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',\"año_solic\") ultima_fecha FROM t_permisos_solicitados_det
		 WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']}  ORDER BY \"año_solic\" DESC,mes_solic DESC,dia_solic DESC LIMIT 1";
		 	
		 $ultimo_dia=$this->db->query($sql2)->result_array();
		 	
		 $sql3 ="SELECT COUNT(*) numero_dias FROM t_permisos_solicitados_det
		 WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']}";
		
		 $numero_dias=$this->db->query($sql3)->result_array();
		 	
		 $permisos[$i]['primer_dia']=$primer_dia[0]['primera_fecha'];
		 $permisos[$i]['ultimo_dia']=$ultimo_dia[0]['ultima_fecha'];
		 $permisos[$i]['numero_dias']=$numero_dias[0]['numero_dias'];
	 }
	 	
	 $this->db->trans_complete();
	 $this->db->close();
	 
	
	 return $permisos;
	 }
	 */
	 
	 //CARGA DE DATOS PARA LA PANTALLA GENERAL DE PERMISOS Y PINTAR EL HISTORICO INFERIOR
	 public  function cargar_historico_permisos($k_consultor)//VERSION DESARROLLO
	 {
	 	$this->load->database();
	 	$this->db->trans_start();
	 
	 	//fecha_formato_original solo lo uso para ordenar
	 	$sql ="SELECT k_permisos_solic, to_char(f_solicitud, 'DD-MM-YYYY') f_solicitud,f_solicitud as fecha_formato_original,C.id_consultor,A.k_proyecto,id_proyecto,
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
	 	desc_observaciones,COALESCE(desc_rechazo,'') desc_rechazo,sw_envio_solicitud
	 	FROM t_permisos_solicitados A
	 	JOIN t_proyectos B on A.k_proyecto=B.k_proyecto
	 	JOIN t_consultores C on A.k_consultor_solic=C.k_consultor
	 	WHERE A.k_consultor=$k_consultor
	 	ORDER BY fecha_formato_original DESC, k_permisos_solic DESC";
	 
	 
	 
	 	$permisos=$this->db->query($sql)->result_array();
	 
	 
	 	for($i=0;$i<sizeof($permisos);$i++)
	 	{
	 		$sql_orden ="SELECT CONCAT(\"año_solic\",mes_solic,dia_solic) fecha_para_ordenar FROM t_permisos_solicitados_det
	 		WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} ORDER BY \"año_solic\",mes_solic,dia_solic ASC LIMIT 1";
	 
	 		$dia_ordenar=$this->db->query($sql_orden)->result_array();
	 
	 		$permisos[$i]['dia_orden']=$dia_ordenar[0]['fecha_para_ordenar'];
	 	}
	 
	 	//ordeno el array por el primer dia
	 	usort($permisos, function($a, $b) {
	 		return $b['dia_orden'] - $a['dia_orden'];
	 	});
	 
	 
	 		for($i=0;$i<sizeof($permisos);$i++)
	 		{
	 			$sql ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',\"año_solic\") primera_fecha FROM t_permisos_solicitados_det
	 			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} ORDER BY \"año_solic\",mes_solic,dia_solic ASC LIMIT 1";
	 
	 			$primer_dia=$this->db->query($sql)->result_array();
	 
	 			$sql2 ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',\"año_solic\") ultima_fecha FROM t_permisos_solicitados_det
	 			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']}  ORDER BY \"año_solic\" DESC,mes_solic DESC,dia_solic DESC LIMIT 1";
	 
	 			$ultimo_dia=$this->db->query($sql2)->result_array();
	 
	 			$sql3 ="SELECT COUNT(*) numero_dias FROM t_permisos_solicitados_det
	 			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']}";
	 
	 			$numero_dias=$this->db->query($sql3)->result_array();
	 			
	 			
	 			
	 			//CONTAMOS EL NUMERO DE DIAS DEL AÑO ANTERIOR
	 			$sql4 ="SELECT COUNT(*) numero_dias_year_anterior FROM t_permisos_solicitados_det
	 			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} AND \"año_vac\"<CAST(\"año_solic\" AS INTEGER)";
	 				
	 			$numero_dias_year_anterior=$this->db->query($sql4)->row_array();
	 			
	 			
	 			//CONTAMOS EL NUMERO DE DIAS DEL AÑO SIGUIENTE
	 			$sql5 ="SELECT COUNT(*) numero_dias_year_siguiente FROM t_permisos_solicitados_det
	 			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} AND \"año_vac\">CAST(\"año_solic\" AS INTEGER)";
	 			
	 			$numero_dias_year_siguiente=$this->db->query($sql5)->row_array();
	 			
	 
	 			$permisos[$i]['primer_dia']=$primer_dia[0]['primera_fecha'];
	 			$permisos[$i]['ultimo_dia']=$ultimo_dia[0]['ultima_fecha'];
	 			$permisos[$i]['numero_dias']=$numero_dias[0]['numero_dias'];
	 				
	 			//SI HAY DIAS DEL AÑO ANTERIOR FORMATEAMOS LA INFORMACION PARA MOSTRARSELO AL USUARIO
	 			if($numero_dias_year_anterior['numero_dias_year_anterior']>0)
	 			{
	 				if($numero_dias_year_anterior['numero_dias_year_anterior']!=$permisos[$i]['numero_dias'])
	 				{
	 					$permisos[$i]['numero_dias']=$numero_dias_year_anterior['numero_dias_year_anterior'].'*+'.($permisos[$i]['numero_dias']-$numero_dias_year_anterior['numero_dias_year_anterior']);
	 				}
	 				else
	 				{
	 					$permisos[$i]['numero_dias']=$numero_dias_year_anterior['numero_dias_year_anterior'].'*';
	 				}	 
	 			}
	 			
	 			
	 			//SI HAY DIAS DEL AÑO SIGUIENTE FORMATEAMOS LA INFORMACION PARA MOSTRARSELO AL USUARIO
	 			if($numero_dias_year_siguiente['numero_dias_year_siguiente']>0)
	 			{
	 				if($numero_dias_year_siguiente['numero_dias_year_siguiente']!=$permisos[$i]['numero_dias'])
	 				{
	 					$permisos[$i]['numero_dias']=($permisos[$i]['numero_dias']-$numero_dias_year_siguiente['numero_dias_year_siguiente'])."+".$numero_dias_year_siguiente['numero_dias_year_siguiente'].'#';
	 				}
	 				else
	 				{
	 					$permisos[$i]['numero_dias']=$numero_dias_year_siguiente['numero_dias_year_siguiente'].'#';
	 				}	 			
	 			}
	 			
	 			
	 			
	 				
	 		}
	 			
	 		$this->db->trans_complete();
	 		$this->db->close();
	 
	 		return $permisos;
	 }
	
	


	 public function grabar_solicitud($datos_guardar)//VERSION PROD
	 {
	 $fechaActual = date('Y-m-d');
	
	 $this->load->database();
	 $this->db->trans_start();
	
	
	 $id_responsable=$this->db->query("SELECT id_consultor FROM t_consultores WHERE k_consultor={$datos_guardar['responsable_solicitud']}")->row_array();
	
	 //NUEVA SOLICITUD
	 //PASA SEGURO POR AQUI
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
	
	 //FORMATO DD-MM-YYYY, DD-MM-YYYY,DD-MM-YYYY
	 $array_dias =explode(", ", $datos_guardar['dias_solicitados']);
	
	 //FORMATO X X X X
	 $array_horas =explode(" ", $datos_guardar['horas_por_dias']);
	 	
	
	
	 for($i=0;$i<sizeof($array_dias);$i++)
	 {
	 	$horas;
	 	
		 //NUMERO DE HORAS SI ES KEYVACACIONES(SERA SIEMPRE IGUAL)
		 if($datos_guardar['k_proyecto_solicitud']==450)
		 {
		 $horas=$datos_guardar['horas_jornada'];
		 }
	 	
		 //NUMERO DE HORAS SI ES KEYOTROS(COGEMOS LA POSICION DEL ARRAY SEGUN EL BUCLE)
		 if($datos_guardar['k_proyecto_solicitud']==468)
		 {
		 $horas=$array_horas[$i];
		 }
	 	
	 	
		 $dia_partido=explode("-", $array_dias[$i]);
		 $dia=$dia_partido[0];
		 $mes=$dia_partido[1];
		 $year=$dia_partido[2];
	 	
	 	
		 $year_vac;
		 //$year_vac=($datos_guardar['diasPendientesDebidos']>0)?$datos_guardar['year_solicitud']-1:$datos_guardar['year_solicitud'];
		 	
		 if($datos_guardar['diasPendientesDebidos']>0)
		 {
		 $year_vac=$datos_guardar['year_solicitud']-1;
		 }
		 else if($datos_guardar['diasPendientes']>0)
		 {
		 $year_vac=$datos_guardar['year_solicitud'];
		 }
		 else
		 {
		 $year_vac=$datos_guardar['year_solicitud']+1;
		 }
	 		 
	 	
		 if($datos_guardar['k_proyecto_solicitud']==468)
		 {
		 $year_vac=null;
		 }
		
		 //INSERT
		 $data = array(
		 'k_permisos_solic'      =>   $datos_guardar['k_permisos_solic'],
		 'horas_solic'         	=>   $horas,
		 'dia_solic' 			=>   $dia,
		 'mes_solic'      		=>   $mes,
		 'año_solic'  	 	=>   $year,
		 'año_vac'   		=>   $year_vac,
		 );
	
		 $resultado=$this->db->insert('t_permisos_solicitados_det',$data);
		 	
		 if($datos_guardar['diasPendientesDebidos']>0)
		 {
		 $datos_guardar['diasPendientesDebidos']--;
		 }
		 else if($datos_guardar['diasPendientes']>0)
		 {
		 $datos_guardar['diasPendientes']--;
		 }
		 else
		 {
		 $datos_guardar['diasPendientesFuturo']--;
		 }
		
		 //$datos_guardar['diasPendientesDebidos']--;
		 	
		 	
		 //FIN INSERT
	
	
	 }
	
	
	 $this->db->trans_complete();
	 $this->db->close();
	
	 return $datos_guardar['k_permisos_solic'];
	
	
	 }
	 
	
	


	
	 public function grabar_solicitud_editado($datos_guardar)//VERSION PROD
	 {
	 $fechaActual = date('Y-m-d');
	
	 $this->load->database();
	 $this->db->trans_start();
	
	
	 $id_responsable=$this->db->query("SELECT id_consultor FROM t_consultores WHERE k_consultor={$datos_guardar['responsable_solicitud']}")->row_array();
	
	
	 $fechaActual = date('Y-m-d');
	
	 $data = array(
	 'k_consultor_solic'     =>   $datos_guardar['k_consultor_solic'],
	 'f_solicitud' 	 		=>   $fechaActual,
	 'desc_observaciones'   	=>   $datos_guardar['observaciones'],
	 );
	 	
	 $array = array('k_permisos_solic' => $datos_guardar['k_permisos_solic'], 'i_autorizado_n1' => 0, 'i_autorizado_n2' => 0);
	
	 $this->db->where($array);
	
	 // Produces: WHERE name = 'Joe' AND title = 'boss' AND status = 'active'
	
	 //$this->db->where('k_permisos_solic', $datos_guardar['k_permisos_solic']);
	 $this->db->update('t_permisos_solicitados', $data);
	
	
	 //BORRO TOODOS LOS DATOS Y LOS INSERTO DE NUEVO
	
	 $this->db->delete('t_permisos_solicitados_det', array('k_permisos_solic' => $datos_guardar['k_permisos_solic']));
	
	 // Produces:
	 // DELETE FROM t_permisos_solic_det
	 // WHERE t_permisos_solic = $datos_guardar['k_permisos_solic']
	
	
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
	
	 $year_vac;
	 //$year_vac=($datos_guardar['diasPendientesDebidos']>0)?$datos_guardar['year_solicitud']-1:$datos_guardar['year_solicitud'];
	 	
	 //SEGUN LE QUEDEN DIAS PENDIENTES EN UN AÃ‘O O EN OTRO LE PONEMOS ESE VALOR EN AÃ‘O_VAC DE LA BBD
	 if($datos_guardar['diasPendientesDebidos']>0)
	 {
	 $year_vac=$datos_guardar['year_solicitud']-1;
	 }
	 else if($datos_guardar['diasPendientes']>0)
	 {
	 $year_vac=$datos_guardar['year_solicitud'];
	 }
	 else
	 {
	 $year_vac=$datos_guardar['year_solicitud']+1;
	 }
	 	
	 	
	 //SI ES KEYOTROS LO PONEMOS NULL PORQUE NO CUENTA COMO DIA
	 if($datos_guardar['k_proyecto_solicitud']==468)
	 {
	 $year_vac=null;
	 }
	 	
	 //INSERT
	 	
	 $data = array(
	 'k_permisos_solic'      =>   $datos_guardar['k_permisos_solic'],
	 'horas_solic'         	=>   $horas,
	 'dia_solic' 			=>   $dia,
	 'mes_solic'      		=>   $mes,
	 'año_solic' 	 	=>   $year,
	 'año_vac'   		=>   $year_vac,
	 );
	 	
	 //RESTAMOS DIAS AL CONTADOR DE DIAS PENDIENTES SEGUN POR EL AÃ‘O QUE VAYAMOS
	 $resultado=$this->db->insert('t_permisos_solicitados_det',$data);
	 	
	 if($datos_guardar['diasPendientesDebidos']>0)
	 {
	 $datos_guardar['diasPendientesDebidos']--;
	 }
	 else if($datos_guardar['diasPendientes']>0)
	 {
	 $datos_guardar['diasPendientes']--;
	 }
	 else
	 {
	 $datos_guardar['diasPendientesFuturo']--;
	 }
	 	
	 //$datos_guardar['diasPendientesDebidos']--;
	 	
	 	
	 //FIN INSERT
	
	 	
	 }
	
	 $this->db->trans_complete();
	 $this->db->close();
	
	 return $datos_guardar['k_permisos_solic'];
	
	 }
	 
	
	 
	
	
	//===================================FINAL VERSIONES PRODUCCION============(CON MOTIVO DE LAS "EÑES" EN POSTGRE)
	
	
	//===================================VERSIONES DESARROLLO============(CON MOTIVO DE LAS "EÑES" EN POSTGRE)
	
	 /*
	 
	 
	//COGE LOS PERMISOS ACEPTADOS DE UN MES PARA LA AYUDA DEL IMC
	public function cargar_datos_para_imc($k_consultor,$year,$mes)//VERSION DESARROLLO
	{
		$this->load->database();
		$this->db->trans_start();
	
	
		$sql ="SELECT A.horas_cons horas_solic,A.dia_cons dia_solic,B.k_proyecto,A.desc_observaciones
		FROM t_permisos_consumidos_det A
		JOIN t_permisos_consumidos B
		ON A.k_permisos_cons=B.k_permisos_cons
		where B.k_consultor ='$k_consultor' and B.mes_cons='$mes' and B.año_cons='$year'
		ORDER BY dia_solic";
	
	
		$datos_permisos_para_imc=$this->db->query($sql)->result_array();
	
	
		$this->db->trans_complete();
		$this->db->close();
		return $datos_permisos_para_imc;
	}
	
	
	
	
	

	public function cargar_dias_debidos($k_consultor,$year_solicitud,$k_permiso_solic=0)//VERSION DESARROLLO
	{
		//A.k_permisos_solic!=$k_permiso_solic  es para no sumar los dias de una solicitud que se va a editar
	
		//calculamos los dias totales de vacaciones que tiene y le restamos los que estan en consumidos(aprobados) y los que tenga pendiente de aprobar
		//Eso para el año anterior, el actual y el siguiente.
	
	
	
		$this->load->database();
		$this->db->trans_start();
	
		//====================TOTALES======================
		$sql ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
		coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones
		WHERE k_consultor=$k_consultor AND año_vac=$year_solicitud AND sw_validado=-1";
	
		$total_vac_este=$this->db->query($sql)->row_array();
	
		$year_anterior=$year_solicitud-1;
		$year_siguiente=$year_solicitud+1;
	
		$sql2 ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
		coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones
		WHERE k_consultor=$k_consultor AND año_vac=$year_anterior AND sw_validado=-1";
	
		$total_vac_anterior=$this->db->query($sql2)->row_array();
	
	
		$sql2bis ="SELECT (coalesce(dias_vac_base,0)+coalesce(dias_vac_compensables,0)+coalesce(dias_vac_especiales,0)+
		coalesce(dias_vac_jornada_verano,0)+coalesce(dias_vac_resto,0)) suma_dias FROM t_permisos_vacaciones
		WHERE k_consultor=$k_consultor AND año_vac=$year_siguiente AND sw_validado=-1";
	
		$total_vac_siguiente=$this->db->query($sql2bis)->row_array();
	
	
		//====================CONSUMIDOS Y PENDIENTES======================
	
		//SOLO CONTAMOS LOS DIAS QUE NO ESTEN RECHAZADOS Y QUE NO SEAN DE ESTA SOLICITUD PORQUE ESTOS ULTIMOS LUEGO HACEMOS UNA SELECCION EN EL CALENDARIO
		//AL CARGARLO Y LOS DESCUENTA
	
		$sql3 ="SELECT count(*) suma_consumidos FROM t_permisos_consumidos_det A
		join t_permisos_consumidos B on A.k_permisos_cons=B.k_permisos_cons
		WHERE A.año_vac=$year_solicitud AND B.k_consultor=$k_consultor";
	
		$consumidos_este=$this->db->query($sql3)->row_array();
	
		$sql3_pendientes_aprob ="SELECT count(*) suma_pendientes FROM t_permisos_solicitados_det A
		join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
		WHERE A.año_vac=$year_solicitud AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2=0 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
		$pendientes_este=$this->db->query($sql3_pendientes_aprob)->row_array();
	
	
	
	
		//SOLO CONTAMOS LOS DIAS QUE NO ESTEN RECHAZADOS Y QUE NO SEAN DE ESTA SOLICITUD PORQUE ESTOS ULTIMOS LUEGO HACEMOS UNA SELECCION EN EL CALENDARIO
		//AL CARGARLO Y LOS DESCUENTA
	
		$sql4 ="SELECT count(*) suma_consumidos FROM t_permisos_consumidos_det A
		join t_permisos_consumidos B on A.k_permisos_cons=B.k_permisos_cons
		WHERE A.año_vac=$year_anterior AND B.k_consultor=$k_consultor";
	
		$consumidos_anterior=$this->db->query($sql4)->row_array();
	
		$sql4_pendientes_aprob ="SELECT count(*) suma_pendientes FROM t_permisos_solicitados_det A
		join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
		WHERE A.año_vac=$year_anterior AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2=0 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
		$pendientes_anterior=$this->db->query($sql4_pendientes_aprob)->row_array();
	
	
	
	
	
		$sql5 ="SELECT count(*) suma_consumidos FROM t_permisos_consumidos_det A
		join t_permisos_consumidos B on A.k_permisos_cons=B.k_permisos_cons
		WHERE A.año_vac=$year_siguiente AND B.k_consultor=$k_consultor";
	
		$consumidos_siguiente=$this->db->query($sql5)->row_array();
	
		$sql6 ="SELECT count(*) suma_pendientes FROM t_permisos_solicitados_det A
		join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
		WHERE A.año_vac=$year_siguiente AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2=0 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
		$pendientes_siguiente=$this->db->query($sql6)->row_array();
	
	
	
	
		$sql5 ="SELECT count(*) suma_consumidos FROM t_permisos_solicitados_det A
		join t_permisos_solicitados B on A.k_permisos_solic=B.k_permisos_solic
		WHERE A.año_vac=$year_siguiente AND B.i_autorizado_n1!=2 AND B.i_autorizado_n2!=2 AND A.k_permisos_solic!=$k_permiso_solic AND B.k_consultor=$k_consultor";
	
		$consumidos_siguiente=$this->db->query($sql5)->row_array();
	
		$dias['dias_debidos']=$total_vac_este['suma_dias']-$consumidos_este['suma_consumidos']-$pendientes_este['suma_pendientes'];
		$dias['dias_debidos_pendientes']=$total_vac_anterior['suma_dias']-$consumidos_anterior['suma_consumidos']-$pendientes_anterior['suma_pendientes'];
		$dias['dias_debidos_futuro']=$total_vac_siguiente['suma_dias']-$consumidos_siguiente['suma_consumidos']-$pendientes_siguiente['suma_pendientes'];
	
		$dias['dias_base']=$total_vac_este['suma_dias'];
		$dias['dias_base_anterior']=$total_vac_anterior['suma_dias'];
		$dias['dias_base_siguiente']=$total_vac_siguiente['suma_dias'];
	
		$this->db->trans_complete();
		$this->db->close();
	
	
		return $dias;
	}
	
	


	//SELECCIONA TOODOS LOS DIAS SOLICITADOS POR EL USUARIO
	public function cargar_dias_solicitados($k_consultor,$k_permiso_solicitud_calendario)//VERSION DESARROLLO
	{
	
		$this->load->database();
		$this->db->trans_start();
	
		$sql;
			
		//NUEVA SOLICITUD O VISTA DE TODAS LAS VACAS(SELECCIONAMOS DE CONSUMIDOS LOS ACEPTADOS Y DE SOLICITADOS LOS DEMAS)
		if($k_permiso_solicitud_calendario==0)
		{
	
			$sql ="SELECT A.dia_solic,A.mes_solic,A.año_solic year_solic,B.i_autorizado_n1,B.i_autorizado_n2,
			A.k_permisos_solic,A.horas_solic ,B.desc_observaciones, año_vac year_vac
			FROM t_permisos_solicitados_det A
			join t_permisos_solicitados B
			on A.k_permisos_solic=B.k_permisos_solic
			where B.k_consultor ='$k_consultor' AND i_autorizado_n2!=1
			UNION ALL
			SELECT A.dia_cons dia_solic,B.mes_cons mes_solic,B.año_cons year_solic,1 i_autorizado_n1,1 i_autorizado_n2,
			9223372036854775807 k_permisos_solic,A.horas_cons horas_solic ,A.desc_observaciones, año_vac year_vac
			FROM t_permisos_consumidos_det A
			JOIN t_permisos_consumidos B
			ON A.k_permisos_cons=B.k_permisos_cons
			where B.k_consultor ='$k_consultor'
			ORDER BY year_solic, mes_solic,dia_solic,k_permisos_solic";
		}
		else//SOLOVISTA PARA UN k_permisos EN CONCRETO
		{
			$sql ="SELECT A.dia_solic,A.mes_solic,A.año_solic year_solic,B.i_autorizado_n1,B.i_autorizado_n2,A.k_permisos_solic,A.horas_solic ,B.desc_observaciones, año_vac year_vac
			FROM t_permisos_solicitados_det A
			join t_permisos_solicitados B
			on A.k_permisos_solic=B.k_permisos_solic
			where B.k_consultor ='$k_consultor' AND B.k_permisos_solic=$k_permiso_solicitud_calendario
			ORDER BY year_solic, mes_solic,dia_solic,k_permisos_solic";
	
		}
	
		$diasSolicitados=$this->db->query($sql)->result_array();			
	
		$this->db->trans_complete();
		$this->db->close();
		return $diasSolicitados;
	
	}
	
	
	

	//CARGA DE DATOS PARA LA PANTALLA GENERAL DE PERMISOS Y PINTAR EL HISTORICO INFERIOR
	public  function cargar_historico_permisos($k_consultor)//VERSION DESARROLLO
	{
		$this->load->database();
		$this->db->trans_start();
	
	    //fecha_formato_original solo lo uso para ordenar
		$sql ="SELECT k_permisos_solic, to_char(f_solicitud, 'DD-MM-YYYY') f_solicitud,f_solicitud as fecha_formato_original,C.id_consultor,A.k_proyecto,id_proyecto,
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
		desc_observaciones,COALESCE(desc_rechazo,'') desc_rechazo,sw_envio_solicitud
		FROM t_permisos_solicitados A
		JOIN t_proyectos B on A.k_proyecto=B.k_proyecto
		JOIN t_consultores C on A.k_consultor_solic=C.k_consultor
		WHERE A.k_consultor=$k_consultor
		ORDER BY fecha_formato_original DESC, k_permisos_solic DESC";
	
	
	
		$permisos=$this->db->query($sql)->result_array();
	
		
		for($i=0;$i<sizeof($permisos);$i++)
		{
			$sql_orden ="SELECT CONCAT(año_solic,mes_solic,dia_solic) fecha_para_ordenar FROM t_permisos_solicitados_det
			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} ORDER BY año_solic,mes_solic,dia_solic ASC LIMIT 1";
		
			$dia_ordenar=$this->db->query($sql_orden)->result_array();			
		
			$permisos[$i]['dia_orden']=$dia_ordenar[0]['fecha_para_ordenar'];
		}
		
		//ordeno el array por el primer dia
		usort($permisos, function($a, $b) {
			return $b['dia_orden'] - $a['dia_orden'];
		});
		
	
		for($i=0;$i<sizeof($permisos);$i++)
		{
			$sql ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',año_solic) primera_fecha FROM t_permisos_solicitados_det
			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} ORDER BY año_solic,mes_solic,dia_solic ASC LIMIT 1";
	
			$primer_dia=$this->db->query($sql)->result_array();
	
			$sql2 ="SELECT CONCAT(dia_solic,'-',mes_solic,'-',año_solic) ultima_fecha FROM t_permisos_solicitados_det
			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']}  ORDER BY año_solic DESC,mes_solic DESC,dia_solic DESC LIMIT 1";
	
			$ultimo_dia=$this->db->query($sql2)->result_array();
	
			$sql3 ="SELECT COUNT(*) numero_dias FROM t_permisos_solicitados_det
			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']}";
	
			$numero_dias=$this->db->query($sql3)->result_array();
			
			//CONTAMOS EL NUMERO DE DIAS DEL AÑO ANTERIOR
			$sql4 ="SELECT COUNT(*) numero_dias_year_anterior FROM t_permisos_solicitados_det
			WHERE k_permisos_solic={$permisos[$i]['k_permisos_solic']} AND año_vac<CAST(año_solic AS INTEGER)";
			
			$numero_dias_year_anterior=$this->db->query($sql4)->row_array();
	
			$permisos[$i]['primer_dia']=$primer_dia[0]['primera_fecha'];
			$permisos[$i]['ultimo_dia']=$ultimo_dia[0]['ultima_fecha'];
			$permisos[$i]['numero_dias']=$numero_dias[0]['numero_dias'];
			
			//SI HAY DIAS DEL AÑO ANTERIOR FORMATEAMOS LA INFORMACION PARA MOSTRARSELO AL USUARIO
			if($numero_dias_year_anterior['numero_dias_year_anterior']>0)
			{
				if($numero_dias_year_anterior['numero_dias_year_anterior']!=$permisos[$i]['numero_dias'])
				{
					$permisos[$i]['numero_dias']=$numero_dias_year_anterior['numero_dias_year_anterior'].'*+'.($permisos[$i]['numero_dias']-$numero_dias_year_anterior['numero_dias_year_anterior']);
				}
				else
				{
					$permisos[$i]['numero_dias']=$numero_dias_year_anterior['numero_dias_year_anterior'].'*';
				}
				
			}
			
		}
			
		$this->db->trans_complete();
		$this->db->close();
	
		return $permisos;
	}
	
	

	public function grabar_solicitud($datos_guardar)//VERSION DESARROLLO
	{
		$fechaActual = date('Y-m-d');
	
		$this->load->database();
		$this->db->trans_start();
	
	
		$id_responsable=$this->db->query("SELECT id_consultor FROM t_consultores WHERE k_consultor={$datos_guardar['responsable_solicitud']}")->row_array();
	
		//NUEVA SOLICITUD
		//PASA SEGURO POR AQUI
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
	
		//FORMATO DD-MM-YYYY, DD-MM-YYYY,DD-MM-YYYY
		$array_dias =explode(", ", $datos_guardar['dias_solicitados']);
	
		//FORMATO X X X X
		$array_horas =explode(" ", $datos_guardar['horas_por_dias']);
			
	
	
		for($i=0;$i<sizeof($array_dias);$i++)
		{
			$horas;
	
			//NUMERO DE HORAS SI ES KEYVACACIONES(SERA SIEMPRE IGUAL)
			if($datos_guardar['k_proyecto_solicitud']==450)
			{
				$horas=$datos_guardar['horas_jornada'];
			}
	
			//NUMERO DE HORAS SI ES KEYOTROS(COGEMOS LA POSICION DEL ARRAY SEGUN EL BUCLE)
			if($datos_guardar['k_proyecto_solicitud']==468)
			{
				$horas=$array_horas[$i];
			}
	
	
			$dia_partido=explode("-", $array_dias[$i]);
			$dia=$dia_partido[0];
			$mes=$dia_partido[1];
			$year=$dia_partido[2];
	
	
			$year_vac;
			//$year_vac=($datos_guardar['diasPendientesDebidos']>0)?$datos_guardar['year_solicitud']-1:$datos_guardar['year_solicitud'];
	
			if($datos_guardar['diasPendientesDebidos']>0)
			{
				$year_vac=$datos_guardar['year_solicitud']-1;
			}
			else if($datos_guardar['diasPendientes']>0)
			{
				$year_vac=$datos_guardar['year_solicitud'];
			}
			else
			{
				$year_vac=$datos_guardar['year_solicitud']+1;
			}
	
	
			if($datos_guardar['k_proyecto_solicitud']==468)
			{
				$year_vac=null;
			}
	
			//INSERT
			$data = array(
					'k_permisos_solic'      =>   $datos_guardar['k_permisos_solic'],
					'horas_solic'         	=>   $horas,
					'dia_solic' 			=>   $dia,
					'mes_solic'      		=>   $mes,
					'año_solic' 	 		=>   $year,
					'año_vac'   			=>   $year_vac,
			);
	
			$resultado=$this->db->insert('t_permisos_solicitados_det',$data);
	
			if($datos_guardar['diasPendientesDebidos']>0)
			{
				$datos_guardar['diasPendientesDebidos']--;
			}
			else if($datos_guardar['diasPendientes']>0)
			{
				$datos_guardar['diasPendientes']--;
			}
			else
			{
				$datos_guardar['diasPendientesFuturo']--;
			}
	
			//$datos_guardar['diasPendientesDebidos']--;
	
	
			//FIN INSERT
	
	
		}
	
	
		$this->db->trans_complete();
		$this->db->close();
	
		return $datos_guardar['k_permisos_solic'];
	
	
	}
	
	
	

	public function grabar_solicitud_editado($datos_guardar)//VERSION DESARROLLO
	{
		$fechaActual = date('Y-m-d');
	
		$this->load->database();
		$this->db->trans_start();
	
	
		$id_responsable=$this->db->query("SELECT id_consultor FROM t_consultores WHERE k_consultor={$datos_guardar['responsable_solicitud']}")->row_array();
	
	
		$fechaActual = date('Y-m-d');
	
		$data = array(
				'k_consultor_solic'     =>   $datos_guardar['k_consultor_solic'],
				'f_solicitud' 	 		=>   $fechaActual,
				'desc_observaciones'   	=>   $datos_guardar['observaciones'],
		);
			
		$array = array('k_permisos_solic' => $datos_guardar['k_permisos_solic'], 'i_autorizado_n1' => 0, 'i_autorizado_n2' => 0);
	
		$this->db->where($array);
	
		// Produces: WHERE name = 'Joe' AND title = 'boss' AND status = 'active'
	
		//$this->db->where('k_permisos_solic', $datos_guardar['k_permisos_solic']);
		$this->db->update('t_permisos_solicitados', $data);
	
	
		//BORRO TOODOS LOS DATOS Y LOS INSERTO DE NUEVO
	
		$this->db->delete('t_permisos_solicitados_det', array('k_permisos_solic' => $datos_guardar['k_permisos_solic']));
	
		// Produces:
		// DELETE FROM t_permisos_solic_det
		// WHERE t_permisos_solic = $datos_guardar['k_permisos_solic']
	
	
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
	
			$year_vac;
			//$year_vac=($datos_guardar['diasPendientesDebidos']>0)?$datos_guardar['year_solicitud']-1:$datos_guardar['year_solicitud'];
	
			//SEGUN LE QUEDEN DIAS PENDIENTES EN UN AÃ‘O O EN OTRO LE PONEMOS ESE VALOR EN AÃ‘O_VAC DE LA BBD
			if($datos_guardar['diasPendientesDebidos']>0)
			{
				$year_vac=$datos_guardar['year_solicitud']-1;
			}
			else if($datos_guardar['diasPendientes']>0)
			{
				$year_vac=$datos_guardar['year_solicitud'];
			}
			else
			{
				$year_vac=$datos_guardar['year_solicitud']+1;
			}
	
	
			//SI ES KEYOTROS LO PONEMOS NULL PORQUE NO CUENTA COMO DIA
			if($datos_guardar['k_proyecto_solicitud']==468)
			{
				$year_vac=null;
			}
	
			//INSERT
	
			$data = array(
					'k_permisos_solic'      =>   $datos_guardar['k_permisos_solic'],
					'horas_solic'         	=>   $horas,
					'dia_solic' 			=>   $dia,
					'mes_solic'      		=>   $mes,
					'año_solic' 	 		=>   $year,
					'año_vac'   			=>   $year_vac,
			);
	
			//RESTAMOS DIAS AL CONTADOR DE DIAS PENDIENTES SEGUN POR EL AÃ‘O QUE VAYAMOS
			$resultado=$this->db->insert('t_permisos_solicitados_det',$data);
	
			if($datos_guardar['diasPendientesDebidos']>0)
			{
				$datos_guardar['diasPendientesDebidos']--;
			}
			else if($datos_guardar['diasPendientes']>0)
			{
				$datos_guardar['diasPendientes']--;
			}
			else
			{
				$datos_guardar['diasPendientesFuturo']--;
			}
	
			//$datos_guardar['diasPendientesDebidos']--;
	
	
			//FIN INSERT
	
	
		}
	
		$this->db->trans_complete();
		$this->db->close();
	
		return $datos_guardar['k_permisos_solic'];
	
	}
	
	
	*/
	
	//===================================FINAL VERSIONES DESARROLLO============(CON MOTIVO DE LAS "EÑES" EN POSTGRE)
	
	
	
	
	
	
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
	
	//CARGA LOS DIAS DE LA TABLA CALENDARIO PARA LA TABLA INFERIOR (FECHA ACTUAL NO ES LA ACTUAL REAL, DEPENDE DE LA QUE LE PASEMOS QUE DE MOMENTO ES OCTUBRE DEL AÃ‘O ANTERIOR)
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
	
	
	
	
	
	//cargar la informacion de la solicitud activa a nivel de tabla padre(t_permisos_solicitados)
	public function cargar_datos_solicitud_activa($k_permiso_solic)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT * FROM t_permisos_solicitados
		where k_permisos_solic ='$k_permiso_solic'";
		
		$datosSolicitud=$this->db->query($sql)->result_array();
				
		$this->db->trans_complete();
		$this->db->close();
		return $datosSolicitud;
	}
	
	
	//CARGA LAS HORAS DE QUE PUESO EL USUARIO EN ESA SOLICITUD (COGEMOS UNA LINEA PORQUE SON TODAS IGUALES EN KEYVACACIONES)
	public function cargar_horas_solicitud($k_permiso_solic)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT horas_solic FROM t_permisos_solicitados_det
		where k_permisos_solic ='$k_permiso_solic' LIMIT 1";
		
		$horasSolicitud=$this->db->query($sql)->result_array()[0]['horas_solic'];
		
		$this->db->trans_complete();
		$this->db->close();
		return $horasSolicitud;
	}
	
	//COMPROBAMOS SI HAY CALENDARIO DEL AÃ‘O SIGUIENTE PARA PINTARLO O NO
	public function comprobar_calendario_proximo_year($year)
	{
		$this->load->database();
		$this->db->trans_start();
		
		$year++;
		
		//BUSCAMOS EN EL CALENDARIO EL 1 ENERO DEL AÃ‘O QUE VIENE		
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
		
		
		$sql ="SELECT k_proyecto,id_proyecto,nom_proyecto FROM t_proyectos where k_proyecto IN(450,468)";
		
		
		
		$permisos['tipo_solicitud']=$this->db->query($sql)->result_array();
		
		
					
		$this->db->trans_complete();
		$this->db->close();
		return $permisos;
	}
	
	
	
	
	public function cargar_festivos($todos)
	{
		$this->load->database();
		$this->db->trans_start();		
		
		$ultimoDiaYear = date('Y')-1 . '-09-30';
		
				
		$sql ="SELECT f_dia_calendario FROM t_calendario WHERE sw_laborable=0 and f_dia_calendario>'$ultimoDiaYear'";	
		
		//SI ES todos COGEMOS ToDOS LOS FESTIVOS
		if($todos==1)
		{
			$sql ="SELECT f_dia_calendario FROM t_calendario WHERE sw_laborable=0";
		}
		
		$festivos=$this->db->query($sql)->result_array();		
			
		$this->db->trans_complete();
		$this->db->close();
		
		return $festivos;
	}
	
	
	
	public function eliminar_solicitud($id_eliminar)
	{				
		$this->load->database();
		$this->db->trans_start();
				
		$this->db->delete('t_permisos_solicitados_det', array('k_permisos_solic' => $id_eliminar));
		
		$this->db->delete('t_permisos_solicitados', array('k_permisos_solic' => $id_eliminar));
			
		// Produces:
		// DELETE FROM t_lineas_imc
		// WHERE k_lineas_imc = $fila['k_linea_imc_borrar']
				
		$this->db->trans_complete();
		$this->db->close();
	}
	
	public function enviar_solicitud($k_permisos_solic,$datos_guardar)
	{
		//SOLO PONEMOS EL sw_envio_solicitud a -1
		
		$this->load->database();
		$this->db->trans_start();
		
		
		$sql ="SELECT * from t_permisos_solicitados where k_permisos_solic=$k_permisos_solic";
		
		$es_key_otros=$this->db->query($sql)->row_array();
		
		$fechaActual = date('Y-m-d');
		
		//ESTE VALOR SERA 0 SI ES VACACIONES Y 1 SI ES KEYOTROS PORQUE SOLO AUTORIZA RRHH
		$aut_1=0;
		if($es_key_otros['k_proyecto']==468)
		{
			$aut_1=1;
		}
				
		$data = array(
				'k_consultor_solic'     =>   $datos_guardar['k_consultor_solic'],
				'sw_envio_solicitud'    =>   -1,
				'i_autorizado_n1'      	=>   $aut_1,
				'f_solicitud' 	 		=>   $fechaActual,
		);
		
		$this->db->where('k_permisos_solic', $k_permisos_solic);
		
		$this->db->update('t_permisos_solicitados', $data);
		// Produces:
		// UPDATE t_permisos_solicitados
		// SET sw_envio_solicitud = '{-1}'
		// WHERE k_permisos_solic = $k_permisos_solic
		
		
		
		$this->db->trans_complete();
		$this->db->close();
	} 
	
	
	public function get_resp_aut_rrhh($k_proyecto)
	{
			
		$this->load->database();
		$this->db->trans_start();
		
		$sql ="SELECT k_jefe_proyecto from t_proyectos where k_proyecto=$k_proyecto";
	
		$jefe_proyecto=$this->db->query($sql)->row_array();		
	
		$this->db->trans_complete();
		$this->db->close();
		
		return $jefe_proyecto['k_jefe_proyecto'];
	}
}