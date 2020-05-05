DELIMITER $$

USE `arboldecisiones`$$

DROP VIEW IF EXISTS `v_clientes_arboldec_con_nodo_troba_fecha`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`rfalla`@`%` SQL SECURITY DEFINER VIEW `arboldecisiones`.`v_clientes_arboldec_con_nodo_troba_fecha` AS 
-- Obteniendo Nodo y Troba para clientes HFC, obtiendo datos de planta clarita si es necesario
SELECT DISTINCT dh1.idclientecrm, IF(nc1.NODO IS NULL OR nc1.NODO='', pc1.nodo, nc1.NODO) AS nodo_real , IF(nc1.TROBA IS NULL OR nc1.TROBA='', pc1.plano, nc1.TROBA) AS troba_real, dh1.fechahora
FROM arboldecisiones.`decisiones_hoy` dh1
INNER JOIN multiconsulta.nclientes nc1 ON nc1.IDCLIENTECRM = dh1.idclientecrm
INNER JOIN cms.planta_clarita pc1 ON pc1.cliente = nc1.IDCLIENTECRM 
UNION
-- Obteniendo Nodo y Troba para clientes NO HFC
SELECT DISTINCT dh2.idclientecrm, pc2.nodo AS nodo_real , pc2.plano AS troba_real, dh2.fechahora
FROM arboldecisiones.`decisiones_hoy` dh2
INNER JOIN cms.planta_clarita pc2 ON dh2.idclientecrm = pc2.cliente
WHERE NOT EXISTS (SELECT * FROM multiconsulta.nclientes nc2 WHERE nc2.IDCLIENTECRM = dh2.idclientecrm)$$

DELIMITER ;