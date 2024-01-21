CREATE TABLE `pop_ElementoTipoSubEtapa`
(
    `etapa`        int(11)                         NOT NULL,
    `elementoTipo` int(11)                         NOT NULL,
    `nome`         varchar(256) CHARACTER SET utf8 NOT NULL,
    `area`         int(11)                                  DEFAULT NULL,
    `proximo`      int(11)                                  DEFAULT NULL,
    `anterior`     int(11)                                  DEFAULT NULL,
    `responsavel`  int(11)                                  DEFAULT NULL,
    `prazo`        int(11)                                  DEFAULT NULL,
    `isUsed`       tinyint(1)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
