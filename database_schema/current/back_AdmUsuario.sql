CREATE TABLE `back_AdmUsuario`
(
    `usuarioNerdweb` int(11)    NOT NULL,
    `nome`           varchar(80) CHARACTER SET utf8  DEFAULT NULL,
    `email`          varchar(256) CHARACTER SET utf8 DEFAULT NULL,
    `senha`          varchar(65) CHARACTER SET utf8  DEFAULT NULL,
    `imagem`         text CHARACTER SET utf8,
    `area`           int(11)                         DEFAULT NULL,
    `administrador`  tinyint(1)                      DEFAULT '0',
    `ativo`          tinyint(1) NOT NULL             DEFAULT '1',
    `isUsed`         tinyint(1) NOT NULL             DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
