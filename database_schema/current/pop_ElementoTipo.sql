CREATE TABLE `pop_ElementoTipo`
(
    `elementoTipo` int(11)    NOT NULL,
    `nome`         varchar(256) CHARACTER SET utf8 DEFAULT NULL,
    `prazo`        int(11)    NOT NULL             DEFAULT '0',
    `isUsed`       tinyint(1) NOT NULL             DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
