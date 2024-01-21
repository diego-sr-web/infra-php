CREATE TABLE `pop_Notificacao`
(
    `notificacao`  int(11)    NOT NULL,
    `texto`        text,
    `url`          varchar(255)        DEFAULT NULL,
    `icone`        varchar(255)        DEFAULT NULL,
    `cor`          varchar(7)          DEFAULT NULL,
    `prazo`        date                DEFAULT NULL,
    `dataCriacao`  datetime            DEFAULT NULL,
    `dataLeitura`  datetime            DEFAULT NULL,
    `projeto`      int(11)             DEFAULT NULL,
    `elementoTipo` int(11)             DEFAULT NULL,
    `subetapa`     int(11)             DEFAULT NULL,
    `cliente`      int(11)             DEFAULT NULL,
    `area`         int(11)    NOT NULL,
    `usuario`      int(11)    NOT NULL,
    `isUsed`       tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
