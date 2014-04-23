ALTER TABLE user_account
  ADD CONSTRAINT USER_UNIQUE_login UNIQUE (login);
ALTER TABLE content
  DROP CONSTRAINT content_fk_id_user;
ALTER TABLE menu
	ADD COLUMN id_user INT;
ALTER TABLE menu
 ADD CONSTRAINT menu_fk_id_user FOREIGN KEY (id_user)
      REFERENCES user_account (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE;
ALTER TABLE menu
  DROP CONSTRAINT menu_fk_id_category;
ALTER TABLE content
  ADD COLUMN id_category INT;
ALTER TABLE content
	ADD CONSTRAINT content_fk_id_category FOREIGN KEY (id_category)
      REFERENCES category (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE SET NULL;