CREATE TABLE `pop_ColunasBase`
(
    `colunaBase` int(11)                         NOT NULL,
    `nome`       varchar(256) CHARACTER SET utf8 NOT NULL,
    `isUsed`     tinyint(4)                      NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
