CREATE TABLE `pop_ProjetoDadosTemporarios`
(
    `projeto`  int(11)    NOT NULL,
    `user`     int(11)    NOT NULL,
    `conteudo` text CHARACTER SET utf8,
    `isUsed`   tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
