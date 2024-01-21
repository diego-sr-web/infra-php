CREATE TABLE `back_AdmGrupo`
(
    `grupo`     int(11)    NOT NULL,
    `nome`      varchar(50) CHARACTER SET utf8 DEFAULT NULL,
    `descricao` tinytext CHARACTER SET utf8,
    `isUsed`    tinyint(1) NOT NULL            DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
