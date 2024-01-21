CREATE TABLE `pop_Projeto`
(
    `projeto`       int(11)                         NOT NULL,
    `nome`          varchar(512) CHARACTER SET utf8 NOT NULL,
    `dataEntrada`   date                                     DEFAULT NULL,
    `prazo`         date                                     DEFAULT NULL,
    `prazoEstimado` date                                     DEFAULT NULL,
    `finalizado`    tinyint(1)                      NOT NULL DEFAULT '0',
    `projetoTipo`   int(11)                         NOT NULL,
    `cliente`       int(11)                                  DEFAULT NULL,
    `isUsed`        tinyint(1)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
