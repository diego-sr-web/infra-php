CREATE TABLE `pop_ProjetoTipo`
(
    `projetoTipo` int(11)                         NOT NULL,
    `nome`        varchar(256) CHARACTER SET utf8 NOT NULL,
    `descricao`   text CHARACTER SET utf8,
    `diasPrazo`   int(11)                                  DEFAULT NULL,
    `cor`         varchar(7) CHARACTER SET utf8            DEFAULT NULL,
    `icone`       varchar(50) CHARACTER SET utf8           DEFAULT NULL,
    `identifier`  varchar(50) CHARACTER SET utf8  NOT NULL COMMENT 'string que identifica o tipo de projeto\nex:\nPROJETO_FACEBOOK\nPROJETO_WEBSITE',
    `escondido`   tinyint(4)                      NOT NULL DEFAULT '0',
    `isUsed`      tinyint(1)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
