CREATE TABLE `back_AdmGrupoUsuario`
(
    `grupoUsuario`   int(11)    NOT NULL,
    `usuarioNerdweb` int(11)    NOT NULL,
    `grupo`          int(11)    NOT NULL,
    `isUsed`         tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
