DELIMITER $$

USE `zz_new_system`$$

DROP PROCEDURE IF EXISTS `sp_grafica_averias_x_jefatura_troba`$$

CREATE DEFINER=`rfalla`@`%` PROCEDURE `sp_grafica_averias_x_jefatura_troba`()
BEGIN
	#############################################################
	TRUNCATE TABLE zz_new_system.graf_promedio;
	INSERT INTO zz_new_system.graf_promedio
	SELECT DISTINCT
	CASE 
	  WHEN SUBSTR(a.nroplano,1,1) = 'G' THEN 'GPON'
	  WHEN jj.jefatura IS NOT NULL THEN jj.jefatura
	  ELSE 'Otros'
	END AS jefatura, 
	CONCAT(a.codnod,a.nroplano) AS troba, a.dia_mov,b.desdia,LPAD(TRIM(SUBSTR(a.fec_mov,12,2)),2,'0') AS hora,COUNT(*) AS aver,
	cont_reqs.nro_reqs
	FROM ccm1.v_averias_x_nodo_troba_by_codreq a 
	INNER JOIN catalogos.dias b ON DAYOFWEEK(a.dia_mov)=b.dia 
	INNER JOIN (
		SELECT reqs.desdia, reqs.hora, COUNT(*) AS nro_reqs
		FROM (
		SELECT a.dia_mov,b.desdia,SUBSTR(fec_mov,12,2) AS hora,COUNT(*) AS aver FROM ccm1.v_averias_x_nodo_troba_by_codreq a 
		INNER JOIN catalogos.dias b ON DAYOFWEEK(a.dia_mov)=b.dia 
		WHERE tipreqini IN ('R7','RA','RP') AND DATEDIFF(NOW(),dia_mov)<=30 AND DATEDIFF(NOW(),dia_mov)>=0 AND DAYOFWEEK(a.dia_mov)=DAYOFWEEK(NOW())
		GROUP BY 1,2,3) reqs
		GROUP BY reqs.desdia, reqs.hora
	) cont_reqs ON cont_reqs.desdia = b.desdia AND cont_reqs.hora = LPAD(TRIM(SUBSTR(a.fec_mov,12,2)),2,'0')
	LEFT JOIN catalogos.jefaturas jj ON a.codnod=jj.nodo	
	WHERE tipreqini IN ('R7','RA','RP') 
	  AND DATEDIFF(NOW(),dia_mov)<=30 AND DATEDIFF(NOW(),dia_mov)>=0 AND DAYOFWEEK(a.dia_mov)=DAYOFWEEK(NOW())
	GROUP BY jefatura, troba, dia_mov, desdia, hora;
	#############################################################
	TRUNCATE TABLE zz_new_system.graf_ayer;
	INSERT INTO zz_new_system.graf_ayer
	SELECT DISTINCT
	CASE 
	  WHEN SUBSTR(a.nroplano,1,1) = 'G' THEN 'GPON'
	  WHEN jj.jefatura IS NOT NULL THEN jj.jefatura
	  ELSE 'Otros'
	END AS jefatura, 
	CONCAT(a.codnod,a.nroplano) AS troba, b.desdia,LPAD(TRIM(SUBSTR(a.fec_mov,12,2)),2,'0') AS hora,COUNT(*) AS aver 
	FROM ccm1.v_averias_x_nodo_troba_by_codreq a 
	INNER JOIN catalogos.dias b ON DAYOFWEEK(a.dia_mov)=b.dia
	LEFT JOIN catalogos.jefaturas jj ON a.codnod=jj.nodo	
	WHERE tipreqini IN ('R7','RA','RP') 
	  AND DATEDIFF(NOW(),a.dia_mov)=1
	GROUP BY jefatura, troba, desdia, hora;
	#############################################################
	TRUNCATE TABLE zz_new_system.graf_hoy;
	INSERT INTO zz_new_system.graf_hoy
	SELECT DISTINCT
	CASE 
	  WHEN SUBSTR(a.nroplano,1,1) = 'G' THEN 'GPON'
	  WHEN jj.jefatura IS NOT NULL THEN jj.jefatura
	  ELSE 'Otros'
	END AS jefatura, 
	CONCAT(a.codnod,a.nroplano) AS troba, b.desdia,LPAD(TRIM(SUBSTR(a.fec_mov,12,2)),2,'0') AS hora,COUNT(*) AS aver 
	FROM ccm1.v_averias_x_nodo_troba_by_codreq a 
	INNER JOIN catalogos.dias b ON DAYOFWEEK(a.dia_mov)=b.dia 
	LEFT JOIN catalogos.jefaturas jj ON a.codnod=jj.nodo	
	WHERE tipreqini IN ('R7','RA','RP') 
	  AND DATEDIFF(NOW(),a.dia_mov)=0
	GROUP BY jefatura, troba, desdia, hora;
	#############################################################
	TRUNCATE TABLE zz_new_system.graf_arbol_tot;
	INSERT INTO zz_new_system.graf_arbol_tot
	SELECT DISTINCT 
	CASE 
	  WHEN SUBSTR(c.troba_real,1,1) = 'G' THEN 'GPON'
	  WHEN jj.jefatura IS NOT NULL THEN jj.jefatura
	  ELSE 'Otros'
	END AS jefatura, 
	CASE
	  WHEN c.nodo_real IS NULL OR c.troba_real IS NULL THEN 'Otros'
	  ELSE CONCAT(c.nodo_real,c.troba_real) 
	END AS troba, 
	LPAD(TRIM(SUBSTR(dh.fechahora,12,2)),2,'0') AS hora ,COUNT(*) AS arboltot 
	FROM arboldecisiones.`decisiones_hoy` dh
	LEFT JOIN arboldecisiones.v_clientes_arboldec_con_nodo_troba c ON dh.idclientecrm = c.idclientecrm
	  -- clientes que no estan ni en nclientes ni en planta clarita!! --- Se agruparan como "Otros"
	LEFT JOIN catalogos.jefaturas jj ON c.nodo_real=jj.nodo
	WHERE DATEDIFF(NOW(),dh.fechahora)=0
	GROUP BY jefatura, troba, hora;
	#############################################################
	TRUNCATE TABLE zz_new_system.graf_arbol_hoy;
	INSERT INTO zz_new_system.graf_arbol_hoy
	SELECT DISTINCT 
	CASE 
	  WHEN SUBSTR(resumen.troba_real,1,1) = 'G' THEN 'GPON'
	  WHEN jj.jefatura IS NOT NULL THEN jj.jefatura
	  ELSE 'Otros'
	END AS jefatura, 
	CASE
	  WHEN resumen.nodo_real IS NULL OR resumen.troba_real IS NULL THEN 'Otros'
	  ELSE CONCAT(resumen.nodo_real,resumen.troba_real) 
	END AS troba, 
	-- resumen.troba_real, resumen.nodo_real,
	resumen.hora, SUM(resumen.arbol) AS arbol 
	FROM (	
	  SELECT DISTINCT 'req_pend_macro_final' AS tipo, a1.fec_registro AS fecharegistro, c1.troba_real, c1.nodo_real, LPAD(TRIM(SUBSTR(a1.fec_registro,12,2)),2,'0') AS hora, COUNT(DISTINCT b1.`fechahora`) AS arbol 
	  FROM cms.`req_pend_macro_final` a1 
	  LEFT JOIN arboldecisiones.`decisiones_hoy` b1 ON a1.`codcli`=b1.`idclientecrm` 
  	    AND DATEDIFF(NOW(),a1.`fec_registro`)=0 
	    AND DATEDIFF(NOW(),b1.`fechahora`)=0 
	  LEFT JOIN arboldecisiones.v_clientes_arboldec_con_nodo_troba_fecha c1 ON a1.`codcli` = c1.idclientecrm
  	    AND DATEDIFF(NOW(),a1.`fec_registro`)=0 
	    AND DATEDIFF(NOW(),c1.`fechahora`)=0 
	  WHERE DATEDIFF(NOW(),a1.`fec_registro`)=0 
	  GROUP BY tipo, fecharegistro, c1.troba_real, c1.nodo_real, hora

	  UNION

	  SELECT DISTINCT 'aver_liq_catv_pais' AS tipo, a2.fecharegistro AS fecharegistro, c2.troba_real, c2.nodo_real, LPAD(TRIM(SUBSTR(a2.fecharegistro,12,2)),2,'0') AS hora, COUNT(DISTINCT b2.`fechahora`) AS arbol 
	  FROM cms.`aver_liq_catv_pais` a2
	  LEFT JOIN arboldecisiones.`decisiones_hoy` b2 ON a2.`codigodelcliente`=b2.`idclientecrm` 
	    AND DATEDIFF(NOW(),a2.`fecharegistro`)=0 
	    AND DATEDIFF(NOW(),b2.`fechahora`)=0 
	  LEFT JOIN arboldecisiones.v_clientes_arboldec_con_nodo_troba_fecha c2 ON a2.`codigodelcliente` = c2.idclientecrm
  	    AND DATEDIFF(NOW(),a2.`fecharegistro`)=0 
	    AND DATEDIFF(NOW(),c2.`fechahora`)=0 
	  WHERE DATEDIFF(NOW(),a2.`fecharegistro`)=0 
	  GROUP BY tipo, fecharegistro, c2.troba_real, c2.nodo_real, hora
	  
	) resumen
	LEFT JOIN catalogos.jefaturas jj ON resumen.nodo_real=jj.nodo
	WHERE DATEDIFF(NOW(),resumen.fecharegistro)=0 
        GROUP BY jefatura, troba, resumen.hora;
	#############################################################
	TRUNCATE TABLE zz_new_system.graf_llamadas;
	INSERT INTO  zz_new_system.graf_llamadas
	SELECT DISTINCT
	CASE 
	  WHEN SUBSTR(a.troba,1,1) = 'G' THEN 'GPON'
	  WHEN jj.jefatura IS NOT NULL THEN jj.jefatura
	  ELSE 'Otros'
	END AS jefatura, 
	CASE
	  WHEN a.nodo IS NULL OR a.troba IS NULL THEN 'Otros'
	  ELSE CONCAT(a.nodo,a.troba) 
	END AS troba, 
        LPAD(TRIM(SUBSTR(a.fechahora,12,2)),2,'0') AS horallam, COUNT(*) AS llamadas 
        FROM alertasx.`alertas_dmpe` a 
        LEFT JOIN catalogos.jefaturas jj ON a.nodo=jj.nodo
        GROUP BY jefatura, troba, horallam;
	#############################################################
	TRUNCATE TABLE zz_new_system.graf_liquidaciones;
	INSERT INTO zz_new_system.graf_liquidaciones
	SELECT DISTINCT
	CASE 
	  WHEN SUBSTR(a.plano,1,1) = 'G' THEN 'GPON'
	  WHEN jj.jefatura IS NOT NULL THEN jj.jefatura
	  ELSE 'Otros'
	END AS jefatura, 
	CASE
	  WHEN a.codnod IS NULL OR a.plano IS NULL THEN 'Otros'
	  ELSE CONCAT(a.codnod,a.plano) 
	END AS troba, 
	LPAD(TRIM(SUBSTR(a.fecha_liquidacion,12,2)),2,'0') Hora, COUNT(*) AS liq 
	FROM cms.aver_liq_catv_pais a 
	LEFT JOIN catalogos.jefaturas jj ON a.codnod=jj.nodo -- and DATEDIFF(NOW(),a.fecha_liquidacion)=0 
        WHERE DATEDIFF(NOW(),a.fecha_liquidacion)=0 
	GROUP BY jefatura, troba, Hora ;
    END$$

DELIMITER ;