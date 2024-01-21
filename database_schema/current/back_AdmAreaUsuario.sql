CREATE TABLE `back_AdmAreaUsuario`
(
    `id`             int(11)    NOT NULL,
    `usuarioNerdweb` int(11)    NOT NULL,
    `area`           int(11)    NOT NULL,
    `nivel`          int(11)             DEFAULT '1',
    `isUsed`         tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
