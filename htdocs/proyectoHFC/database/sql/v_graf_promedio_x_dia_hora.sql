DELIMITER $$

USE `zz_new_system`$$

DROP VIEW IF EXISTS `v_graf_promedio_x_dia_hora`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`rfalla`@`%` SQL SECURITY DEFINER VIEW `zz_new_system`.`v_graf_promedio_x_dia_hora` AS (
SELECT
  c.desdia, c.hora, ROUND(AVG(c.aver)) AS aver 
  FROM `graf_promedio` c 
  GROUP BY `c`.`desdia`,`c`.`hora`)$$

DELIMITER ;