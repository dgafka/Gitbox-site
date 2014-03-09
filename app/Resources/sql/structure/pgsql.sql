CREATE TABLE IF NOT EXISTS user_description (
  id SERIAL PRIMARY KEY NOT NULL,
  description TEXT NULL,
  registration_date DATE DEFAULT '1970-01-01' NOT NULL,
  ban_date DATE NULL,
  ip VARCHAR(15) NULL,
  hit INT DEFAULT 0 NOT NULL
);

CREATE TABLE IF NOT EXISTS user_group (
  id SERIAL PRIMARY KEY NOT NULL,
  permissions INT NOT NULL,
  description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS user_account (
  id SERIAL PRIMARY KEY NOT NULL,
  id_group INT NOT NULL,
  id_description INT NOT NULL,
  login VARCHAR(25) NOT NULL,
  password VARCHAR(32) NOT NULL,
  status VARCHAR(1) NOT NULL,
  email VARCHAR(50) NOT NULL,
  CONSTRAINT USER_FK_id_group FOREIGN KEY (id_group) REFERENCES user_group(id),
  CONSTRAINT USER_FK_id_description FOREIGN KEY (id_description) REFERENCES user_description(id) ON DELETE CASCADE,
  CONSTRAINT USER_UNIQUE_id UNIQUE (id)
);

CREATE TABLE IF NOT EXISTS module (
  id SERIAL PRIMARY KEY NOT NULL,
  name VARCHAR(50) NOT NULL,
  description TEXT NULL
);

CREATE TABLE IF NOT EXISTS user_modules (
  id_user INT NOT NULL,
  id_module INT NOT NULL,
  CONSTRAINT USER_MODULES_FK_id_user FOREIGN KEY (id_user) REFERENCES user_account(id) ON DELETE RESTRICT,
  CONSTRAINT USER_MODULES_FK_id_module FOREIGN KEY (id_module) REFERENCES module(id) ON DELETE CASCADE,
  PRIMARY KEY(id_user, id_module)
);

CREATE TABLE IF NOT EXISTS category (
  id SERIAL PRIMARY KEY NOT NULL,
  name VARCHAR(50) NOT NULL,
  description TEXT NULL
);

CREATE TABLE IF NOT EXISTS menu (
  id SERIAL PRIMARY KEY NOT NULL,
  id_category INT NULL, -- NULL -> uncategorized
  id_module INT NOT NULL,
  parent INT NULL,
  title VARCHAR(50) NOT NULL,
  status VARCHAR(1) NOT NULL,
  sort INT NULL, -- same SORT number -> getting MENU items AS [SELECT * FROM menu ORDER BY title ASC]
  expire DATE NULL,
  CONSTRAINT MENU_FK_id_category FOREIGN KEY (id_category) REFERENCES category(id) ON DELETE SET NULL,
  CONSTRAINT MENU_FK_id_module FOREIGN KEY (id_module) REFERENCES module(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS content (
  id SERIAL PRIMARY KEY NOT NULL,
  id_user INT NOT NULL,
  id_menu INT NOT NULL,
  status VARCHAR(1) NOT NULL,
  title VARCHAR(255) NOT NULL,
  header VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  create_date DATE DEFAULT '1970-01-01' NOT NULL,
  hit INT DEFAULT 0 NOT NULL,
  expire DATE NULL,
--   total_modifications INT DEFAULT 0, -- if total_modifications == 0 -> last_modification_date is CREATION DATE
  last_modification_date DATE NOT NULL, -- what with creation_date??
  rate FLOAT8 NULL, -- same as DOUBLE PRECISION
  type VARCHAR(1) NOT NULL,
  CONSTRAINT CONTENT_FK_id_user FOREIGN KEY (id_user) REFERENCES user_account(id) ON DELETE CASCADE,
  CONSTRAINT CONTENT_FK_id_menu FOREIGN KEY (id_menu) REFERENCES menu(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS comment (
  id SERIAL PRIMARY KEY NOT NULL,
  id_user INT NOT NULL,
  id_content INT NOT NULL,
  description TEXT NOT NULL,
  status VARCHAR(1) NOT NULL,
--   total_modifications INT DEFAULT 0,
  last_modification_date DATE NULL, -- what with creation_date??
  create_date DATE NOT NULL,
  CONSTRAINT COMMENT_FK_id_user FOREIGN KEY (id_user) REFERENCES user_account(id) ON DELETE SET NULL, -- NULL-user comments are displayed as a (deleted-user comment)
  CONSTRAINT COMMENT_FK_id_content FOREIGN KEY (id_content) REFERENCES content(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS content_share (
  id SERIAL PRIMARY KEY NOT NULL, -- not necessary??
  id_user INT NOT NULL,
  id_content INT NOT NULL,
  CONSTRAINT CONTENT_SHARE_FK_id_user FOREIGN KEY (id_user) REFERENCES user_account(id) ON DELETE CASCADE,
  CONSTRAINT CONTENT_SHARE_FK_id_content FOREIGN KEY (id_content) REFERENCES content(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS attachment (
  id SERIAL PRIMARY KEY NOT NULL,
  id_content INT NOT NULL,
  status VARCHAR(1) NOT NULL,
  filename VARCHAR(100) NOT NULL,
  title VARCHAR(50) NOT NULL,
  description VARCHAR(255) NULL,
  create_date DATE NOT NULL,
  CONSTRAINT ATTACHMENT_FK_id_content FOREIGN KEY (id_content) REFERENCES content(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS banned_ip (
  id SERIAL PRIMARY KEY NOT NULL,
  ip VARCHAR(15) NOT NULL,
  create_date DATE NOT NULL,
  expire_date DATE NOT NULL,
  description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS logs (
  id SERIAL PRIMARY KEY NOT NULL,
  description TEXT NOT NULL,
  create_date DATE NOT NULL
);

CREATE INDEX USER_INDEXED_login_password ON user_account (login, password);