CREATE TABLE `pop_Elemento`
(
    `elemento`        int(11)    NOT NULL,
    `dataCriacao`     datetime            DEFAULT NULL,
    `dataAtualizacao` timestamp  NULL     DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `elementoTipo`    int(11)    NOT NULL,
    `elementoStatus`  int(11)    NOT NULL,
    `projeto`         int(11)    NOT NULL,
    `isUsed`          tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
