CREATE TABLE `pop_ElementoTipoNomeTipo`
(
    `elementoTipo` int(11)                         NOT NULL,
    `nome`         varchar(128) CHARACTER SET utf8 NOT NULL,
    `nomeExibicao` varchar(100) CHARACTER SET utf8          DEFAULT NULL,
    `tipo`         varchar(128) CHARACTER SET utf8 NOT NULL DEFAULT 'text',
    `isUsed`       tinyint(4)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
