CREATE TABLE `pop_ElementoProximo`
(
    `projetoTipo`  int(11) NOT NULL,
    `elementoTipo` int(11) NOT NULL,
    `proximo`      int(11) NOT NULL,
    `isUsed`       int(11) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
