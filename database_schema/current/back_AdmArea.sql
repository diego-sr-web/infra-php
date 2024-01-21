CREATE TABLE `back_AdmArea`
(
    `area`   int(11)    NOT NULL,
    `nome`   varchar(50) CHARACTER SET utf8 DEFAULT NULL,
    `cor`    varchar(7) CHARACTER SET utf8  DEFAULT NULL,
    `hidden` tinyint(4)                     DEFAULT '0',
    `isUsed` tinyint(1) NOT NULL            DEFAULT '1'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
