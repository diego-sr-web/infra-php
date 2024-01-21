CREATE TABLE `pop_ProjetoChat`
(
    `projeto`     int(11)    NOT NULL,
    `elemento`    int(11)             DEFAULT NULL,
    `responsavel` int(11)    NOT NULL,
    `sistema`     tinyint(4) NOT NULL DEFAULT '0',
    `conteudo`    text       NOT NULL,
    `data`        timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `isUsed`      tinyint(1) NOT NULL DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
