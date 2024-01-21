CREATE TABLE `pop_ElementoConteudo`
(
    `elemento` int(11)                         NOT NULL,
    `chave`    varchar(128) CHARACTER SET utf8 NOT NULL,
    `valor`    text,
    `isUsed`   tinyint(1)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
