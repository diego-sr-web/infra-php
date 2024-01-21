CREATE TABLE `pop_ElementoHistorico`
(
    `usuario`  int(11)    NOT NULL,
    `elemento` int(11)    NOT NULL,
    `acao`     tinyint(4) NOT NULL,
    `data`     timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `isUsed`   tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
