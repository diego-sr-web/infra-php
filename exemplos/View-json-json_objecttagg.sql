SELECT
       `p`.`elemento`        AS `elemento`,
       `p`.`dataCriacao`     AS `dataCriacao`,
       `p`.`dataAtualizacao` AS `dataAtualizacao`,
       `p`.`elementoTipo`    AS `elementoTipo`,
       `p`.`elementoStatus`  AS `elementoStatus`,
       `p`.`projeto`         AS `projeto`,
       JSON_OBJECTAGG(`c`.`chave`, `c`.`valor`) as campos,
       `p`.`isUsed`          AS `isUsed`
FROM (
      (
          `pop_admin`.`pop_Elemento` `p`
              JOIN `pop_admin`.`pop_ElementoConteudo` `c`
              ON
                  (
                      `p`.`elemento` = `c`.`elemento` AND `c`.`isUsed` = 1
                      )
          )
    )
WHERE `p`.`isUsed` = 1
GROUP BY `elemento`
ORDER BY `p`.`elemento`
