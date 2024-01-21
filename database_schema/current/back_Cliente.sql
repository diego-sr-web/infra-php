CREATE TABLE `back_Cliente`
(
    `cliente`      int(11)    NOT NULL,
    `nomeFantasia` varchar(50) CHARACTER SET utf8  DEFAULT NULL,
    `responsavel`  varchar(100) CHARACTER SET utf8 DEFAULT NULL,
    `email`        varchar(50) CHARACTER SET utf8  DEFAULT NULL,
    `contato`      text CHARACTER SET utf8,
    `logo`         varchar(255)                    DEFAULT 'default.png',
    `logo_full`    varchar(255)                    DEFAULT 'default.png',
    `dataEntrada`  date                            DEFAULT NULL,
    `dataCriacao`  datetime                        DEFAULT NULL,
    `observacao`   text CHARACTER SET utf8,
    `ativo`        tinyint(1)                      DEFAULT '1',
    `whmcsId`      int(11)                         DEFAULT NULL,
    `isUsed`       tinyint(1) NOT NULL             DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
