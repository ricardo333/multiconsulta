DELIMITER $$

USE `ccm1`$$

DROP VIEW IF EXISTS `v_averias_x_nodo_troba_by_codreq`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`rfalla`@`%` SQL SECURITY DEFINER VIEW `v_averias_x_nodo_troba_by_codreq` AS (
SELECT
codnod, nroplano, dia_mov, fec_mov, tipreqini 
FROM ccm1.averias_m1_new 
GROUP BY `averias_m1_new`.`codreq`)$$

DELIMITER ;