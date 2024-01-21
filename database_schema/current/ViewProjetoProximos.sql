DROP TABLE IF EXISTS `ViewProjetoProximos`;

CREATE ALGORITHM = UNDEFINED DEFINER =`root`@`localhost` SQL SECURITY DEFINER VIEW `ViewProjetoProximos` AS
select `p`.`projetoTipo`    AS `idProjetoTipo`,
       `p`.`nome`           AS `projetoTipoNome`,
       `et`.`elementoTipo`  AS `idPai`,
       `et`.`nome`          AS `nomePai`,
       `et2`.`elementoTipo` AS `idFilho`,
       `et2`.`nome`         AS `nomeFilho`
from (((`pop_ProjetoTipo` `p` join `pop_ElementoProximo` `e`) join `pop_ElementoTipo` `et`)
         join `pop_ElementoTipo` `et2`)
where ((`p`.`projetoTipo` = `e`.`projetoTipo`) and (`e`.`elementoTipo` = `et`.`elementoTipo`) and
       (`e`.`proximo` = `et2`.`elementoTipo`));
