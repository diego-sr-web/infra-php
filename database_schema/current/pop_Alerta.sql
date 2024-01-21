CREATE TABLE `pop_Alerta`
(
    `alerta`        int(11)    NOT NULL,
    `titulo`        varchar(255)        DEFAULT NULL,
    `texto`         text,
    `tipo`          varchar(255)        DEFAULT NULL,
    `dataCriacao`   datetime            DEFAULT NULL,
    `dataExpiracao` datetime            DEFAULT NULL,
    `projeto`       int(11)             DEFAULT NULL,
    `elemento`      int(11)             DEFAULT NULL,
    `isUsed`        tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
