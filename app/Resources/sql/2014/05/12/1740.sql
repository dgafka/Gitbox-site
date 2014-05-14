-- utworzenie nowej tabeli
CREATE TABLE IF NOT EXISTS user_fav_content (
  id SERIAL PRIMARY KEY NOT NULL,
  id_user INT NOT NULL,
  id_content INT NOT NULL,
  CONSTRAINT USER_FAV_CONTENT_FK_id_user FOREIGN KEY (id_user) REFERENCES user_account(id) ON DELETE CASCADE,
  CONSTRAINT USER_FAV_CONTENT_FK_id_content FOREIGN KEY (id_content) REFERENCES content(id) ON DELETE CASCADE
);