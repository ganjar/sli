CREATE TABLE sli_block_original (
  id      VARCHAR(64) NOT NULL
  COMMENT 'ID|text',
  content TEXT        NOT NULL
  COMMENT 'Оригинал|textarea',
  PRIMARY KEY (id)
)
  ENGINE = INNODB
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT = 'Блоки - оригиналы';


CREATE TABLE sli_block_translate (
  id          INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  original_id VARCHAR(64)         NOT NULL
  COMMENT 'Оригинал|text',
  language_id TINYINT(3) UNSIGNED NOT NULL
  COMMENT 'Язык|select',
  content     TEXT                NOT NULL
  COMMENT 'Перевод|textarea',
  PRIMARY KEY (id),
  UNIQUE INDEX UK_sli_block_translate (original_id, language_id),
  CONSTRAINT FK_sli_block_translate_sli_block_original_id FOREIGN KEY (original_id)
  REFERENCES sli_block_original (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT FK_sli_block_translate_sli_language_id FOREIGN KEY (language_id)
  REFERENCES sli_language (id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
)
  ENGINE = INNODB
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT = 'Блоки - перевод';

CREATE TABLE sli_language (
  id                   TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  is_active            TINYINT(1)          NOT NULL DEFAULT 0 COMMENT 'Активность|checkbox',
  alias                VARCHAR(8)          NOT NULL COMMENT 'Алиас|text',
  title                VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'Название|text',
  auto_translate_alias VARCHAR(8)                   DEFAULT NULL
  COMMENT 'Алиас для автопереводчика|text',
  PRIMARY KEY (id),
  UNIQUE INDEX UK_sli_lang_alias (alias)
)
  ENGINE = INNODB
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT = 'Языки';

CREATE TABLE sli_original (
  id      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  a       VARCHAR(64) BINARY CHARACTER SET utf8
          COLLATE utf8_bin NOT NULL
  COMMENT 'Индекс (системный)|hidden',
  content TEXT             NOT NULL
  COMMENT 'Оригинал|textarea',
  PRIMARY KEY (id),
  INDEX indexA (a)
)
  ENGINE = INNODB
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT = 'Оригинал';

CREATE TABLE sli_setting (
  id    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(32)      NOT NULL
  COMMENT 'Название блока настроек|text',
  data  MEDIUMBLOB                DEFAULT NULL
  COMMENT 'Данные|json',
  PRIMARY KEY (id),
  UNIQUE INDEX UK_sli_setting_alias (alias)
)
  ENGINE = INNODB
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT = 'Настройки';

CREATE TABLE sli_translate (
  original_id INT(11) UNSIGNED    NOT NULL
  COMMENT 'Оргиниал|select',
  language_id TINYINT(3) UNSIGNED NOT NULL
  COMMENT 'Язык|select',
  content     TEXT                NOT NULL
  COMMENT 'Перевод|textarea',
  PRIMARY KEY (original_id, language_id),
  INDEX IDX_sli_translate_original_id (original_id),
  CONSTRAINT FK_sli_translate_sli_language_id FOREIGN KEY (language_id)
  REFERENCES sli_language (id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT FK_sli_translate_sli_original_id FOREIGN KEY (original_id)
  REFERENCES sli_original (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = INNODB
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT = 'Перевод';

CREATE TABLE sli_translate_status (
  id      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  url     VARCHAR(255)     NOT NULL
  COMMENT 'Адрес страницы|text',
  percent TINYINT(4)                DEFAULT NULL
  COMMENT 'Статус перевода в %|int',
  PRIMARY KEY (id)
)
  ENGINE = INNODB
  CHARACTER SET utf8
  COLLATE utf8_general_ci
  COMMENT = 'Статус перевода страниц';