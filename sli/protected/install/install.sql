--
-- Описание для таблицы sli_original
--
CREATE TABLE IF NOT EXISTS sli_original (
  id INT(11) NOT NULL AUTO_INCREMENT,
  a VARCHAR(64) BINARY CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  search TEXT NOT NULL,
  content TEXT NOT NULL,
  PRIMARY KEY (id),
  INDEX indexA (a)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы sli_translate
--
CREATE TABLE IF NOT EXISTS sli_translate (
  original_id INT(11) NOT NULL,
  language_id INT(11) NOT NULL,
  content TEXT NOT NULL,
  PRIMARY KEY (original_id, language_id),
  INDEX IDX_sli_translate_original_id (original_id)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;