ALTER TABLE user_account ADD CONSTRAINT login_unique UNIQUE (login);
ALTER TABLE user_account ADD CONSTRAINT email_unique UNIQUE (email);
ALTER TABLE module ADD CONSTRAINT name_unique UNIQUE (name);
ALTER TABLE user_group ADD CONSTRAINT permission_unique UNIQUE (permissions);
ALTER TABLE user_description DROP COLUMN description;
ALTER TABLE menu DROP COLUMN id_user;
ALTER TABLE comment RENAME COLUMN  description TO content;
ALTER TABLE attachment ADD COLUMN mime VARCHAR(20);