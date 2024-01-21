CREATE TABLE `pop_Prioridade`
(
    `prioridade` tinyint(4)                      NOT NULL,
    `nome`       varchar(256) CHARACTER SET utf8 NOT NULL,
    `icone`      varchar(256) CHARACTER SET utf8 NOT NULL,
    `cor`        varchar(10) CHARACTER SET utf8  NOT NULL,
    `cor_linha`  varchar(10) CHARACTER SET utf8  NOT NULL,
    `identifier` varchar(256) CHARACTER SET utf8 NOT NULL,
    `isUsed`     tinyint(1)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
