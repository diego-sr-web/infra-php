DROP TABLE IF EXISTS `ViewUsuarioArea`;

CREATE ALGORITHM = UNDEFINED DEFINER =`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewUsuarioArea` AS
select `admArea`.`area`              AS `idArea`,
       `admAreaUsuario`.`id`         AS `ligacao`,
       `admArea`.`nome`              AS `nomeArea`,
       `admUsuario`.`usuarioNerdweb` AS `idUsuario`,
       `admUsuario`.`nome`           AS `nomeUsuario`
from ((`back_AdmArea` `admArea` join `back_AdmAreaUsuario` `admAreaUsuario`)
         join `back_AdmUsuario` `admUsuario`)
where ((`admArea`.`area` = `admAreaUsuario`.`area`) and
       (`admAreaUsuario`.`usuarioNerdweb` = `admUsuario`.`usuarioNerdweb`) and (`admArea`.`isUsed` = 1) and
       (`admAreaUsuario`.`isUsed` = 1) and (`admUsuario`.`isUsed` = 1));
