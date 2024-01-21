select `p`.`elemento`        AS `elemento`,
       `p`.`dataCriacao`     AS `dataCriacao`,
       `p`.`dataAtualizacao` AS `dataAtualizacao`,
       `p`.`elementoTipo`    AS `elementoTipo`,
       `p`.`elementoStatus`  AS `elementoStatus`,
       `p`.`projeto`         AS `projeto`,
       `pr`.`projetoTipo`    AS `projetoTipo`,
       `c`.`chave`           AS `chave`,
       `c`.`valor`           AS `valor`,
       `p`.`isUsed`          AS `isUsed`
from ((`pop_Elemento` `p` join `pop_ElementoConteudo` `c` on (((`p`.`elemento` = `c`.`elemento`) and (`c`.`isUsed` = 1))))
         join `pop_Projeto` `pr` on (((`p`.`projeto` = `pr`.`projeto`) and (`pr`.`isUsed` = 1))))
where ((`p`.`isUsed` = 1) and ((`c`.`chave` = 'area') or (`c`.`chave` = 'Etapa') or (`c`.`chave` = 'Responsavel')))
order by `p`.`elemento`
