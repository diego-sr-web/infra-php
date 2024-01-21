CREATE TABLE `pop_ElementoStatus`
(
    `elementoStatus` int(11)                        NOT NULL,
    `nome`           varchar(256) CHARACTER SET utf8         DEFAULT NULL,
    `descricao`      text CHARACTER SET utf8,
    `cor`            varchar(7) CHARACTER SET utf8           DEFAULT NULL,
    `identifier`     varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'string que identifica o status\nex: \nSTATUS_AGUARDANDO_RESPONSAVEL\nSTATUS_EM_ANDAMENTO',
    `isUsed`         tinyint(1)                     NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
