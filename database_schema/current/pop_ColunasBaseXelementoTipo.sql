CREATE TABLE `pop_ColunasBaseXelementoTipo`
(
    `elementoTipo` int(11)    NOT NULL,
    `colunasBase`  int(11)    NOT NULL,
    `isUsed`       tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
