CREATE TABLE `back_AdmPermissaoGrupo`
(
    `permissaoGrupo` int(11)    NOT NULL,
    `permissao`      int(11)    NOT NULL,
    `grupo`          int(11)    NOT NULL,
    `isUsed`         tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
