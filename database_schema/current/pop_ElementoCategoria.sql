CREATE TABLE `pop_ElementoCategoria`
(
    `categoria`  int(11)                         NOT NULL,
    `cliente`    int(11)                                  DEFAULT NULL,
    `nome`       varchar(256) CHARACTER SET utf8 NOT NULL,
    `icone`      varchar(15) CHARACTER SET utf8  NOT NULL DEFAULT 'fa-circle',
    `cor`        varchar(7) CHARACTER SET utf8   NOT NULL DEFAULT '#ffffff',
    `descricao`  text CHARACTER SET utf8,
    `identifier` varchar(256) CHARACTER SET utf8          DEFAULT NULL,
    `isUsed`     tinyint(1)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
