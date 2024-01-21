CREATE TABLE `pop_ProjetoConteudo`
(
    `projeto` int(11)      NOT NULL,
    `chave`   varchar(128) NOT NULL,
    `valor`   blob,
    `isUsed`  tinyint(1)   NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
