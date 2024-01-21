DROP TABLE IF EXISTS `ViewProjetoPrimeiros`;

CREATE ALGORITHM = UNDEFINED DEFINER =`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewProjetoPrimeiros` AS
select `p`.`projetoTipo`   AS `idTipoProjeto`,
       `p`.`nome`          AS `projetoTipoNome`,
       `et`.`elementoTipo` AS `idTipoElemento`,
       `et`.`nome`         AS `nomeTipoElemento`
from ((`pop_ProjetoTipo` `p` join `pop_ElementoPrimeiro` `e`)
         join `pop_ElementoTipo` `et`)
where ((`p`.`projetoTipo` = `e`.`projetoTipo`) and (`e`.`elementoTipo` = `et`.`elementoTipo`));
