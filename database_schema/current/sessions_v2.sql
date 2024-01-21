CREATE TABLE `sessions_v2`
(
    `id`     varchar(32) NOT NULL,
    `access` int(10) UNSIGNED DEFAULT NULL,
    `data`   text
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
