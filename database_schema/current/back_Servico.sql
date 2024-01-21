CREATE TABLE `back_Servico`
(
    `servico`     int(11)    NOT NULL,
    `nome`        varchar(100) CHARACTER SET utf8 DEFAULT NULL,
    `descricao`   text CHARACTER SET utf8,
    `whServiceId` int(11)                         DEFAULT NULL,
    `isUsed`      tinyint(1) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
