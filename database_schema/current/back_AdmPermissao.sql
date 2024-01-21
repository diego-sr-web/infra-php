CREATE TABLE `back_AdmPermissao`
(
    `permissao` int(11)                        NOT NULL,
    `nome`      varchar(50) CHARACTER SET utf8          DEFAULT NULL,
    `descricao` tinytext CHARACTER SET utf8,
    `arquivo`   varchar(50) CHARACTER SET utf8 NOT NULL,
    `isUsed`    tinyint(1)                     NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
