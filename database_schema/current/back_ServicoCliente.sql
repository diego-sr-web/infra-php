CREATE TABLE `back_ServicoCliente`
(
    `servicoCliente`  int(11)    NOT NULL,
    `cliente`         int(11)    NOT NULL,
    `servico`         int(11)    NOT NULL,
    `periodicidade`   int(11)             DEFAULT NULL,
    `dataAssinatura`  datetime            DEFAULT NULL,
    `dataAtualizacao` datetime            DEFAULT NULL,
    `observacao`      text CHARACTER SET utf8,
    `ativo`           tinyint(1)          DEFAULT '1',
    `isUsed`          tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
